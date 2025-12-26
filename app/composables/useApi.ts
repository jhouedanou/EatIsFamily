export const useApi = () => {
    const fetchData = async <T>(endpoint: string): Promise<T | null> => {
        try {
            const { data, error } = await useFetch<T>(`/api/${endpoint}`, {
                key: endpoint,
                // Enable caching for better performance
                getCachedData: (key) => {
                    return useNuxtData(key).data.value as T
                }
            })

            if (error.value) {
                console.error(`Error fetching ${endpoint}:`, error.value)
                return null
            }

            return data.value
        } catch (err) {
            console.error(`Failed to fetch ${endpoint}:`, err)
            return null
        }
    }

    return {
        fetchData
    }
}
