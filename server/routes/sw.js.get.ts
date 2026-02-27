/**
 * Service Worker fallback route for dev mode
 * Prevents 404 errors on /sw.js that crash the Nuxt dev error overlay
 * In production, the PWA module generates the actual sw.js
 */
export default defineEventHandler((event) => {
    setResponseHeader(event, 'Content-Type', 'application/javascript')
    setResponseHeader(event, 'Cache-Control', 'no-cache')
    return '// Service worker not available in dev mode'
})
