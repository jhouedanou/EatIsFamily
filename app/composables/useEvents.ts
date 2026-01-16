export interface Event {
    id: number
    slug: string
    title: string
    image: string
    description: string
    event_type: string
}

export const useEvents = () => {
    const { fetchData } = useApi()

    const getEvents = async (): Promise<Event[] | null> => {
        return await fetchData<Event[]>('events.json')
    }

    const getEventBySlug = async (slug: string): Promise<Event | null> => {
        const events = await getEvents()
        if (!events) return null
        return events.find(event => event.slug === slug) || null
    }

    return {
        getEvents,
        getEventBySlug
    }
}
