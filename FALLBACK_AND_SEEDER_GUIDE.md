# Fallback and Seeder Guide

The project has two different demo systems. They should not be confused.

## Seeder Demo Content

Seeders create database records and are the preferred way to preview the frontend with realistic CMS-like data.

Important files:

- `database/seeders/CategorySeeder.php`
- `database/seeders/DistrictsSeeder.php`
- `database/seeders/DatabaseSeeder.php`
- `app/Console/Commands/FillDemoCategoryPosts.php`

Recommended local setup:

```bash
php artisan migrate:fresh --seed
php artisan demo:fill-category-posts --min=10
```

Seeders should be safe to run repeatedly. Demo post commands generate unique slugs and should avoid duplicate content.

## FallbackDataService

File:

- `app/Support/FallbackDataService.php`

Purpose:

- local preview
- staging/demo preview
- emergency empty-database rendering

It is not a production content source.

Production default:

```env
ENABLE_FALLBACK_CONTENT=false
```

Local demo option:

```env
ENABLE_FALLBACK_CONTENT=true
```

## Content Priority

1. CMS/editorial placements
2. real database posts
3. seeded demo posts
4. fallback arrays

## Safe Rules

- Do not add permanent editorial content to `FallbackDataService`.
- Do not rely on fallback data for production screenshots or QA.
- Keep fallback article fields compatible with normalized article arrays.
- If a section is empty in production, fix CMS data instead of enabling fallback.
