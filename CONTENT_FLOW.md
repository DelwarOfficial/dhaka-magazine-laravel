# Content Flow

## Normalized Article Array

Most views receive articles as arrays:

```php
[
    'id' => 1,
    'slug' => 'post-slug',
    'title' => 'Post title',
    'category' => 'Category name',
    'category_slug' => 'category-slug',
    'category_url' => '/category/category-slug',
    'excerpt' => 'Short excerpt',
    'author' => 'Author name',
    'date' => 'Formatted date',
    'time_ago' => 'Relative time',
    'image_url' => '/storage/image.jpg',
    'views' => 100,
    'tags' => [],
]
```

The normalization boundary is `ArticleFeed::postToArticleArray()`.

## Homepage Flow

```text
HomeController
  -> HomeDataService
  -> HomepageContentRepository
  -> ArticleFeed / ContentPlacement
  -> home.blade.php
  -> home components
```

## Category Flow

```text
CategoryController
  -> CategoryRepository
  -> ArticleFeed::categoryArticles()
  -> category.blade.php
```

## Article Flow

```text
ArticleController
  -> ArticleFeed::findArticle()
  -> RelatedArticleService
  -> PopularNewsService
  -> article.blade.php
```

## Ticker Flow

```text
AppServiceProvider view composer
  -> TickerHeadlineService
  -> ArticleFeed::breakingNews()
  -> components/dhaka-magazine-scroll/scroll-nav.blade.php
```

## Popular Flow

```text
Controller or HomeDataService
  -> PopularNewsService
  -> view_count ordered posts
  -> most-read components
```

## Fallback Flow

Fallback content is only used when enabled and database content is unavailable. Production should use:

```env
ENABLE_FALLBACK_CONTENT=false
```
