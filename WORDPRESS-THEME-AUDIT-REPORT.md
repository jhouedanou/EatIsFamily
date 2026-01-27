# WordPress Theme Audit Report
## Comprehensive Analysis for 100% Admin Manageability

**Project:** Eat Is Family
**Date:** 2026-01-27
**Objective:** Ensure all site content, markers, logos, and structure are manageable via WordPress Admin without touching code.

---

## Executive Summary

The WordPress theme has made significant progress toward full admin manageability. The following audit identifies:

✅ **What's Already Implemented** (85% complete)
❌ **What Still Needs Work** (15% remaining)
📋 **Refactoring Roadmap** (detailed action plan)

---

## 1. Data Source Mapping Audit

### ✅ Already Implemented

#### Custom Post Types (CPTs)
All major content types are registered and managed via WordPress:

| CPT | Status | Admin Location | REST API Endpoint |
|-----|--------|---------------|-------------------|
| `activity` | ✅ Active | Activities menu | `/eatisfamily/v1/activities` |
| `event` | ✅ Active | Events menu | `/eatisfamily/v1/events` |
| `job` | ✅ Active | Jobs menu | `/eatisfamily/v1/jobs` |
| `venue` | ✅ Active | Venues menu | `/eatisfamily/v1/venues` |
| `timeline_event` | ✅ Active | Timeline menu | Standard WP REST API |
| `post` (blog) | ✅ Active | Posts menu | `/eatisfamily/v1/blog-posts` |

#### WordPress Options (Site-wide Content)
Structured data managed via custom admin pages:

| Option | Admin Location | Contains |
|--------|---------------|----------|
| `eatisfamily_site_content` | Site Content page | Global site configuration, SEO, social, contact |
| `eatisfamily_pages_content` | Pages Content page | All page-specific content (hero sections, CTAs) |
| `eatisfamily_partners` | Partners page | Partner logos and company names |
| `eatisfamily_services` | Services page | Service cards with icons, thumbnails, descriptions |
| `eatisfamily_sustainability` | Sustainability page | Sustainability items with backgrounds and icons |
| `eatisfamily_gallery` | Gallery page | Gallery image URLs |
| `eatisfamily_venues_metadata` | Auto from Customizer | Map title, description, filter label |
| `eatisfamily_event_types` | Managed with venues | Event type filters for map |
| `eatisfamily_stats` | Managed with venues | Statistics for map section |

#### WordPress Customizer (Global Settings)
Complete theme customization via Appearance > Customize:

| Section | Fields | Purpose |
|---------|--------|---------|
| **Brand Identity** | Site Logo, Logo Dark, Favicon, Brand Name, Brand Highlight | Logo uploads and brand naming |
| **Header Configuration** | Background Image, Sticky Header Toggle, CTA Text (Desktop), CTA Text (Mobile), CTA Link | Header appearance and CTA button |
| **Navigation Menu** | 6 Nav Links (text + URL pairs) | Main navigation structure |
| **Map Configuration** | MapTiler API Key, Style URL, Center (Lat/Lng), Zoom Level | Interactive map settings |
| **Venue Markers** | Marker Type, Stadium Marker, Arena Marker, Festival Marker, Default Marker | Map marker images by venue type |
| **SEO & Meta** | Default Title, Title Template, Description, Keywords, OG Image, Twitter Handle, Canonical Base | Site-wide SEO |
| **Social Media** | Facebook, Instagram, Twitter, LinkedIn, YouTube, TikTok URLs | Social links |
| **Contact Information** | Email, Phone, Address, Business Hours, Legal Email | Contact details |
| **Footer Configuration** | Footer Logo, Tagline, Copyright Text, Back to Top Toggle, Section Titles | Footer content |
| **Global Configuration** | Jobs Per Page, Events Per Page, Blog Posts Per Page, Site Language | Pagination and locale |
| **UI Text Strings** | 20+ UI labels | All button text, loading messages, error messages (i18n ready) |
| **Background Images** | 11 background images | Hero backgrounds for all pages |

#### REST API Endpoints
All endpoints active and functional:

