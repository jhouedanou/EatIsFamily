export interface BlogPost {
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
    featured_media: string
    // Champs optionnels
    author?: {
        name: string
        avatar: string
    }
    reading_time?: string
    categories?: Array<{
        id: number
        name: string
    }>
}

export const useBlog = () => {
    /**
     * Get all blog posts via the Nuxt server proxy (avoids CORS issues)
     * The server route /nuxt-api/blog-posts fetches from WordPress and falls back to local JSON
     */
    const getBlogPosts = async (): Promise<BlogPost[]> => {
        try {
            const posts = await $fetch<BlogPost[]>('/nuxt-api/blog-posts')
            return posts || []
        } catch (err) {
            console.error('[Blog] Failed to fetch blog posts:', err)
            return []
        }
    }

    /**
     * Get a single blog post by slug
     * Tries Nuxt cache first (from blog index), then fetches via server proxy
     */
    const getBlogPostBySlug = async (slug: string): Promise<BlogPost | null> => {
        // Try cached data from blog index first (avoids extra network request)
        try {
            const cachedPosts = useNuxtData<BlogPost[]>('blog-posts')
            const cached = cachedPosts?.data?.value?.find((p) => p.slug === slug)
            if (cached) return cached
        } catch {
            // useNuxtData may not be available in all contexts
        }

        // Fallback: fetch all posts via server proxy
        const posts = await getBlogPosts()
        return posts.find(post => post.slug === slug) || null
    }

    return {
        getBlogPosts,
        getBlogPostBySlug
    }
}

