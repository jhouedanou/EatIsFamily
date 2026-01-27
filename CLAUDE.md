# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

```bash
# Install dependencies
npm install

# Development server (http://localhost:3000)
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Prepare Nuxt (auto-imports, types)
npm run postinstall
```

## Project Overview

EatIsFriday is a Nuxt 4 application for a food service business (catering, stadium concessions, events) built with Vue 3, TypeScript, and Tailwind CSS. The application uses a **composable-first architecture** with static JSON data files instead of a backend API.

**Tech Stack:**
- Nuxt 4.2.2 (Vue 3.5.26, Vue Router 4.6.4)
- TypeScript
- Tailwind CSS (custom theme)
- Leaflet for interactive maps
- GSAP for animations
- Lucide Vue Next for icons

## Architecture

### Data Management Pattern

The application uses **Vue 3 Composables** for state management without Pinia or Vuex:

1. **Base Layer** - `composables/useApi.ts`:
   - Generic `fetchData<T>(endpoint)` wrapper around Nuxt's `useFetch`
   - Handles caching via `getCachedData` and `useNuxtData`

2. **Domain Layer** - Specialized composables:
   - `useJobs()` - Job listings and details
   - `useBlog()` - Blog posts
   - `useEvents()` - Events
   - `useActivities()` - Activities
   - `useSiteContent()` - Global site configuration
   - `usePageContent()` - Page-specific content
   - `useVenues()` - Venues/stadiums data

3. **Data Source** - WordPress REST API with local JSON fallback:
   - Primary: WordPress API at `https://bigfive.dev/eatisfamily/index.php/wp-json/eatisfamily/v1/`
   - Fallback: Static JSON files in `/public/api/` when API is unavailable
   - Configuration via environment variables (see `.env.example`)

**API Endpoints:**
- `/activities` - List of activities
- `/activities/{slug}` - Single activity by slug
- `/blog-posts` - List of blog posts
- `/blog-posts/{slug}` - Single blog post by slug
- `/events` - List of events
- `/events/{id}` - Single event by ID
- `/jobs` - List of job postings
- `/jobs/{slug}` - Single job by slug
- `/venues` - List of venues with metadata
- `/venues/{id}` - Single venue by ID
- `/site-content` - Global site configuration
- `/pages-content` - Page-specific content

**Environment Variables:**
```bash
# WordPress API Base URL
NUXT_PUBLIC_API_BASE_URL=https://bigfive.dev/eatisfamily/index.php/wp-json/eatisfamily/v1

# Use local JSON fallback (for offline development)
NUXT_PUBLIC_USE_LOCAL_FALLBACK=false
```

**Example composable usage in components:**
```typescript
const { getBlogPosts, getBlogPostBySlug } = useBlog()
const posts = await getBlogPosts()
```

### Routing Structure

File-based routing with two dynamic segments:
- `/blog/[slug]` - Blog post detail pages (slug-based)
- `/jobs/[id]` - Job detail pages (id-based)

Key pages:
- `/` - Landing page with hero, services, map, gallery
- `/careers` - Job listings with venue filtering
- `/blog` - Blog post listing
- `/events` - Events listing
- `/contact` - Contact form

### Component Organization

**20 reusable components** organized by purpose:

```
components/
├── cards/              # Data display cards
│   ├── JobCard.vue
│   ├── BlogCard.vue
│   ├── EventCard.vue
│   └── ActivityCard.vue
├── forms/              # Application forms
│   ├── ContactForm.vue
│   ├── JobApplicationForm.vue
│   └── ActivityApplicationForm.vue
├── layout/             # Navigation and structure
│   ├── Header.vue      # Sticky header with navigation
│   ├── Navigation.vue  # Mobile menu
│   ├── Footer.vue      # Global footer
│   └── ContactModal.vue
├── home/               # Homepage-specific
│   └── DesignSection.vue
├── BaseButton.vue      # Polymorphic button (see below)
├── SectionHeader.vue
├── VenueMap.vue        # Leaflet interactive map
└── CustomMap.vue
```

**BaseButton Pattern** - Single source of truth for buttons:
- Renders as `<NuxtLink>` (internal), `<a>` (external), or `<button>` based on props
- Variants: `primary` (pink), `secondary` (white), `lime`, `outline`, `dark`
- Sizes: `sm`, `md`, `lg`
- Props: `to`, `href`, `variant`, `size`, `block`

### Design System

**Brand Colors** (from tailwind.config.js):
- Pink: `#FF4D6D` (primary brand color)
- Lime: `#C8F560` (accent)
- Yellow: `#FFDD00`
- Blue: `#A0C4FF`
- Purple: `#E4B1F0`

**Typography**:
- Headings: Recoleta (serif) - Custom font in `/public/fonts/`
- Body: Plus Jakarta Sans (sans-serif) - Loaded via Google Fonts
- Apply with: `font-heading`, `font-body`

**Organic Design Language**:
- Custom box shadows with offset: `shadow-organic`, `shadow-organic-lg`, `shadow-organic-hover`
- Pattern: `4px 4px 0 rgba(0, 0, 0, 1)` for playful, bold aesthetic
- Use these instead of standard Tailwind shadows for brand consistency

### TypeScript Interfaces

Key data structures defined in composables:

```typescript
// Job listing
interface Job {
  id: number
  slug: string
  title: string
  excerpt: string
  content: string
  location: string
  job_type: string
  salary: string
  requirements: string[]
  benefits: string[]
  featured_media: string
  date: string
  status: string
}

// Blog post
interface BlogPost {
  id: number
  slug: string
  title: string
  excerpt: string
  content: string
  date: string
  modified: string
  author: { name: string, avatar: string }
  categories: string[]
  tags: string[]
  featured_media: string
  reading_time: number
}

// Similar patterns for Event, Activity, SiteContent
```

### Important Patterns

1. **Client-Only Components**:
   - Leaflet maps must be wrapped in `<ClientOnly>` to prevent SSR issues
   - Example: `VenueMap.vue` uses this pattern

2. **Composable Composition**:
   - Domain composables call `useApi().fetchData()` for data fetching
   - Type safety enforced with TypeScript generics
   - Caching handled automatically by Nuxt

3. **Image Optimization**:
   - Use `<NuxtImg>` component from `@nuxt/image` module
   - Lazy loading enabled on card components

4. **Animation**:
   - GSAP available for complex animations (see `pages/index.vue` hero)
   - Tailwind transitions for hover states

## Configuration Files

**nuxt.config.ts** - Main configuration:
- Modules: `@nuxtjs/tailwindcss`, `@nuxt/image`, `@nuxtjs/google-fonts`, `@nuxtjs/leaflet`
- Custom CSS: `~/assets/css/main.css`
- SEO metadata configured in `app.head`

**tailwind.config.js** - Design system:
- Custom color palette, fonts, and shadow utilities
- Extend (don't override) Tailwind defaults

## Adding New Features

**New Data Type:**
1. Create JSON file in `/public/api/your-data.json`
2. Define TypeScript interface
3. Create composable in `/composables/useYourData.ts` following existing patterns
4. Import and use in components

**New Page:**
1. Add file to `/pages/your-page.vue`
2. Routing is automatic (file-based)
3. Use `layouts/default.vue` by default

**New Component:**
1. Add to appropriate subdirectory in `/components/`
2. Auto-imported by Nuxt (no manual imports needed)
3. Follow naming conventions: PascalCase for multi-word components