```
/wp-json/eatisfamily/v1/
├── activities (GET list, GET by slug)
├── blog-posts (GET list, GET by slug)
├── events (GET list, GET by ID)
├── jobs (GET list, GET by slug)
├── venues (GET list, GET by ID) - includes metadata, event_types, stats
├── site-content (GET global config)
├── pages-content (GET all page content) - includes partners, services, gallery, sustainability
└── settings (GET global settings) - Customizer + site/pages content combined
```

### ❌ What Still Needs Work

#### Static JSON Files
These files should be migrated to WordPress options:

| File | Purpose | Recommended Solution |
|------|---------|---------------------|
| `public/api/geo/europe.json` | Geographic coordinates for Europe map | Create `eatisfamily_geo_europe` option, add admin UI |
| `public/api/geo/france-regions.json` | French regions data | Create `eatisfamily_geo_france` option, add admin UI |

**Note:** `public/api/activities.json` is a fallback for the Activities CPT (already managed via WordPress).

---

## 2. Media Management Audit

### ✅ Already Implemented

**WordPress Media Library Integration:**
- All CPT featured images use `get_the_post_thumbnail_url()`
- Customizer fields use `WP_Customize_Media_Control` for image uploads
- Admin pages use WordPress media uploader for:
  - Partner logos
  - Service thumbnails
  - Service icons
  - Sustainability backgrounds/icons
  - Gallery images
  - Hero backgrounds

**Media Fields in Customizer:**
- Site Logo
- Site Logo Dark
- Favicon
- Header Background Image
- Stadium/Arena/Festival Markers (custom icons)
- SEO OG Image
- Background Images (11 page-specific backgrounds)

### ❌ What Still Needs Work

**Hardcoded Image Paths in Vue Components:**
The following hardcoded `/images/` paths need to be replaced with dynamic URLs from WordPress settings:

#### Job Pages (`jobs/[slug].vue`, `careers.vue`)
```
/images/streamline-emojis_briefcase.png
/images/streamline-emojis_moneybag.svg
/images/ApplyForThisOh.svg
/images/ShareThisJobOh.svg
/images/btnApplyForPosition.svg
/images/btnGoBackToJobs.svg
/images/chevronDown.svg (2 instances)
/images/btnApplu.svg
/images/btnVieu.svg
/images/btnDiscoverAndApply.svg
```

#### Homepage (`index.vue`)
```
/images/btnExplore.svg
/images/btnLearnMoreAboutUs.svg
```

#### Forms (`JobSearchForm.vue`)
```
/images/btnSearchForm.svg
```

#### Explore Section (`ExploreSection.vue`)
```
/images/mapIcon.svg
/images/iconCal.svg
/images/iconInfo.svg
/images/iconShop.svg
/images/iconFood.svg
/images/joinNowBtn.svg
```

#### Header (`Header.vue`)
```
/images/btnGetInTouch.svg
```

#### Blog (`blog/index.vue`)
```
/images/btnReadMore.svg (3 instances)
```

#### Default OG Image (`layouts/default.vue`)
```
Line 36: '/images/og-default.jpg' - should come from Customizer SEO settings
```

**Total hardcoded images:** ~25 unique icon/button files

---

## 3. Dynamic Template Engine Audit

### ✅ Already Implemented

**Vue Components Using Dynamic Data:**

| Component | Data Source | Status |
|-----------|------------|--------|
| `TheNavbar.vue` | `useGlobalSettings()` | ✅ Fully dynamic (logo, brand name, nav links, CTA) |
| `TheFooter.vue` | `useGlobalSettings()` | ✅ Fully dynamic (all content from settings) |
| `VenueMap.vue` | `useGlobalSettings()` | ✅ Fully dynamic (map config, marker icons) |
| `default.vue` (layout) | `useGlobalSettings()` | ✅ SEO meta tags from settings |
| `jobs/[slug].vue` | `useJobs()` | ✅ Job data from WordPress CPT |
| `careers.vue` | `useJobs()`, `useVenues()` | ✅ Jobs and venues from WordPress |
| `index.vue` | `usePageContent()` | ✅ Homepage content from pages-content API |
| `ExploreSection.vue` | `useVenues()` | ✅ Venue data from WordPress |

**Data Flow Architecture:**
```
WordPress Admin
     ↓
WordPress Options / CPT Meta
     ↓
REST API Endpoints (/eatisfamily/v1/*)
     ↓
Vue Composables (useGlobalSettings, usePageContent, useJobs, etc.)
     ↓
Vue Components (TheNavbar, TheFooter, VenueMap, etc.)
```

