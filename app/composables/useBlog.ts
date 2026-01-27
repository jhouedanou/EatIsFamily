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
     */
    const getBlogPostBySlug = async (slug: string): Promise<BlogPost | null> => {
        // Try to fetch from API first
        if (!useLocalFallback) {
            const post = await fetchSingle<BlogPost>('blog-posts', slug)
            if (post) return post
        }
        
        // Fallback: fetch all and filter
        const posts = await getBlogPosts()
        return posts.find(post => post.slug === slug) || null
    }

    return {
        getBlogPosts,
        getBlogPostBySlug
    }
}

