/**
 * Job Application API Endpoint
 * POST /api/applications/apply
 * 
 * Handles secure job application submissions with file upload
 */

import { readMultipartFormData, createError, H3Event } from 'h3'
import { writeFile, mkdir, readFile } from 'fs/promises'
import { join } from 'path'
import { existsSync } from 'fs'


// Base directory for storing uploads (outside public folder for security)
const UPLOADS_DIR = join(process.cwd(), 'server', 'uploads', 'applications')
const APPLICATIONS_DATA_DIR = join(process.cwd(), 'server', 'data', 'applications')

export default defineEventHandler(async (event: H3Event) => {
    // Only accept POST requests
    if (event.method !== 'POST') {
        throw createError({
            statusCode: 405,
            statusMessage: 'Method Not Allowed'
        })
    }

    try {
        // 1. Rate limiting check
        const clientIP = getClientIP(event)
        const rateLimitResult = checkRateLimit(clientIP)

        if (rateLimitResult.limited) {
            throw createError({
                statusCode: 429,
                statusMessage: 'Too Many Requests',
                data: {
                    message: `Trop de candidatures. Veuillez réessayer dans ${rateLimitResult.retryAfter} secondes.`,
                    retryAfter: rateLimitResult.retryAfter
                }
            })
        }

        // 2. Parse multipart form data
        const formData = await readMultipartFormData(event)

        if (!formData || formData.length === 0) {
            throw createError({
                statusCode: 400,
                statusMessage: 'Bad Request',
                data: { message: 'Données du formulaire manquantes' }
            })
        }

        // 3. Extract fields from form data
        const fields: Record<string, string> = {}
        let resumeFile: { data: Buffer; filename: string; type: string } | null = null

        for (const field of formData) {
            if (field.name === 'resume' && field.filename) {
                // Handle file
                resumeFile = {
                    data: field.data,
                    filename: field.filename,
                    type: field.type || 'application/octet-stream'
                }
            } else if (field.name && field.data) {
                // Handle text fields
                fields[field.name] = field.data.toString('utf-8')
            }
        }

        // 4. Validate form data (including honeypot check)
        const applicationData: ApplicationFormData = {
            name: fields.name || '',
            email: fields.email || '',
            phone: fields.phone || '',
            linkedin: fields.linkedin || '',
            coverLetter: fields.coverLetter || '',
            jobSlug: fields.jobSlug || '',
            honeypot: fields.website || '' // Honeypot field named "website"
        }

        const validation = validateFormData(applicationData)

        if (!validation.valid) {
            throw createError({
                statusCode: 400,
                statusMessage: 'Validation Error',
                data: {
                    message: 'Erreurs de validation',
                    errors: validation.errors
                }
            })
        }

        // 5. Validate resume file
        if (!resumeFile) {
            throw createError({
                statusCode: 400,
                statusMessage: 'Bad Request',
                data: { message: 'CV requis' }
            })
        }

        // Check file extension
        if (!isAllowedExtension(resumeFile.filename)) {
            throw createError({
                statusCode: 400,
                statusMessage: 'Invalid File Type',
                data: { message: 'Type de fichier non autorisé. Formats acceptés: PDF, DOC, DOCX' }
            })
        }

        // Check MIME type
        if (!isAllowedMimeType(resumeFile.type)) {
            throw createError({
                statusCode: 400,
                statusMessage: 'Invalid File Type',
                data: { message: 'Type MIME non autorisé' }
            })
        }

        // Check file size
        if (!isFileSizeValid(resumeFile.data.length)) {
            throw createError({
                statusCode: 400,
                statusMessage: 'File Too Large',
                data: { message: 'Le fichier est trop volumineux. Taille maximum: 2MB' }
            })
        }

        // 6. Create upload directories if they don't exist
        if (!existsSync(UPLOADS_DIR)) {
            await mkdir(UPLOADS_DIR, { recursive: true })
        }
        if (!existsSync(APPLICATIONS_DATA_DIR)) {
            await mkdir(APPLICATIONS_DATA_DIR, { recursive: true })
        }

        // 7. Generate secure filename and save file
        // 7. Generate secure filename and save file
        const secureFilename = generateSecureFilename(resumeFile.filename)
        const filePath = join(UPLOADS_DIR, secureFilename)

        await writeFile(filePath, resumeFile.data)

        // 8. Determine recipients
        let recipients: string[] = []
        let jobDepartment = 'Unknown'
        let jobTitle = fields.jobTitle || 'Unknown Position'

        try {
            // Read jobs data to find department
            const jobsPath = join(process.cwd(), 'public', 'data', 'jobs.json')
            if (existsSync(jobsPath)) {
                const jobsData = await readFile(jobsPath, 'utf-8')
                const jobs = JSON.parse(jobsData)
                const job = jobs.find((j: any) => j.slug === applicationData.jobSlug)

                if (job) {
                    jobDepartment = job.department || 'Unknown'
                    jobTitle = job.title?.rendered || job.title || 'Unknown Position'
                }
            }

            // Read recipients configuration
            const recipientsPath = join(process.cwd(), 'server', 'data', 'recipients.json')

            if (existsSync(recipientsPath)) {
                const recipientsData = await readFile(recipientsPath, 'utf-8')
                const config = JSON.parse(recipientsData)

                // Strategy: specific department -> default -> fallback
                if (jobDepartment && config.departments && config.departments[jobDepartment]) {
                    recipients = config.departments[jobDepartment]
                } else if (config.default) {
                    recipients = config.default
                }
            }
        } catch (err) {
            console.error('[CONFIG ERROR] Failed to load recipients configuration or job details:', err)
            // Fallback if config fails
            recipients = ['hr@eatisfamily.com']
        }

        if (recipients.length === 0) {
            recipients = ['hr@eatisfamily.com']
        }

        // 9. Create application record
        const applicationId = crypto.randomUUID()
        const applicationRecord = {
            id: applicationId,
            jobSlug: sanitizeInput(applicationData.jobSlug),
            jobTitle: jobTitle,
            department: jobDepartment,
            applicant: {
                name: sanitizeInput(applicationData.name),
                email: sanitizeInput(applicationData.email),
                phone: sanitizeInput(applicationData.phone),
                linkedin: applicationData.linkedin ? sanitizeInput(applicationData.linkedin) : null
            },
            coverLetter: applicationData.coverLetter ? sanitizeInput(applicationData.coverLetter) : null,
            resume: {
                originalFilename: resumeFile.filename,
                storedFilename: secureFilename,
                mimeType: resumeFile.type,
                size: resumeFile.data.length
            },
            routing: {
                recipients: recipients,
                sentTo: recipients // In a real email scenario, this confirms dispatch
            },
            metadata: {
                submittedAt: new Date().toISOString(),
                ipAddress: clientIP,
                userAgent: event.node.req.headers['user-agent'] || 'unknown'
            },
            status: 'pending'
        }

        // 10. Save application data
        const applicationFilePath = join(APPLICATIONS_DATA_DIR, `${applicationId}.json`)
        await writeFile(applicationFilePath, JSON.stringify(applicationRecord, null, 2))

        // 11. Forward to WordPress Contact Form 7
        const wpBaseUrl = process.env.NUXT_PUBLIC_WP_BASE_URL || 'https://www.eatisfamily.fr/api'
        const cf7FormId = '357'

        try {
            const cf7FormData = new FormData()
            cf7FormData.append('your-name', applicationData.name)
            cf7FormData.append('your-email', applicationData.email)
            cf7FormData.append('your-phone', applicationData.phone)
            cf7FormData.append('your-linkedin', applicationData.linkedin || '')
            cf7FormData.append('your-message', applicationData.coverLetter || '')
            cf7FormData.append('job-title', fields.jobTitle || jobTitle)
            cf7FormData.append('job-location', fields.jobLocation || '')
            cf7FormData.append('job-slug', applicationData.jobSlug)
            cf7FormData.append('_wpcf7_unit_tag', `wpcf7-f${cf7FormId}-o1`)

            // Attach resume file to CF7
            const blob = new Blob([new Uint8Array(resumeFile.data)], { type: resumeFile.type })
            cf7FormData.append('resume', blob, resumeFile.filename)

            const cf7Endpoint = `${wpBaseUrl}/wp-json/contact-form-7/v1/contact-forms/${cf7FormId}/feedback`
            console.log(`[APPLICATION] Forwarding to CF7: ${cf7Endpoint}`)

            const cf7Response = await fetch(cf7Endpoint, {
                method: 'POST',
                body: cf7FormData,
                headers: { 'Accept': 'application/json' },
            })

            const cf7Result = await cf7Response.text()
            console.log(`[APPLICATION] CF7 response status: ${cf7Response.status}`)
            console.log(`[APPLICATION] CF7 response:`, cf7Result)

            // Update application record with CF7 status
            applicationRecord.status = cf7Response.ok ? 'sent_to_cf7' : 'cf7_error'
            await writeFile(applicationFilePath, JSON.stringify(applicationRecord, null, 2))

        } catch (cf7Error) {
            console.error('[APPLICATION] CF7 forwarding failed:', cf7Error)
            // Don't fail the whole request — the application is already saved locally
            applicationRecord.status = 'cf7_error'
            await writeFile(applicationFilePath, JSON.stringify(applicationRecord, null, 2))
        }

        console.log(`[APPLICATION] New application ${applicationId} for ${jobTitle} (${jobDepartment})`)

        // 12. Return success response
        return {
            success: true,
            message: 'Candidature envoyée avec succès',
            applicationId: applicationId
        }

    } catch (error: any) {
        // Re-throw H3 errors as-is
        if (error.statusCode) {
            throw error
        }

        // Log unexpected errors
        console.error('[APPLICATION ERROR]', error)

        // Return generic error for security
        throw createError({
            statusCode: 500,
            statusMessage: 'Internal Server Error',
            data: { message: 'Une erreur est survenue. Veuillez réessayer.' }
        })
    }
})