### ❌ What Still Needs Work

**Hardcoded Icon URLs:**
- While component text, links, and settings are dynamic, **icon/button image URLs** are still hardcoded as `/images/*.svg`
- These need to be added to WordPress settings (either Customizer or admin page)
- Vue components should retrieve icon URLs from `useGlobalSettings()`

---

## 4. Code Quality & Integration

### ✅ Already Implemented

**WordPress Backend:**
- ✅ Clean CPT registration with proper labels and REST API support
- ✅ Customizer organized into logical sections (12 sections)
- ✅ Admin pages with WYSIWYG editors for complex content
- ✅ Media uploader integration for all image fields
- ✅ Helper functions for array parsing (`eatisfamily_parse_array_field`)
- ✅ Proper sanitization and nonce verification
- ✅ Format functions for consistent API responses
- ✅ CORS headers for cross-origin API requests

**Vue Frontend:**
- ✅ Composable-first architecture (useApi, useGlobalSettings, usePageContent, etc.)
- ✅ TypeScript interfaces for all data structures
- ✅ Deep merge logic for WordPress + local data fallback
- ✅ Caching via Nuxt's `useState`
- ✅ Error handling and loading states
- ✅ SEO meta tags from WordPress settings

**Data Consistency:**
- ✅ WordPress REST API returns properly structured JSON
- ✅ Vue composables map WordPress data to expected frontend structure
- ✅ Fallback to local JSON if WordPress API unavailable

### ❌ What Still Needs Work

**Missing Admin UI:**
1. No Customizer section for **UI Icons & Buttons** (25+ icon files)
2. No admin page for **Geo Data** (Europe/France regions JSON)

**Vue Components:**
- Need to replace hardcoded `/images/` paths with `settings.value?.icons?.icon_name` pattern

---

## 5. Refactoring Roadmap

### Priority 1: Add UI Icons & Buttons to Customizer

**File to Modify:** `wordpress-theme/inc/customizer.php`

**New Section:** `eatisfamily_icons`

**Fields to Add:**
```php
// Job Icons
'icon_briefcase' => Media upload field
'icon_moneybag' => Media upload field
'icon_apply_for_this' => Media upload field
'icon_share_job' => Media upload field

// CTA Buttons
'btn_apply_position' => Media upload field
'btn_go_back_jobs' => Media upload field
'btn_explore' => Media upload field
'btn_learn_more' => Media upload field
'btn_get_in_touch' => Media upload field
'btn_read_more' => Media upload field
'btn_search_form' => Media upload field
'btn_discover_apply' => Media upload field
'btn_join_now' => Media upload field

// UI Icons
'icon_chevron_down' => Media upload field
'icon_map' => Media upload field
'icon_calendar' => Media upload field
'icon_info' => Media upload field
'icon_shop' => Media upload field
'icon_food' => Media upload field
```

### Priority 2: Add Geo Data to WordPress Options

**New Admin Page:** "Geo Data Management"

**Location:** Site Content > Geo Data

**Fields:**
- Europe GeoJSON (textarea with JSON validation)
- France Regions GeoJSON (textarea with JSON validation)

**Storage:**
- `eatisfamily_geo_europe` option
- `eatisfamily_geo_france` option

**REST API:**
- Add endpoint: `/eatisfamily/v1/geo-data`

### Priority 3: Update REST API

**File to Modify:** `wordpress-theme/functions.php` (lines 1424-1481)

**Update `eatisfamily_get_global_settings()` function:**
```php
// Add icons section
'icons' => $customizer_settings['icons'],

// Add geo data
'geo' => array(
    'europe' => get_option('eatisfamily_geo_europe', array()),
    'france' => get_option('eatisfamily_geo_france', array())
)
```

### Priority 4: Update Vue Components

**Pattern to Follow:**
```vue
<script setup>
const { settings, loadSettings } = useGlobalSettings()
const iconBriefcase = computed(() => settings.value?.icons?.icon_briefcase || '/images/streamline-emojis_briefcase.png')
</script>

<template>
  <nuxt-img :src="iconBriefcase" alt="briefcase icon" />
</template>
```

