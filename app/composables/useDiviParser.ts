/**
 * Divi Builder Shortcode Parser for Vue/Nuxt
 * 
 * Parses WordPress Divi Builder shortcodes and converts them into
 * a structured tree that can be rendered by Vue components.
 * 
 * Supported modules:
 * - et_pb_section (regular + fullwidth)
 * - et_pb_row (with column structures like 1_3,1_3,1_3)
 * - et_pb_column
 * - et_pb_text
 * - et_pb_image
 * - et_pb_button
 * - et_pb_blurb (icon + title + text)
 * - et_pb_slider / et_pb_fullwidth_slider / et_pb_slide
 * - et_pb_video
 * - et_pb_code (raw HTML/scripts)
 * - et_pb_gallery
 * - et_pb_cta (call to action)
 * - et_pb_divider
 * - et_pb_accordion / et_pb_accordion_item
 * - et_pb_toggle
 * - et_pb_tabs / et_pb_tab
 * - wdcl_image_carousel / wdcl_image_carousel_child
 * - ez-toc (table of contents)
 */

export interface DiviNode {
  type: string
  attrs: Record<string, string>
  children: DiviNode[]
  content: string // Inner HTML content (text between tags)
}

/**
 * Decode HTML entities from WordPress API
 */
function decodeEntities(text: string): string {
  if (!text) return ''
  return text
    .replace(/&quot;/g, '"')
    .replace(/\u0026quot;/g, '"')
    .replace(/&amp;/g, '&')
    .replace(/\u0026amp;/g, '&')
    .replace(/&#038;/g, '&')
    .replace(/&lt;/g, '<')
    .replace(/\u003c/g, '<')
    .replace(/&gt;/g, '>')
    .replace(/\u003e/g, '>')
    .replace(/&apos;/g, "'")
    .replace(/&#8217;/g, "'")
    .replace(/&#8216;/g, "'")
    .replace(/&#8220;/g, '"')
    .replace(/&#8221;/g, '"')
    .replace(/&#8211;/g, '–')
    .replace(/&#8230;/g, '…')
    .replace(/&hellip;/g, '…')
    .replace(/&#039;/g, "'")
    .replace(/&nbsp;/g, ' ')
    .replace(/&rsquo;/g, "'")
    .replace(/&lsquo;/g, "'")
    .replace(/&rdquo;/g, '"')
    .replace(/&ldquo;/g, '"')
    .replace(/\u0026Prime;/g, '"')
    .replace(/\u0026rsquo;/g, "'")
    .replace(/\u0026lsquo;/g, "'")
    .replace(/\u0026rdquo;/g, '"')
    .replace(/\u0026ldquo;/g, '"')
    .replace(/\u0026nbsp;/g, ' ')
    // French smart quotes that Divi uses
    .replace(/»/g, '"')
    .replace(/«/g, '"')
    .replace(/\u00BB/g, '"')
    .replace(/\u00AB/g, '"')
}

/**
 * Parse Divi shortcode attributes string into key-value pairs
 * e.g. 'heading="Hello" button_text="Click"' => { heading: 'Hello', button_text: 'Click' }
 */
function parseAttrs(attrString: string): Record<string, string> {
  const attrs: Record<string, string> = {}
  if (!attrString) return attrs

  // Decode entities first
  const decoded = decodeEntities(attrString)

  // Match key="value" or key='value' patterns
  // Also handle key="value with spaces"
  const regex = /(\w[\w-]*)=\s*"([^"]*?)"/g
  let match: RegExpExecArray | null

  while ((match = regex.exec(decoded)) !== null) {
    const key = match[1]
    const val = match[2]
    if (key !== undefined && val !== undefined) {
      attrs[key] = val
    }
  }

  // Also try single quotes
  const singleQuoteRegex = /(\w[\w-]*)=\s*'([^']*?)'/g
  while ((match = singleQuoteRegex.exec(decoded)) !== null) {
    const key = match[1]
    const val = match[2]
    if (key !== undefined && val !== undefined && !attrs[key]) {
      attrs[key] = val
    }
  }

  // Handle Divi's special format: key="value" (with smart quotes)
  const smartQuoteRegex = /(\w[\w-]*)=\s*[\u201C\u201D""]([^\u201C\u201D""]*?)[\u201C\u201D""]/g
  while ((match = smartQuoteRegex.exec(decoded)) !== null) {
    const key = match[1]
    const val = match[2]
    if (key !== undefined && val !== undefined && !attrs[key]) {
      attrs[key] = val
    }
  }

  return attrs
}

/**
 * List of known Divi shortcode tags (self-closing and container)
 */
const DIVI_TAGS = [
  'et_pb_section',
  'et_pb_row',
  'et_pb_row_inner',
  'et_pb_column',
  'et_pb_column_inner',
  'et_pb_text',
  'et_pb_image',
  'et_pb_button',
  'et_pb_blurb',
  'et_pb_fullwidth_slider',
  'et_pb_slider',
  'et_pb_slide',
  'et_pb_video',
  'et_pb_video_slider',
  'et_pb_video_slider_item',
  'et_pb_code',
  'et_pb_gallery',
  'et_pb_cta',
  'et_pb_divider',
  'et_pb_accordion',
  'et_pb_accordion_item',
  'et_pb_toggle',
  'et_pb_tabs',
  'et_pb_tab',
  'et_pb_counters',
  'et_pb_counter',
  'et_pb_number_counter',
  'et_pb_circle_counter',
  'et_pb_pricing_tables',
  'et_pb_pricing_table',
  'et_pb_testimonial',
  'et_pb_fullwidth_image',
  'et_pb_fullwidth_header',
  'et_pb_fullwidth_menu',
  'et_pb_fullwidth_code',
  'et_pb_fullwidth_post_slider',
  'et_pb_blog',
  'et_pb_sidebar',
  'et_pb_social_media_follow',
  'et_pb_social_media_follow_network',
  'et_pb_map',
  'et_pb_map_pin',
  'et_pb_contact_form',
  'et_pb_contact_field',
  'et_pb_signup',
  'et_pb_team_member',
  'et_pb_portfolio',
  'et_pb_filterable_portfolio',
  'et_pb_post_slider',
  'et_pb_post_title',
  'et_pb_comments',
  'et_pb_login',
  'et_pb_search',
  'et_pb_audio',
  'et_pb_countdown_timer',
  // Third party Divi modules
  'wdcl_image_carousel',
  'wdcl_image_carousel_child',
  'ez-toc',
]

/**
 * Self-closing tags that don't have a closing tag
 */
const SELF_CLOSING_TAGS = [
  'et_pb_divider',
  'et_pb_image',
  'et_pb_fullwidth_image',
  'et_pb_button',
  'et_pb_video',
  'et_pb_number_counter',
  'et_pb_circle_counter',
  'et_pb_map_pin',
  'et_pb_contact_field',
  'et_pb_audio',
  'et_pb_countdown_timer',
  'et_pb_post_title',
  'ez-toc',
]

/**
 * Tokenize the raw content into a flat list of tokens:
 * - OPEN_TAG: [tag_name attrs...]
 * - CLOSE_TAG: [/tag_name]
 * - SELF_CLOSE_TAG: [tag_name attrs.../] (Divi uses [tag_name ...][/tag_name] instead typically)
 * - CONTENT: raw HTML/text between tags
 */
interface Token {
  type: 'open' | 'close' | 'content'
  tag?: string
  attrs?: string
  text?: string
}

function tokenize(raw: string): Token[] {
  const tokens: Token[] = []
  // Build regex to match any Divi shortcode
  const tagPattern = DIVI_TAGS.map(t => t.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')).join('|')
  // Match opening [tag ...], closing [/tag], and self-closing [tag .../]
  const regex = new RegExp(
    `\\[\\/(${tagPattern})\\]|\\[(${tagPattern})((?:\\s+[^\\]]*)?)\\]`,
    'gi'
  )

  let lastIndex = 0
  let match: RegExpExecArray | null

  while ((match = regex.exec(raw)) !== null) {
    // Capture any text/HTML before this shortcode
    if (match.index > lastIndex) {
      const text = raw.slice(lastIndex, match.index).trim()
      if (text) {
        tokens.push({ type: 'content', text })
      }
    }

    if (match[1]) {
      // Closing tag: [/tag]
      tokens.push({ type: 'close', tag: match[1].toLowerCase() })
    } else if (match[2]) {
      // Opening tag: [tag attrs]
      const tag = match[2].toLowerCase()
      const attrs = match[3] || ''

      // Check if this is typically a self-closing tag in Divi
      // (Divi often uses [tag][/tag] even for self-closing, so we check both patterns)
      if (SELF_CLOSING_TAGS.includes(tag)) {
        // Look ahead to see if there's an immediate closing tag
        const closePattern = new RegExp(`^\\s*\\[\\/${tag.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')}\\]`, 'i')
        const remaining = raw.slice(regex.lastIndex)
        const closeMatch = closePattern.exec(remaining)
        if (closeMatch) {
          // Skip the closing tag
          regex.lastIndex += closeMatch[0].length
        }
        // Always emit as open (we'll handle in tree builder)
        tokens.push({ type: 'open', tag, attrs: attrs.trim() })
        // Immediately close self-closing tags
        if (!closeMatch) {
          tokens.push({ type: 'close', tag })
        } else {
          tokens.push({ type: 'close', tag })
        }
      } else {
        tokens.push({ type: 'open', tag, attrs: attrs.trim() })
      }
    }

    lastIndex = regex.lastIndex
  }

  // Remaining content after last shortcode
  if (lastIndex < raw.length) {
    const text = raw.slice(lastIndex).trim()
    if (text) {
      tokens.push({ type: 'content', text })
    }
  }

  return tokens
}

/**
 * Build a tree of DiviNodes from the flat token list
 */
function buildTree(tokens: Token[]): DiviNode[] {
  const root: DiviNode[] = []
  const stack: DiviNode[] = []

  for (const token of tokens) {
    if (token.type === 'open') {
      const node: DiviNode = {
        type: token.tag || 'unknown',
        attrs: parseAttrs(token.attrs || ''),
        children: [],
        content: ''
      }
      const parent = stack.length > 0 ? stack[stack.length - 1] : null
      if (parent) {
        parent.children.push(node)
      } else {
        root.push(node)
      }
      stack.push(node)
    } else if (token.type === 'close') {
      const top = stack.length > 0 ? stack[stack.length - 1] : null
      if (top && top.type === token.tag) {
        stack.pop()
      } else {
        // Mismatched close tag - try to recover
        // Pop until we find the matching open tag
        let found = false
        for (let i = stack.length - 1; i >= 0; i--) {
          const item = stack[i]
          if (item && item.type === token.tag) {
            stack.splice(i)
            found = true
            break
          }
        }
        if (!found && stack.length > 0) {
          // If no match found, just pop the top
          stack.pop()
        }
      }
    } else if (token.type === 'content') {
      const htmlContent = decodeEntities(token.text || '')
      const current = stack.length > 0 ? stack[stack.length - 1] : null
      if (current) {
        // Append to current node's content
        current.content += htmlContent
      } else {
        // Top-level content - wrap in a text node
        root.push({
          type: 'raw_html',
          attrs: {},
          children: [],
          content: htmlContent
        })
      }
    }
  }

  return root
}

/**
 * Check if content contains Divi shortcodes
 */
function hasDiviShortcodes(content: string): boolean {
  if (!content) return false
  return /\[et_pb_|^\[et_pb_|\[wdcl_|\[ez-toc/i.test(content)
}

/**
 * Extract plain text content from Divi tree (for excerpts/previews)
 */
function extractPlainText(nodes: DiviNode[], maxLength = 300): string {
  let text = ''

  function walk(node: DiviNode) {
    if (text.length >= maxLength) return

    // Skip non-content modules
    if (['et_pb_code', 'et_pb_video', 'et_pb_divider', 'ez-toc'].includes(node.type)) return

    // Extract text from HTML content
    if (node.content) {
      const stripped = node.content
        .replace(/<[^>]*>/g, '')
        .replace(/\s+/g, ' ')
        .trim()
      if (stripped) {
        text += (text ? ' ' : '') + stripped
      }
    }

    // Recurse into children
    for (const child of node.children) {
      if (text.length >= maxLength) break
      walk(child)
    }
  }

  for (const node of nodes) {
    if (text.length >= maxLength) break
    walk(node)
  }

  if (text.length > maxLength) {
    text = text.slice(0, maxLength).replace(/\s+\S*$/, '') + '…'
  }

  return text
}

/**
 * Extract the first image URL from Divi tree (for cards/thumbnails)
 */
function extractFirstImage(nodes: DiviNode[]): string | null {
  for (const node of nodes) {
    // Check image modules
    if (node.type === 'et_pb_image' || node.type === 'et_pb_fullwidth_image') {
      if (node.attrs.src) return node.attrs.src
    }

    // Check slides for background images
    if (node.type === 'et_pb_slide') {
      if (node.attrs.background_image) return node.attrs.background_image
    }

    // Check blurbs for images
    if (node.type === 'et_pb_blurb') {
      if (node.attrs.image) return node.attrs.image
    }

    // Check image carousel children
    if (node.type === 'wdcl_image_carousel_child') {
      if (node.attrs.photo) return node.attrs.photo
    }

    // Recurse
    const found = extractFirstImage(node.children)
    if (found) return found
  }
  return null
}

/**
 * Known Nuxt static routes (not blog posts)
 * These paths should NOT be prefixed with /blog/
 */
const NUXT_STATIC_ROUTES = [
  'about', 'activities', 'apply-activities', 'apply-jobs',
  'careers', 'contact', 'cookies', 'demo-buttons',
  'events', 'jobs', 'privacy', 'terms', 'blog'
]

/**
 * Rewrite internal WordPress URLs in HTML content to Nuxt routes.
 * - eatisfamily.fr/slug/ → /blog/slug (for blog posts)
 * - eatisfamily.fr/api/wp-content/uploads/ → eatisfamily.fr/sitewordpressOriginal/wp-content/uploads/
 * - eatisfamily.fr/wp-content/uploads/ → eatisfamily.fr/sitewordpressOriginal/wp-content/uploads/
 * - contact-cuisine-locale/#ancre → /contact#ancre
 */
function rewriteInternalLinks(html: string): string {
  if (!html) return ''

  let result = html

  // Rewrite internal page links: eatisfamily.fr/contact-cuisine-locale/ → /contact
  result = result.replace(
    /href="https?:\/\/(?:www\.)?eatisfamily\.fr\/contact-cuisine-locale\/?([^"]*)"/g,
    'href="/contact$1"'
  )

  // Rewrite internal links: eatisfamily.fr/slug/ → /blog/slug
  // But NOT for known static routes, API paths, sitewordpressOriginal, or wp-content
  result = result.replace(
    /href="https?:\/\/(?:www\.)?eatisfamily\.fr\/([^"#?]+?)\/?([#?][^"]*)?">/g,
    (_match, slug, hashOrQuery = '') => {
      // Strip trailing slash from slug
      const cleanSlug = slug.replace(/\/$/, '')

      // Skip non-page URLs
      if (
        cleanSlug.startsWith('api/') ||
        cleanSlug.startsWith('wp-') ||
        cleanSlug.startsWith('sitewordpressOriginal/') ||
        cleanSlug.includes('/wp-content/') ||
        cleanSlug.includes('/wp-json/') ||
        cleanSlug.includes('.php') ||
        cleanSlug.includes('.css') ||
        cleanSlug.includes('.js') ||
        cleanSlug.includes('.jpg') ||
        cleanSlug.includes('.png') ||
        cleanSlug.includes('.webp') ||
        cleanSlug.includes('.pdf')
      ) {
        return `href="https://www.eatisfamily.fr/${slug}${hashOrQuery}">`
      }

      // Check if it matches a known Nuxt static route
      const firstSegment = cleanSlug.split('/')[0]
      if (firstSegment && NUXT_STATIC_ROUTES.includes(firstSegment)) {
        return `href="/${cleanSlug}${hashOrQuery}">`
      }

      // Everything else is a blog post slug
      return `href="/blog/${cleanSlug}${hashOrQuery}">`
    }
  )

  // Also catch simpler href patterns without trailing content
  result = result.replace(
    /href="https?:\/\/(?:www\.)?eatisfamily\.fr\/?">/g,
    'href="/">'
  )

  return result
}

/**
 * Main composable
 */
export const useDiviParser = () => {
  /**
   * Parse raw Divi content into a structured tree
   */
  const parseDiviContent = (rawContent: string): DiviNode[] => {
    if (!rawContent) return []

    // Decode the raw content first (API may double-encode)
    let content = rawContent
    // Handle unicode escapes from JSON
    content = content.replace(/\\u003c/g, '<')
      .replace(/\\u003e/g, '>')
      .replace(/\\u0026/g, '&')
      .replace(/\\n/g, '\n')

    // Decode HTML entities
    content = decodeEntities(content)

    const tokens = tokenize(content)
    return buildTree(tokens)
  }

  /**
   * Check if content is Divi-formatted
   */
  const isDiviContent = (content: string): boolean => {
    return hasDiviShortcodes(decodeEntities(content || ''))
  }

  /**
   * Get plain text excerpt from Divi content
   */
  const getDiviExcerpt = (rawContent: string, maxLength = 200): string => {
    const tree = parseDiviContent(rawContent)
    return extractPlainText(tree, maxLength)
  }

  /**
   * Get first image from Divi content
   */
  const getDiviFirstImage = (rawContent: string): string | null => {
    const tree = parseDiviContent(rawContent)
    return extractFirstImage(tree)
  }

  return {
    parseDiviContent,
    isDiviContent,
    getDiviExcerpt,
    getDiviFirstImage,
    decodeEntities,
    rewriteInternalLinks
  }
}
