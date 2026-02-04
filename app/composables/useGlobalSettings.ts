/**
 * Composable for fetching global settings from WordPress
 * Provides centralized access to all site configuration
 * 
 * Settings include:
 * - Brand identity (logo, name, tagline)
 * - Header configuration
 * - Navigation links
 * - Footer configuration
 * - Map & markers settings
 * - SEO defaults
 * - Social media links
 * - Contact information
 * - UI text strings (i18n)
 * - Background images
 */

export interface GlobalSettings {
    brand: {
        logo: string
        logo_dark: string
        favicon: string
        name: string
        highlight: string
        site_name: string
        tagline: string
    }
    header: {
        background_image: string
        sticky: boolean
        cta_text: string
        cta_text_mobile: string
        cta_link: string
    }
    navigation: {
        links: Array<{
            text: string
            to: string
        }>
    }
    footer: {
        logo: string
        tagline: string
        copyright: string
        show_back_to_top: boolean
        back_to_top_text: string
        company_title: string
        policy_title: string
        contact_email: string
        contact_phone: string
    }
    map: {
        maptiler_key: string
        maptiler_style: string
        center: [number, number]
        zoom: number
    }
    markers: {
        type: 'default' | 'custom' | 'circle'
        stadium: string
        arena: string
        festival: string
        default: string
    }
    seo: {
        default_title: string
        title_template: string
        default_description: string
        keywords: string
        og_image: string
        twitter_site: string
        canonical_base: string
    }
    social: {
        facebook: string
        instagram: string
        twitter: string
        linkedin: string
        youtube: string
        tiktok: string
    }
    contact: {
        email: string
        phone: string
        address: string
        hours: string
        legal_email: string
    }
    config: {
        jobs_per_page: number
        events_per_page: number
        blog_per_page: number
        locale: string
    }
    strings: {
        loading: string
        no_results: string
        error_generic: string
        submit_button: string
        send_message: string
        sending: string
        message_sent: string
        thank_you: string
        apply_now: string
        view_details: string
        read_more: string
        back_to_jobs: string
        job_not_found: string
        all_job_titles: string
        all_sites: string
        search_placeholder: string
    }
    backgrounds: {
        hero_default: string
        about_hero: string
        contact_hero: string
        careers_hero: string
        events_hero: string
        blog_hero: string
        decoration_1: string
        decoration_2: string
        vector_bg: string
    }
    contact_form?: {
        cf7_form_id?: string
        cf7_job_application_form_id?: string
    }
}

// Local fallback settings
const defaultSettings: GlobalSettings = {
    brand: {
        logo: '',
        logo_dark: '',
        favicon: '',
        name: 'Eat is',
        highlight: 'Family',
        site_name: 'Eat Is Family',
        tagline: 'Event Catering & Food Experiences'
    },
    header: {
        background_image: '',
        sticky: true,
        cta_text: 'Get in touch',
        cta_text_mobile: 'Contact',
        cta_link: '/contact'
    },
    navigation: {
        links: [
            { text: 'About', to: '/about' },
            { text: 'Activities', to: '/apply-activities' },
            { text: 'Our Events', to: '/events' },
            { text: 'Careers', to: '/careers' },
            { text: 'Blogs', to: '/blog' }
        ]
    },
    footer: {
        logo: '',
        tagline: 'Creating unforgettable culinary experiences that bring people together.',
        copyright: '© {year} Eat is Family. All Rights Reserved.',
        show_back_to_top: true,
        back_to_top_text: 'Back to top',
        company_title: 'Company',
        policy_title: 'Policy Info',
        contact_email: 'hello@eatisfamily.fr',
        contact_phone: '+33 1 23 5 67 89'
    },
    map: {
        maptiler_key: '',
        maptiler_style: 'https://api.maptiler.com/maps/streets/style.json',
        center: [2.0, 48.5],
        zoom: 5
    },
    markers: {
        type: 'custom',
        stadium: '',
        arena: '',
        festival: '',
        default: ''
    },
    seo: {
        default_title: 'Eat Is Family - Event Catering & Food Experiences',
        title_template: '%s | Eat Is Family',
        default_description: 'Eat Is Family delivers exceptional catering for stadiums, arenas, and festivals across France.',
        keywords: 'event catering, stadium catering, food service, VIP hospitality',
        og_image: '/images/og-default.jpg',
        twitter_site: '@eatisfamily',
        canonical_base: 'https://eatisfamily.fr'
    },
    social: {
        facebook: 'https://facebook.com/eatisfamily',
        instagram: 'https://instagram.com/eatisfamily',
        twitter: 'https://twitter.com/eatisfamily',
        linkedin: 'https://linkedin.com/company/eatisfamily',
        youtube: '',
        tiktok: ''
    },
    contact: {
        email: 'hello@eatisfamily.fr',
        phone: '+33 1 23 5 67 89',
        address: '123 Rue de la Gastronomie, 75001 Paris, France',
        hours: 'Monday - Friday: 9:00 AM - 6:00 PM',
        legal_email: 'legal@eatisfamily.com'
    },
    config: {
        jobs_per_page: 6,
        events_per_page: 9,
        blog_per_page: 6,
        locale: 'fr'
    },
    strings: {
        loading: 'Chargement...',
        no_results: 'Aucun résultat trouvé.',
        error_generic: 'Une erreur est survenue. Veuillez réessayer.',
        submit_button: 'Envoyer',
        send_message: 'Envoyer le message',
        sending: 'Envoi en cours...',
        message_sent: 'Message envoyé !',
        thank_you: 'Merci de nous avoir contactés. Nous vous répondrons rapidement.',
        apply_now: 'Postuler',
        view_details: 'Voir les détails',
        read_more: 'Lire la suite',
        back_to_jobs: 'Voir toutes les offres',
        job_not_found: 'Offre non trouvée',
        all_job_titles: 'Tous les postes',
        all_sites: 'Tous les sites',
        search_placeholder: 'Rechercher...'
    },
    backgrounds: {
        hero_default: '/images/heroBg.svg',
        about_hero: '/images/events-hero.jpg',
        contact_hero: '',
        careers_hero: '/images/dida.svg',
        events_hero: '/images/events-hero.jpg',
        blog_hero: '/images/bBlog.svg',
        decoration_1: '/images/decoHeaderBg.svg',
        decoration_2: '/images/loris.svg',
        vector_bg: '/images/vectorBgAbout.svg'
    }
}

