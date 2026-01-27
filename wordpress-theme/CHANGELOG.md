# Changelog - Eat Is Family WordPress Theme

All notable changes to this project will be documented in this file.

## [3.0.0] - 2026-01-27

### 🎉 Major Release - Complete Dynamic Backend

#### Added

- ✅ **Hero Section Video Support**
  - YouTube video background support with autoplay, mute, loop
  - MP4 video background support with native HTML5 video
  - Toggle between Image / YouTube / MP4 in WordPress admin
  - Responsive video styles for all screen sizes

- ✅ **Forms Labels Management** (New Tab in Pages Content)
  - Job Search Form labels (title, subtitle, placeholders)
  - Contact Form labels (all fields, buttons, messages)
  - Job Application Form labels (all fields, buttons, messages)
  - All labels editable from WordPress admin

- ✅ **Corrected Pages Content Structure**
  - Fixed `hero_section.title.line_1/line_2/line_3` structure
  - Now matches exact JSON structure expected by Vue frontend
  - WYSIWYG editors for rich text fields

- ✅ **New TypeScript Interfaces**
  - `FormsContent.job_search` for job search form labels
  - `FormsContent.job_application` for job application form labels
  - Updated `HomepageContent.hero_section` with video fields

#### Changed

- 🔄 **JobSearchForm.vue** now uses dynamic labels from WordPress
- 🔄 **ContactForm.vue** already uses dynamic labels (verified)
- 🔄 **index.vue** hero section supports video backgrounds
- 🔄 **admin-pages.php** completely rewritten for correct structure

#### Fixed

- 🐛 **Critical Bug**: Admin page fields now map correctly to JSON structure
- 🐛 **Fixed**: Editing hero subtitle now actually updates frontend
- 🐛 **Fixed**: All homepage sections use correct nested structure

---

## [2.0.0] - 2026-01-27

### 🎉 Major Release - Headless CMS Complete

#### Added
- ✅ **Complete Meta Boxes System** (`/inc/meta-boxes.php`)
  - WYSIWYG editors for all text content
  - Dynamic dropdowns for relationships (Venues, Departments, Categories)
  - Repeater fields for lists (Requirements, Benefits, Services)
  - Media upload integration with WordPress media library
  - Gallery fields for multiple images
  - Complex repeaters for structured data (Shops, Menu Items)

- ✅ **Admin Pages for Content Management** (`/inc/admin-pages.php`)
  - Site Content editor (SEO, Contact, Social Media)
  - Pages Content editor (Hero sections, CTAs for all pages)
  - Data Management page (Manual JSON import tools)

- ✅ **New Custom Post Type: Timeline Events**
  - For About page company history timeline
  - Fields: event date, description, display order, featured image

- ✅ **Helper Functions**
  - `eatisfamily_parse_array_field()` - Parses arrays from multiple formats (JSON, newline-separated, comma-separated)

#### Changed
- ⚠️ **DISABLED automatic JSON import on theme activation**
  - WordPress database is now the single source of truth
  - JSON files only used for initial manual import via admin page
  - Prevents overwriting admin changes

- 🔄 **Updated all format functions** to use `eatisfamily_parse_array_field()`
  - `eatisfamily_format_job()` - Requirements and Benefits
  - `eatisfamily_format_venue()` - Amenities and Services

- 📝 **Updated theme metadata** in `style.css`
  - Version: 2.0.0
  - Description updated to reflect headless CMS purpose
  - Updated tags

#### Improved
- 🎨 **Enhanced Admin UI**
  - Organized meta boxes with sections and groups
  - Better field labels and descriptions
  - Inline JavaScript for repeater functionality
  - CSS styling for better UX

- 📊 **Better Data Management**
  - Dashboard page showing content statistics
  - Manual import controls with file existence checks
  - Import confirmation dialogs

- 🔐 **Security**
  - All inputs properly sanitized
  - Nonce verification on all forms
  - Permission checks on admin pages

#### Removed
- ❌ Automatic JSON import hook
- ❌ Hardcoded JSON update on content save
- ❌ Direct JSON file dependencies in API responses

### Migration Notes from v1.0

If you're upgrading from v1.0:

1. **Backup your database** before upgrading
2. **Existing content will be preserved** - no data loss
3. **JSON files will no longer be auto-updated**
4. **Use WordPress admin exclusively** for content editing
5. **Update your Nuxt.js config** to point to WordPress API (already should be done)

### New Admin Menu Structure

```
WordPress Admin
├── Activities (Custom Post Type)
├── Events (Custom Post Type)
├── Jobs (Custom Post Type)
├── Venues (Custom Post Type)
├── Timeline (Custom Post Type - NEW)
├── Posts (Blog)
└── Site Content (NEW Menu)
    ├── Site Content (Global Settings)
    ├── Pages Content (Page-specific content)
    └── Data Management (Import tools)
```

### API Endpoints (Unchanged)

All API endpoints remain the same - no breaking changes:
- `GET /eatisfamily/v1/activities`
- `GET /eatisfamily/v1/activities/{slug}`
- `GET /eatisfamily/v1/blog-posts`
- `GET /eatisfamily/v1/blog-posts/{slug}`
- `GET /eatisfamily/v1/events`
- `GET /eatisfamily/v1/events/{id}`
- `GET /eatisfamily/v1/jobs`
- `GET /eatisfamily/v1/jobs/{slug}`
- `GET /eatisfamily/v1/venues`
- `GET /eatisfamily/v1/venues/{id}`
- `GET /eatisfamily/v1/site-content`
- `GET /eatisfamily/v1/pages-content`

## [1.0.0] - 2026-01-23

### Initial Release

#### Added
- Basic Custom Post Types (Activity, Event, Job, Venue)
- REST API endpoints for all content types
- JSON import script (`import-data.php`)
- Basic admin columns
- CORS support
- Simple meta boxes with JSON array fields

#### Features
- WordPress 6.0+ compatibility
- PHP 8.0+ support
- Automatic JSON import on theme activation
- Basic REST API documentation

---

## Upcoming Features

### v2.1.0 (Planned)
- [ ] Export functionality (WordPress to JSON)
- [ ] Bulk editing tools
- [ ] Advanced filtering in admin lists
- [ ] Content duplication feature
- [ ] SEO metadata per post type

### v2.2.0 (Planned)
- [ ] Multi-language support (WPML/Polylang)
- [ ] Advanced search in admin
- [ ] Content versioning
- [ ] Scheduled publishing improvements

### v3.0.0 (Future)
- [ ] GraphQL API support
- [ ] Real-time preview
- [ ] Advanced analytics dashboard
- [ ] Content templates

---

**Maintainer:** Eat Is Family Team  
**Contact:** hello@eatisfamily.fr  
**License:** GPL-2.0-or-later
