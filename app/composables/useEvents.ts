export interface Event {
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
    date: string
    end_date: string
    location: string
    event_type: string
    ticket_price: string
    featured_media: string
    status: string
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