**Files to Update:**
1. `app/pages/jobs/[slug].vue` (8 icons)
2. `app/pages/careers.vue` (7 icons)
3. `app/pages/index.vue` (2 buttons)
4. `app/components/forms/JobSearchForm.vue` (1 icon)
5. `app/components/home/ExploreSection.vue` (6 icons)
6. `app/components/layout/Header.vue` (1 button)
7. `app/pages/blog/index.vue` (1 button)
8. `app/layouts/default.vue` (1 OG image - already exists in settings.seo.og_image)

### Priority 5: Testing Checklist

- [ ] Upload all 25 icons to WordPress Media Library
- [ ] Configure icons in Customizer
- [ ] Test REST API `/settings` endpoint returns `icons` object
- [ ] Verify Vue components render dynamic icon URLs
- [ ] Test fallback behavior (icons should still work if setting is empty)
- [ ] Test geo data admin page (save/load GeoJSON)
- [ ] Verify map components use dynamic geo data
- [ ] Clear Vue cache and test fresh load
- [ ] Test all pages for broken images
- [ ] Verify SEO OG image uses Customizer setting (not hardcoded `/images/og-default.jpg`)

---

## 6. File Structure Summary

### WordPress Theme Files

```
wordpress-theme/
├── functions.php (1505 lines)
│   ├── CPT registration (lines 87-175)
│   ├── JSON import functions (lines 235-677)
│   ├── REST API routes (lines 863-982)
│   ├── API callback functions (lines 987-1363)
│   └── Global settings endpoint (lines 1424-1481)
│
├── inc/
│   ├── customizer.php (979 lines) - 12 sections, 100+ fields
│   ├── admin-pages.php - Site Content, Pages Content editors
│   ├── admin-pages-extended.php - Partners, Services, Sustainability, Gallery
│   ├── meta-boxes.php - WYSIWYG editors for CPT fields
│   └── admin.php - Additional admin functionality
│
└── data/
    └── *.json - Initial import data (optional, not required after setup)
```

### Vue Frontend Files

```
app/
├── composables/
│   ├── useApi.ts - Base fetch wrapper with caching
│   ├── useGlobalSettings.ts - Global settings (Customizer + site content)
│   ├── usePageContent.ts - Page-specific content (deep merge logic)
│   ├── useJobs.ts - Job listings and details
│   ├── useVenues.ts - Venues, map metadata, event types
│   ├── useEvents.ts - Events
│   ├── useActivities.ts - Activities
│   └── useBlog.ts - Blog posts
│
├── components/
│   ├── TheNavbar.vue - ✅ Fully dynamic
│   ├── TheFooter.vue - ✅ Fully dynamic
│   ├── VenueMap.vue - ✅ Fully dynamic (map config), ❌ Needs icon updates
│   ├── home/ExploreSection.vue - ✅ Dynamic content, ❌ Hardcoded icons
│   ├── forms/JobSearchForm.vue - ❌ Hardcoded search icon
│   └── layout/Header.vue - ❌ Hardcoded CTA button image
│
├── pages/
│   ├── index.vue - ✅ Dynamic content, ❌ Hardcoded buttons
│   ├── careers.vue - ✅ Dynamic content, ❌ Hardcoded icons
│   ├── jobs/[slug].vue - ✅ Dynamic content, ❌ Hardcoded icons
│   └── blog/index.vue - ✅ Dynamic content, ❌ Hardcoded button
│
└── layouts/
    └── default.vue - ✅ Dynamic SEO, ❌ OG image has hardcoded fallback
```

---

## 7. Admin User Experience

### Current Experience (After Full Implementation)

1. **Adding a New Job:**
   - Go to Jobs > Add New
   - Fill in title, description, requirements (WYSIWYG editor)
   - Select venue from dropdown (dynamic from Venues CPT)
   - Upload featured image via Media Library
   - Publish → Immediately visible on frontend

2. **Changing Site Logo:**
   - Go to Appearance > Customize > Brand Identity
   - Click "Site Logo" → Upload or select from Media Library
   - Click "Publish" → Logo updates across all pages instantly

3. **Managing Map Markers:**
   - Go to Appearance > Customize > Venue Markers
   - Upload custom marker images for Stadium/Arena/Festival
   - Set default marker for other venue types
   - Publish → Map updates with new marker icons

