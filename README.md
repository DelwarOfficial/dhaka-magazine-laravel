# Dhaka Magazine Laravel Blade News Frontend

Dhaka Magazine is a Laravel Blade news frontend prepared for future CMS/database integration. The current UI is intentionally controlled by Blade templates and reusable components, while content comes from CMS-ready Eloquent models, section services, seed/demo posts, or emergency fallback data.

## Project Goals

- Keep the existing news UI, layout, and responsive behavior stable.
- Let a future CMS/database control posts, categories, authors, tags, media, flags, and placements.
- Keep Blade focused on presentation.
- Keep services and repositories responsible for content preparation.
- Keep fallback content only for local/demo/empty-database preview.

## Main Runtime Flow

```text
Route
  -> Controller
  -> Service / Repository / ArticleFeed
  -> Eloquent models with eager loading
  -> Normalized article arrays
  -> Blade pages and components
```

Blade components should not query the database. They receive normalized arrays such as `title`, `slug`, `image_url`, `category`, `category_url`, `time_ago`, `author`, `views`, and `tags`.

## Important Files

| Area | Files |
| --- | --- |
| Routes | `routes/web.php` |
| Homepage controller | `app/Http/Controllers/HomeController.php` |
| Homepage data | `app/Services/HomeDataService.php` |
| Homepage repository | `app/Services/HomepageContentRepository.php` |
| Article feed adapter | `app/Support/ArticleFeed.php` |
| Fallback data | `app/Support/FallbackDataService.php` |
| Popular widget data | `app/Services/PopularNewsService.php` |
| Related article data | `app/Services/RelatedArticleService.php` |
| Ticker data | `app/Services/TickerHeadlineService.php` |
| Homepage config | `config/homepage.php` |
| Category config | `config/categories.php` |
| Main layout | `resources/views/layouts/app.blade.php` |
| Homepage Blade | `resources/views/pages/home.blade.php` |
| Components | `resources/views/components/` |
| JS assets | `resources/js/` |

## Local Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
```

Production build:

```bash
npm run build
php artisan optimize
```

## Environment Flags

```env
HOMEPAGE_CACHE_ENABLED=true
HOMEPAGE_CACHE_TTL=300
ENABLE_FALLBACK_CONTENT=false
```

`ENABLE_FALLBACK_CONTENT=false` is the production-safe default. Set it to `true` only for local preview, staging demos, or an empty database preview.

## Documentation

- [Architecture](ARCHITECTURE.md)
- [Frontend UI Guide](FRONTEND_UI_GUIDE.md)
- [CMS Integration Guide](CMS_INTEGRATION_GUIDE.md)
- [Content Flow](CONTENT_FLOW.md)
- [Fallback and Seeder Guide](FALLBACK_AND_SEEDER_GUIDE.md)
- [Performance Notes](PERFORMANCE_NOTES.md)
- [Feature Map](FEATURE_MAP.md)

## Maintenance Rules

- Do not query Eloquent relationships inside Blade.
- Do not add new homepage sections directly inside controllers.
- Prefer service/repository methods for content preparation.
- Keep UI layout control in Blade/config, not in database migrations.
- Keep fallback/demo data separate from production publishing logic.
- When adding new CMS content fields, normalize them in `ArticleFeed` before they reach views.

## License

Private project. All rights reserved.