export const useGlobalSettings = () => {
    const { fetchData } = useApi()
    
    // Use useState to cache settings across components
    const settings = useState<GlobalSettings | null>('globalSettings', () => null)
    const isLoaded = useState<boolean>('globalSettingsLoaded', () => false)

    /**
     * Deep merge two objects - source values take priority over non-empty values
     */
    const deepMerge = (target: any, source: any): any => {
        const result = { ...target }
        for (const key in source) {
            if (source[key] !== null && source[key] !== undefined && source[key] !== '') {
                if (typeof source[key] === 'object' && !Array.isArray(source[key])) {
                    result[key] = deepMerge(target[key] || {}, source[key])
                } else if (Array.isArray(source[key]) && source[key].length > 0) {
                    result[key] = source[key]
                } else if (!Array.isArray(source[key])) {
                    result[key] = source[key]
                }
            }
        }
        return result
    }

    /**
     * Load global settings from WordPress API
     * Falls back to default settings if API unavailable
     */
    const loadSettings = async (): Promise<GlobalSettings> => {
        // Return cached settings if already loaded
        if (isLoaded.value && settings.value) {
            return settings.value
        }

        try {
            console.log('%c[GlobalSettings] 📡 Fetching settings from WordPress...', 'color: #A0C4FF;')
            const wpSettings = await fetchData<Partial<GlobalSettings>>('settings')
            
            if (wpSettings) {
                // Merge WordPress settings with defaults
                const mergedSettings = deepMerge(defaultSettings, wpSettings)
                settings.value = mergedSettings
                isLoaded.value = true
                console.log('%c[GlobalSettings] ✅ Settings loaded and merged', 'color: green;')
                return mergedSettings
            }
        } catch (error) {
            console.error('%c[GlobalSettings] ❌ Failed to load settings from API', 'color: red;', error)
        }

        // Return default settings if API fails
        console.log('%c[GlobalSettings] ⚠️ Using default settings', 'color: orange;')
        settings.value = defaultSettings
        isLoaded.value = true
        return defaultSettings
    }

    /**
     * Get a specific setting value with dot notation
     * e.g., getSetting('brand.name') returns 'Eat is'
     */
    const getSetting = <T>(path: string, fallback?: T): T => {
        if (!settings.value) {
            return fallback as T
        }

        const keys = path.split('.')
        let value: any = settings.value
        
        for (const key of keys) {
            if (value && typeof value === 'object' && key in value) {
                value = value[key]
            } else {
                return fallback as T
            }
        }
        
        return (value ?? fallback) as T
    }

    /**
     * Get UI string with fallback
     */
    const getString = (key: keyof GlobalSettings['strings']): string => {
        return settings.value?.strings?.[key] || defaultSettings.strings[key] || key
    }

    /**
     * Get background image URL with fallback
     */
    const getBackground = (key: keyof GlobalSettings['backgrounds']): string => {
        return settings.value?.backgrounds?.[key] || defaultSettings.backgrounds[key] || ''
    }

    /**
     * Get marker image URL for a venue type
     */
    const getMarkerImage = (venueType: string): string => {
        const type = venueType.toLowerCase() as keyof Omit<GlobalSettings['markers'], 'type'>
        if (type in (settings.value?.markers || {})) {
            return settings.value?.markers?.[type as 'stadium' | 'arena' | 'festival' | 'default'] || ''
        }
        return settings.value?.markers?.default || ''
    }

    /**
     * Get social media link
     */
    const getSocialLink = (platform: keyof GlobalSettings['social']): string => {
        return settings.value?.social?.[platform] || ''
    }

    /**
     * Format page title using template
     */
    const formatPageTitle = (pageTitle: string): string => {
        const template = settings.value?.seo?.title_template || defaultSettings.seo.title_template
        return template.replace('%s', pageTitle)
    }

    /**
     * Replace {year} placeholder in copyright text
     */
    const getCopyrightText = (): string => {
        const text = settings.value?.footer?.copyright || defaultSettings.footer.copyright
        return text.replace('{year}', new Date().getFullYear().toString())
    }

    return {
        settings: computed(() => settings.value),
        isLoaded: computed(() => isLoaded.value),
        loadSettings,
        getSetting,
        getString,
        getBackground,
        getMarkerImage,
        getSocialLink,
        formatPageTitle,
        getCopyrightText
    }
}
