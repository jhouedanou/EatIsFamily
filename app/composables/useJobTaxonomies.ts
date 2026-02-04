export interface JobType {
    id: string
    label: string
    label_fr: string
}

export interface Department {
    id: string
    label: string
    label_fr: string
}

export interface JobTaxonomies {
    job_types: JobType[]
    departments: Department[]
}

export const useJobTaxonomies = () => {
    const { fetchData } = useApi()

    /**
     * Get all job taxonomies (types d'emploi & départements)
     */
    const getJobTaxonomies = async (): Promise<JobTaxonomies | null> => {
        return await fetchData<JobTaxonomies>('job-taxonomies', 'job-taxonomies.json')
    }

    /**
     * Get job types only
     */
    const getJobTypes = async (): Promise<JobType[]> => {
        const data = await getJobTaxonomies()
        return data?.job_types || getDefaultJobTypes()
    }

    /**
     * Get departments only
     */
    const getDepartments = async (): Promise<Department[]> => {
        const data = await getJobTaxonomies()
        return data?.departments || getDefaultDepartments()
    }

    /**
     * Get job type label by ID (French label preferred)
     */
    const getJobTypeLabel = async (id: string): Promise<string> => {
        const jobTypes = await getJobTypes()
        const jobType = jobTypes.find(t => t.id === id)
        return jobType?.label_fr || jobType?.label || id
    }

    /**
     * Get department label by ID (French label preferred)
     */
    const getDepartmentLabel = async (id: string): Promise<string> => {
        const departments = await getDepartments()
        const department = departments.find(d => d.id === id)
        return department?.label_fr || department?.label || id
    }

    /**
     * Default job types (fallback)
     */
    const getDefaultJobTypes = (): JobType[] => [
        { id: 'full-time', label: 'Full-time', label_fr: 'Temps plein' },
        { id: 'part-time', label: 'Part-time', label_fr: 'Temps partiel' },
        { id: 'seasonal', label: 'Seasonal', label_fr: 'Saisonnier' },
        { id: 'contract', label: 'Contract', label_fr: 'Contrat' },
        { id: 'internship', label: 'Internship', label_fr: 'Stage' },
    ]

    /**
     * Default departments (fallback)
     */
    const getDefaultDepartments = (): Department[] => [
        { id: 'culinary', label: 'Culinary', label_fr: 'Cuisine' },
        { id: 'service', label: 'Service', label_fr: 'Service' },
        { id: 'beverage', label: 'Beverage', label_fr: 'Boissons' },
        { id: 'operations', label: 'Operations', label_fr: 'Opérations' },
        { id: 'quality', label: 'Quality', label_fr: 'Qualité' },
        { id: 'management', label: 'Management', label_fr: 'Direction' },
        { id: 'marketing', label: 'Marketing', label_fr: 'Marketing' },
        { id: 'hr', label: 'Human Resources', label_fr: 'Ressources Humaines' },
    ]

    return {
        getJobTaxonomies,
        getJobTypes,
        getDepartments,
        getJobTypeLabel,
        getDepartmentLabel,
        getDefaultJobTypes,
        getDefaultDepartments,
    }
}
