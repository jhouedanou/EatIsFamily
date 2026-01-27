export interface SiteContent {
    site: {
        name: string
        tagline: string
        description: string
    }
    home: any
    about: any
    contact: any
    footer: any
}

export const useSiteContent = () => {
    const { fetchData } = useApi()

    /**
     * Get site content from WordPress API or local fallback
     */
    const getSiteContent = async (): Promise<SiteContent | null> => {
        return await fetchData<SiteContent>('site-content', 'site-content.json')
    }

    return {
        getSiteContent
    }
}

