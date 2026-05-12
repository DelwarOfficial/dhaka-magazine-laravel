# Frontend UI Guide

This guide explains the Blade UI without changing the design.

## Layout

Main layout:

- `resources/views/layouts/app.blade.php`

It provides:

- SEO meta tags
- Open Graph/Twitter tags
- theme boot script
- Vite CSS/JS
- ticker
- header
- footer
- page content slot

## Header and Navigation

Files:

- `resources/views/partials/header.blade.php`
- `app/Support/CategoryRepository.php`
- `config/categories.php`

Navigation uses `siteCategories` from the view composer. Categories are config/database-compatible arrays with `slug`, `name_bn`, `parent_slug`, and `children`.

## Homepage Page

File:

- `resources/views/pages/home.blade.php`

The homepage only arranges components. Content is prepared by `HomeDataService`.

## Major Homepage Components

| Feature | Component |
| --- | --- |
| Hero/editorial area | `components/home/hero-section.blade.php` |
| Photo/media block | `components/photo-news-block.blade.php` |
| Bangladesh grid | `components/home/category-grid-section.blade.php` |
| Local news | `components/home/local-news-section.blade.php` |
| Feature/list sections | `components/home/feature-list-section.blade.php` |
| Sports | `components/sports-block.blade.php` |
| Opinion | `components/home/opinion-section.blade.php` |
| Video | `components/video-block.blade.php` |
| Entertainment | `components/home/entertainment-section.blade.php` |
| Compact columns | `components/home/compact-category-columns.blade.php` |
| Special strip | `components/home/special-strip-section.blade.php` |
| Bottom columns | `components/home/bottom-category-columns.blade.php` |

## Article Page

File:

- `resources/views/pages/article.blade.php`

Data source:

- `ArticleController`
- `ArticleFeed::findArticle()`
- `RelatedArticleService`
- `PopularNewsService`

The page renders body paragraphs, related articles, tags, share actions, and sidebar widgets.

## Category Page

File:

- `resources/views/pages/category.blade.php`

Data source:

- `CategoryController`
- `ArticleFeed::categoryArticles()`

The page supports local-news filters for `country-news`.

## Latest Page

File:

- `resources/views/news/latest.blade.php`

Data source:

- `NewsController`
- `Post::withContentRelations()`
- `PopularNewsService`

## JavaScript

Main file:

- `resources/js/app.js`

Feature files:

- `resources/js/components/photo-news.js`
- `resources/js/components/prayer-countdown.js`

JavaScript owns interaction only. It should not own content rules.

## Accessibility and SEO Notes

- Use meaningful `alt` text from article titles or media metadata.
- Keep article URLs generated through named routes.
- Keep headings semantic per page.
- Do not hide important content behind JavaScript-only rendering.
