# CMS Integration Guide

This frontend is ready for a CMS that manages content while Blade keeps the layout.

## CMS Should Control

- posts
- categories and subcategories
- authors
- tags
- media/images
- publication status and dates
- view counts
- breaking news
- featured posts
- sticky posts
- trending posts
- editor picks
- content placements

## CMS Should Not Control

- Blade visual structure
- CSS classes
- responsive layout
- frontend component markup
- design identity

## Required Post Fields

Minimum useful fields:

- `title`
- `slug`
- `excerpt`
- `content` or `body`
- `status`
- `published_at`
- `author_id` or `source_name`
- `featured_media_id` or `featured_image`
- category relationship or legacy `category_slug`

## Recommended Relationships

- `Post belongsTo User as author`
- `Post belongsTo Category as primaryCategory`
- `Post belongsToMany Category as categories`
- `Post belongsToMany Tag as tags`
- `Post belongsTo Media as featuredMedia`
- `Post hasMany ContentPlacement`

## Placement Keys

Current homepage placement keys are configured in `config/homepage.php`:

- `home.breaking`
- `home.featured`
- `home.sticky`
- `home.trending`
- `home.editors_pick`

Additional placements can be added without changing Blade if they return the same article array structure.

## Category Feed Control

Current category feeds are configured in `config/homepage.php`. A future CMS can store the same shape in a table:

- section key
- source type
- category slugs
- limit
- active flag
- sort order
- cache TTL

## Integration Steps

1. Connect CMS post/category/media tables to the existing models or adapters.
2. Make sure `Post::withContentRelations()` loads the CMS relationships needed by cards.
3. Map CMS placement/editorial flags into `content_placements` or existing section flags.
4. Keep `ArticleFeed::postToArticleArray()` as the normalization boundary.
5. Disable fallback content in production.
6. Add cache invalidation when CMS content changes.

## Safe Extension Pattern

When adding a new frontend block:

1. Add a service/repository method.
2. Normalize posts to article arrays.
3. Create or reuse a Blade component.
4. Add config for source/limit.
5. Add cache where the query is reused.