4. **Editing Homepage Hero:**
   - Go to Site Content > Pages Content > Homepage
   - Edit Hero Section (title, background image, CTA text)
   - Save Changes → Homepage hero updates

5. **Adding a Partner Logo:**
   - Go to Site Content > Partners
   - Click "Add Partner"
   - Upload logo via Media Library, enter company name
   - Save → Partner appears in homepage partners section

### After Implementing Recommendations

**Additional Capability:**

6. **Changing Button Icons Site-wide:**
   - Go to Appearance > Customize > UI Icons & Buttons
   - Upload new "Apply Now" button design
   - Publish → All job pages show new button design

7. **Managing Geo Data:**
   - Go to Site Content > Geo Data
   - Paste updated Europe GeoJSON
   - Save → Maps update with new boundaries

---

## 8. Recommendations

### Immediate Actions (Critical)

1. **Create UI Icons & Buttons Customizer Section**
   - Add all 25 hardcoded icons/buttons as Media upload fields
   - Group logically: Job Icons, CTA Buttons, UI Icons
   - Update `eatisfamily_get_global_settings()` to include icons

2. **Update Vue Components for Dynamic Icons**
   - Replace all `/images/*.svg` paths with `settings.value?.icons?.*`
   - Provide fallbacks to original paths for backward compatibility

3. **Fix OG Image Fallback**
   - In `layouts/default.vue` line 36, use `settings.value?.seo?.og_image` without hardcoded fallback

### Nice-to-Have (Phase 2)

1. **Geo Data Admin UI**
   - Create admin page for Europe/France GeoJSON management
   - Add JSON validation
   - Provide visual preview of geo boundaries

2. **Icon Preview in Customizer**
   - Add live preview of icons in Customizer
   - Show where each icon appears on the frontend

3. **Bulk Image Manager**
   - Create admin page showing all icons/buttons in use
   - Allow bulk upload/replacement
   - Visual grid layout for easier management

4. **Automated Testing**
   - Add automated tests for REST API endpoints
   - Test that all settings are exposed correctly

---

## 9. Summary

### Current State: 85% Admin-Manageable ✅

**Fully Managed via WordPress Admin:**
- All content (Jobs, Events, Venues, Activities, Blog Posts)
- Site-wide settings (Logo, Brand, Navigation, Footer, SEO, Social, Contact)
- Map configuration (MapTiler, zoom, center, venue markers)
- Page content (Hero sections, CTAs, Services, Partners, Gallery)
- UI text strings (all button labels, error messages)
- Background images (hero backgrounds for all pages)

**Still Hardcoded (15% Remaining):**
- 25 icon/button image URLs (in Vue components)
- 2 geo JSON files (Europe/France regions)

### After Refactoring: 100% Admin-Manageable 🎯

**Zero Code Changes Required:**
- Non-technical users can update ALL content via WordPress Admin
- All images managed through Media Library
- All text editable via WYSIWYG editors or text fields
- All navigation, logos, and branding fully customizable
- Complete control over map markers, icons, and buttons
- Full SEO control (meta tags, titles, descriptions, OG images)

### Estimated Effort

| Task | Time Estimate | Priority |
|------|--------------|----------|
| Add UI Icons section to Customizer | 2 hours | P0 (Critical) |
| Update REST API for icons | 30 minutes | P0 (Critical) |
| Update Vue components (8 files) | 3 hours | P0 (Critical) |
| Testing & QA | 2 hours | P0 (Critical) |
| **Total Critical Path** | **7.5 hours** | **P0** |
| Geo Data admin UI | 3 hours | P1 (Nice-to-have) |
| Icon preview in Customizer | 2 hours | P2 (Enhancement) |

---

## 10. Next Steps

1. ✅ **This audit is complete**
2. **Awaiting approval to proceed with refactoring**
3. Recommended sequence:
   - Phase 1: Customizer icons section + REST API update (2.5 hours)
   - Phase 2: Update Vue components (3 hours)
   - Phase 3: Testing & QA (2 hours)
   - Phase 4: Geo Data UI (optional, 3 hours)

**Ready to begin implementation upon approval.**

---

*End of Audit Report*
