export interface Event {
    id: number
    title: string
    image: string
    description: string
    event_type: string
}

export const useEvents = () => {
    const { fetchData, fetchSingle, useLocalFallback } = useApi()

    /**
     * Get all events from WordPress API or local fallback
     */
    const getEvents = async (): Promise<Event[] | null> => {
        return await fetchData<Event[]>('events', 'events.json')
    }

    /**
     * Get a single event by ID
     */
    const getEventById = async (id: number): Promise<Event | null> => {
        // Try to fetch from API first
        if (!useLocalFallback) {
            const event = await fetchSingle<Event>('events', id)
            if (event) return event
        }
        
        // Fallback: fetch all and filter
        const events = await getEvents()
        if (!events) return null
        return events.find(event => event.id === id) || null
    }

    /**
     * Get events filtered by type
     */
    const getEventsByType = async (eventType: string): Promise<Event[] | null> => {
        const events = await getEvents()
        if (!events) return null
        return events.filter(event => event.event_type.toLowerCase() === eventType.toLowerCase())
    }

    return {
        getEvents,
        getEventById,
        getEventsByType
    }
}

