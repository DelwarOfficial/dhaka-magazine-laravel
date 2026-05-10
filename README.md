# Dhaka Magazine Frontend Demo

Dhaka Magazine is a frontend-only news portal demo prepared for GitHub, Vercel, and Netlify distribution. The demo version has no backend, no live database, no private CMS data, and no environment secrets.

The clean release package lives in:

```text
frontend-demo/
```

## Project Status

- Frontend-only demo application
- Static Vite project
- JSON-driven demo news content
- Reusable local demo images
- No Laravel runtime required for the demo package
- No production CMS content included
- No private uploads, secrets, or environment-specific data included

## Demo Features

- Responsive Dhaka Magazine homepage UI
- Hero carousel
- Sidebar widgets
- Advertisement slot layout
- All Posts section
- Dark mode support
- Mobile navigation behavior
- Demo content loaded from JSON instead of hardcoded post arrays
- Local image fallback handling for missing demo images

## Folder Structure

```text
frontend-demo/
|-- public/
|   |-- demo-data/
|   |   `-- posts.json
|   `-- demo-images/
|-- scripts/
|   `-- generate-demo-data.mjs
|-- src/
|   |-- main.js
|   `-- styles.css
|-- index.html
|-- package.json
|-- package-lock.json
|-- vite.config.js
`-- .gitignore
```

## Demo Data

Demo posts are stored in:

```text
frontend-demo/public/demo-data/posts.json
```

Reusable demo images are stored in:

```text
frontend-demo/public/demo-images/
```

Each post references an image by filename. The frontend resolves those images from `public/demo-images/`. If a referenced file is missing, the UI uses a local placeholder image.

## UI Component Control System

The frontend is controlled from `frontend-demo/src/main.js`. It loads `posts.json`, normalizes each post, sorts posts by newest `published_at`, then maps posts into homepage sections.

| UI section | Data rule | Limit |
| --- | --- | --- |
| Top ticker / latest bar | `is_breaking: true` | 10 posts |
| Main hero story | First post with `is_featured: true`; falls back to newest post | 1 post |
| Body News grid | `is_body_news: true`, excluding the hero post | 6 posts |
| Trending News | `is_trending: true`, excluding hero and Body News posts | 5 posts |
| Editor's Pick | `is_editor_pick: true`, excluding Trending News posts | 3 posts |
| Photo News | Newest posts after sorting | 10 posts |
| Photo latest tab | Newest posts after sorting | 8 posts |
| Photo popular tab | Highest `view_count` first | 10 posts |
| Local News / Saradesh | `category: "local-news"` | 9 posts |
| All Posts | Newest posts after sorting | 24 posts |
| Most Read sidebar | Highest `view_count` first | 5 posts |

Duplicate prevention is handled by the `uniquePosts()` helper for the hero area. It prevents the same post ID from appearing in the Hero, Body News, Trending News, and Editor's Pick groups at the same time.

## Post Data Contract

Each object in `posts.json` should keep this structure:

```json
{
  "id": 1,
  "slug": "demo-news-1",
  "title": "Demo news title",
  "excerpt": "Short summary text.",
  "content": "Full demo article content.",
  "author": "Dhaka Magazine Desk",
  "category": "local-news",
  "category_bn": "Saradesh",
  "image_path": "news-1.jpg",
  "view_count": 1200,
  "published_at": "2026-05-10T12:00:00.000Z",
  "is_breaking": true,
  "is_featured": false,
  "is_body_news": false,
  "is_trending": false,
  "is_editor_pick": false
}
```

The UI expects stable field names. To change which posts appear in a component, update the JSON data or the demo data generator instead of changing the HTML structure or CSS classes.

## Local Setup

From the repository root:

```bash
cd frontend-demo
npm install
npm run dev
```

Vite will print the local development URL in the terminal.

## Build

```bash
cd frontend-demo
npm run build
npm run preview
```

## Regenerate Demo Content

```bash
cd frontend-demo
npm run generate:data
```

This rewrites:

```text
frontend-demo/public/demo-data/posts.json
```

## Deployment

Recommended Vercel or Netlify settings:

```text
Root directory: frontend-demo
Build command: npm run build
Output directory: dist
Node version: Current LTS
```

## GitHub Version Control

Commit the reusable demo package:

- `frontend-demo/src/`
- `frontend-demo/public/demo-data/posts.json`
- `frontend-demo/public/demo-images/`
- `frontend-demo/scripts/`
- `frontend-demo/index.html`
- `frontend-demo/package.json`
- `frontend-demo/package-lock.json`
- `frontend-demo/vite.config.js`
- `frontend-demo/.gitignore`
- `README.md`

Do not commit:

- `.env` files
- API keys or private credentials
- `node_modules/`
- `dist/`
- Laravel cache files
- local logs
- private production CMS content
- private uploaded media

## Notes For Maintainers

The repository may still contain the original Laravel development workspace for local reference. The GitHub-ready demo application is intentionally isolated inside `frontend-demo/` so it can be deployed as a clean static frontend without affecting the local Laravel development site.

## Author

Delwar Hossain

- Website: `delwarhossain.net`
- Email: `hello@delwarhossain.net`

## License

Private project. All rights reserved.
