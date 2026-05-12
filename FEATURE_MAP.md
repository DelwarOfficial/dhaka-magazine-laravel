# Feature Map

| Feature | What it does | Main files | Data source | Fallback |
| --- | --- | --- | --- | --- |
| Header/navigation | Renders desktop/mobile categories | `partials/header.blade.php`, `CategoryRepository` | category config/database-compatible arrays | category config |
| Breaking ticker | Scrolls breaking headlines | `scroll-nav.blade.php`, `TickerHeadlineService` | breaking flags/placements via `ArticleFeed` | fallback breaking articles when enabled |
| Hero section | Main homepage editorial area | `home/hero-section.blade.php`, `HomeDataService` | `content_placements`, section flags | legacy feed fallback |
| Bangladesh | Category grid | `home/category-grid-section.blade.php` | configured category feed | category fallback articles |
| Saradesh/local | Location-aware news | `home/local-news-section.blade.php`, `ArticleFeed::localNews()` | posts with location IDs | legacy local pool when enabled |
| International | Feature/list section | `home/feature-list-section.blade.php` | `world` category feed | fallback category articles |
| Sports | Sports layout and subcategory cards | `sports-block.blade.php` | sports/category relationship feeds | fallback category articles |
| Opinion | Opinion grid | `home/opinion-section.blade.php` | opinion category feed | fallback category articles |
| Video | Video-styled news block | `video-block.blade.php` | videos category feed | fallback video articles |
| Entertainment | Entertainment layout | `home/entertainment-section.blade.php` | entertainment category feed | fallback category articles |
| Photo/media | Carousel and latest/popular tabs | `photo-news-block.blade.php`, `photo-news.js` | latest and popular article arrays | public image fallback |
| Most-read | Popular sidebar/widget | `PopularNewsService`, `widgets/most-read.blade.php` | `view_count` ordered posts | latest posts when no views |
| Article page | Full article rendering | `ArticleController`, `pages/article.blade.php` | post by slug | fallback article when enabled |
| Related articles | More from same category | `RelatedArticleService` | category feed | latest feed filler |
| Category page | Category archive | `CategoryController`, `pages/category.blade.php` | category feed | fallback category articles |
| Latest page | Paginated latest news | `NewsController`, `news/latest.blade.php` | latest published posts | fallback paginator |
| Ads | Placeholder ad slots | `components/ads/ad-slot.blade.php`, `config/ads.php` | config/static slot | placeholder |
| Theme/mobile JS | UI interaction | `resources/js/app.js` | DOM state/localStorage | none |
