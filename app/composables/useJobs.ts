export interface Job {
    id: number
    slug: string
    title: {
        rendered: string
    }
    excerpt: {
        rendered: string
    }
    content: {
        rendered: string
    }
    location: string
    department: string
    job_type: string
    salary: string
    requirements: string[]
    benefits: string[]
    featured_media: string
    date: string
    status: string
}

export const useJobs = () => {
    const { fetchData } = useApi()

    const getJobs = async (): Promise<Job[] | null> => {
        return await fetchData<Job[]>('jobs.json')
    }

    const getJobBySlug = async (slug: string): Promise<Job | null> => {
        const jobs = await getJobs()
        if (!jobs) return null
        return jobs.find(job => job.slug === slug) || null
    }

    return {
        getJobs,
        getJobBySlug
    }
}
