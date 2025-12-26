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
    const { fetchData } = useApi()

    const getActivities = async (): Promise<Activity[] | null> => {
        return await fetchData<Activity[]>('activities.json')
    }

    const getActivityBySlug = async (slug: string): Promise<Activity | null> => {
        const activities = await getActivities()
        if (!activities) return null
        return activities.find(activity => activity.slug === slug) || null
    }

    return {
        getActivities,
        getActivityBySlug
    }
}
