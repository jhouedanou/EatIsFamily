export interface Activity {
    id: number
    slug: string
    title: {
        rendered: string
    }
    description: string
    content: {
        rendered: string
    }
    date: string
    location: string
    capacity: number
    available_spots: number
    category: string
    price: string
    duration: string
    featured_media: string
    status: string
}

export const useActivities = () => {
    const { fetchData, fetchSingle, useLocalFallback } = useApi()

    /**
     * Get all activities from WordPress API or local fallback
     */
    const getActivities = async (): Promise<Activity[] | null> => {
        return await fetchData<Activity[]>('activities', 'activities.json')
    }

    /**
     * Get a single activity by slug
     */
    const getActivityBySlug = async (slug: string): Promise<Activity | null> => {
        // Try to fetch from API first
        if (!useLocalFallback) {
            const activity = await fetchSingle<Activity>('activities', slug)
            if (activity) return activity
        }
        
        // Fallback: fetch all and filter
        const activities = await getActivities()
        if (!activities) return null
        return activities.find(activity => activity.slug === slug) || null
    }

    return {
        getActivities,
        getActivityBySlug
    }
}
