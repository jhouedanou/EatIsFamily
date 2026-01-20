export interface Shop {
  id: string
  name: string
  image: string
}

export interface MenuItem {
  id: string
  name: string
  price: string
  description: string
  thumbnail: string
}

export interface Venue {
  id: string
  name: string
  location: string
  type?: string
  lat: number
  lng: number
  image?: string
  image2?: string
  logo?: string
  capacity?: string
  staff_members?: number
  recent_event?: string
  guests_served?: string
  shops_count?: number
  menus_count?: number
  description?: string
  services?: string[]
  shops?: Shop[]
  menu_items?: MenuItem[]
}

export interface EventType {
  id: string
  name: string
  image: string
}

export interface Stat {
  value: string
  label: string
}

export interface LocationsData {
  title: string
  description: string
  filter_label: string
  event_types: EventType[]
  stats: Stat[]
  map_venues: Venue[]
}

export const useLocations = () => {
    const { fetchData } = useApi()

    const getLocations = async (): Promise<LocationsData | null> => {
        return await fetchData<LocationsData>('locations.json')
    }

    return {
        getLocations
    }
}
