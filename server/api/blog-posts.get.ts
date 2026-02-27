/**
 * Blog Posts Proxy API Endpoint
 * GET /api/blog-posts
 *
 * Proxies blog post requests to WordPress REST API (server-side)
 * This avoids CORS issues when fetching from the frontend during client-side navigation.
 * Falls back to local JSON data if WordPress API is unavailable.
 */

import { readFileSync } from 'fs'
import { join } from 'path'

export default defineEventHandler(async (_event) => {
    const config = useRuntimeConfig()
    const apiBaseUrl = String(config.public.apiBaseUrl || '')
    const useLocalFallback = config.public.useLocalFallback === true || config.public.useLocalFallback === 'true'

    // If local fallback is forced, serve local JSON directly
    if (useLocalFallback || !apiBaseUrl) {
        return getLocalBlogPosts()
    }

    try {
        // Fetch from WordPress API server-side (no CORS issues)
        const data = await $fetch(apiBaseUrl + '/blog-posts', {
            timeout: 10000,
            headers: { 'Accept': 'application/json' }
        })

        // Ensure we return an array
        if (Array.isArray(data)) {
            return data
        }

        console.warn('[Server API] WordPress returned non-array data, falling back to local')
        return getLocalBlogPosts()
    } catch (err: unknown) {
        const message = err instanceof Error ? err.message : String(err)
        console.warn('[Server API] WordPress fetch failed:', message)
        // Fallback to local JSON
        return getLocalBlogPosts()
    }
})

/**
 * Read blog posts from local JSON file
 */
function getLocalBlogPosts(): unknown[] {
    try {
        const filePath = join(process.cwd(), 'public', 'data', 'blog-posts.json')
        const raw = readFileSync(filePath, 'utf-8')
        const data = JSON.parse(raw)
        return Array.isArray(data) ? data : []
    } catch (err: unknown) {
        const message = err instanceof Error ? err.message : String(err)
        console.warn('[Server API] Failed to read local blog-posts.json:', message)
        return []
    }
}
