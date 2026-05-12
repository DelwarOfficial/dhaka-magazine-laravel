# Architecture

This project is a Laravel Blade news frontend designed to be connected to a ready-made CMS later. The CMS should control content, not the visual layout.

## Layers

| Layer | Responsibility |
| --- | --- |
| Routes | Public URL definitions only. |
| Controllers | Choose page type and pass prepared data to views. |
| Services | Compose page payloads and reusable widgets. |
| Repositories/adapters | Fetch content from database, placements, category feeds, or fallback data. |
| Models | Define relationships, scopes, and CMS data shape. |
| Blade pages | Page structure only. |
| Blade components | Reusable UI rendering only. |
| Config | Current section/category control contract. |

## Content Priority

1. `content_placements` for editorial slots where available.
2. Real CMS/database posts.
3. Seeder demo posts.
4. `FallbackDataService` only when enabled and no database content exists.

## Current CMS-Ready Data Model

Important models:

- `Post`
- `Category`
- `Tag`
- `Media`
- `ContentPlacement`
- `District`, `Division`, `Upazila`

`Post::withContentRelations()` is the standard eager-loading graph for frontend cards. Use it for any query that will be normalized into article arrays.

## Homepage Architecture

`HomeController@index` delegates to `HomeDataService`. The service builds a stable payload for `resources/views/pages/home.blade.php`.

Homepage content comes from:

- `HomepageContentRepository::placement()` for hero/editorial slots.
- `HomepageContentRepository::category()` for basic category feeds.
- `HomepageContentRepository::relationshipCategory()` for normalized category relationships.
- `ArticleFeed::localNews()` for location-based local news.
- `PopularNewsService` for popular widgets.

The current layout order is fixed in Blade to preserve UI stability. Future CMS section builders can replace `config/homepage.php` with a database table while keeping the same payload shape.

## Blade Rules

- Blade must not call Eloquent queries.
- Blade may format arrays, split collections, and render components.
- Components should accept arrays or simple scalar props.
- Shared cards live in `resources/views/components/cards` and `resources/views/components/news`.

## View Composer

`AppServiceProvider` shares layout data:

- `tickerHeadlines` from `TickerHeadlineService`
- `siteCategories` from `CategoryRepository`

Both are cached.

## Cache Invalidation

The app clears homepage/ticker/popular/category cache entries when posts, placements, or categories are saved/deleted. For larger production sites, move this to model observers and tagged cache.
