# Performance Notes

## N+1 Prevention

Frontend cards are rendered from normalized arrays. Database relationships must be eager-loaded before normalization.

Standard eager-load scope:

```php
Post::query()->withContentRelations()
```

This includes:

- author
- primary category and parent
- categories and parents
- legacy category/subcategory relations
- featured media
- tags

## Optimized Services

- `ArticleFeed` caches repeated latest-feed and location-ID lookups during one request.
- `HomepageContentRepository` reuses identical category feed queries during one homepage render.
- `PopularNewsService` caches most-read content.
- `TickerHeadlineService` caches ticker headlines.
- `HomeDataService` caches the homepage payload.

## Cache Invalidation

`AppServiceProvider` clears homepage, ticker, popular, and category navigation caches when posts, placements, or categories change.

For high traffic, move cache invalidation to observers and use tagged cache with Redis.

## Recommended Indexes

For production CMS traffic, add or verify indexes on:

- `posts.slug`
- `posts.status`
- `posts.published_at`
- `posts.view_count`
- `posts.category_id`
- `posts.subcategory_id`
- `posts.primary_category_id`
- `post_category.post_id`
- `post_category.category_id`
- `content_placements.placement_key`
- `content_placements.post_id`
- `content_placements.sort_order`

## TTFB Notes

Homepage TTFB depends on:

- number of section queries
- cache backend speed
- image storage URL resolution
- category relationship indexes
- content placement indexes

Enable homepage cache in production:

```env
HOMEPAGE_CACHE_ENABLED=true
HOMEPAGE_CACHE_TTL=300
```

## Blade Performance Rules

- Do not run queries from Blade.
- Do not pass Eloquent collections into repeated card components unless relationships are already loaded.
- Prefer prepared arrays from services.
- Keep expensive filtering in services, not templates.
