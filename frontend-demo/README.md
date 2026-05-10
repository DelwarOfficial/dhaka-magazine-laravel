# Dhaka Magazine Frontend Demo

A frontend-only Dhaka Magazine demo package for GitHub, Vercel, and Netlify. This package has no Laravel backend, no database, and no private production data.

## What Is Included

- Static Vite frontend
- JSON-powered demo content at `public/demo-data/posts.json`
- Reusable demo assets at `public/demo-images/`
- Responsive homepage sections, hero carousel, sidebar widgets, ad slots, dark mode, mobile menu, and all-posts grid

## What Is Not Included

- `.env` secrets
- database files
- Laravel storage/cache files
- production CMS content
- private uploaded media

## Setup

```bash
npm install
npm run dev
```

The app runs with Vite at the local URL printed in your terminal.

## Build

```bash
npm run build
npm run preview
```

## Demo Data

Demo posts live in:

```text
public/demo-data/posts.json
```

Each post uses this shape:

```json
{
  "id": 1,
  "slug": "demo-news-1",
  "title": "বাংলাদেশ: ...",
  "excerpt": "...",
  "content": "...",
  "author": "ঢাকা ম্যাগাজিন ডেস্ক",
  "category": "bangladesh",
  "category_bn": "বাংলাদেশ",
  "image_path": "news-1.jpg",
  "view_count": 1200,
  "published_at": "2026-05-10T12:00:00.000Z"
}
```

Images are resolved from:

```text
public/demo-images/{image_path}
```

If an image is missing, the UI falls back to a reusable placeholder image from `public/demo-images/`.

## Regenerate Demo Data

```bash
npm run generate:data
```

This rewrites `public/demo-data/posts.json` with 330 reusable demo posts.

## Deployment

This project is ready for Vercel or Netlify as a static Vite app.

Recommended settings:

- Build command: `npm run build`
- Output directory: `dist`
- Node version: current LTS

## GitHub Notes

Commit:

- `src/`
- `public/demo-data/posts.json`
- `public/demo-images/`
- `index.html`
- `package.json`
- `vite.config.js`
- `.gitignore`
- `README.md`

Do not commit:

- `node_modules/`
- `dist/`
- `.env`
- local cache/log files
