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
    modified: string
    author: {
        id: number
        name: string
        avatar: string
    }
    categories: Array<{
        id: number
        name: string
        slug: string
    }>
    tags: string[]
    featured_media: string
    reading_time: string
}

export const useBlog = () => {
    const { fetchData } = useApi()

    const getBlogPosts = async (): Promise<BlogPost[] | null> => {
        return await fetchData<BlogPost[]>('blog-posts.json')
    }

    const getBlogPostBySlug = async (slug: string): Promise<BlogPost | null> => {
        const posts = await getBlogPosts()
        if (!posts) return null
        return posts.find(post => post.slug === slug) || null
    }

    return {
        getBlogPosts,
        getBlogPostBySlug
    }
}
