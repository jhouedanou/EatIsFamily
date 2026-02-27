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
    const { fetchData, fetchSingle, useLocalFallback } = useApi()

    /**
     * Get all blog posts from WordPress API or local fallback
     */
    const getBlogPosts = async (): Promise<BlogPost[]> => {
        const posts = await fetchData<BlogPost[]>('blog-posts', 'blog-posts.json')
        return posts || []
    }

    /**
     * Get a single blog post by slug
     * Uses cached data from blog index when available, then falls back to API/local
     */
    const getBlogPostBySlug = async (slug: string): Promise<BlogPost | null> => {
        // 1. Try cached blog posts from Nuxt data layer (already fetched by blog index)
        try {
            const cachedPosts = useNuxtData<BlogPost[]>('blog-posts')
            if (cachedPosts?.data?.value) {
                const found = cachedPosts.data.value.find((post: BlogPost) => post.slug === slug)
                if (found) {
                    console.log(`%c[Blog] ✅ Found post in cache: ${slug}`, 'color: #C8F560;')
                    return found
                }
            }
        } catch (e) {
            // useNuxtData may not be available in all contexts, continue with fetch
        }

        // 2. Try to fetch single post from API
        if (!useLocalFallback) {
            const post = await fetchSingle<BlogPost>('blog-posts', slug)
            if (post) return post
        }
        
        // 3. Fallback: fetch all posts and filter by slug
        const posts = await getBlogPosts()
        return posts.find(post => post.slug === slug) || null
    }

    return {
        getBlogPosts,
        getBlogPostBySlug
    }
}

