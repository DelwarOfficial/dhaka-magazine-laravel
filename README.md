# ঢাকা ম্যাগাজিন — Dhaka Magazine

A modern Bengali-language news portal built with **Laravel 13**, **Tailwind CSS v4**, and **Vite**.

---

## ✨ Features

- Fully responsive homepage with 3-column hero layout
- Category-based article sections (বাংলাদেশ, রাজনীতি, আন্তর্জাতিক, বিনোদন, খেলাধুলা, প্রযুক্তি, and more)
- Animated news ticker / breaking news bar
- Photo news carousel (ফটো সংবাদ)
- Sports block with prayer times sidebar
- Video block section
- Dark mode support
- Sticky navigation with mobile hamburger menu
- Advertisement unit placeholders (300×250, 728×90)
- Bengali date formatting helper

---

## 🛠 Tech Stack

| Layer       | Technology                    |
|-------------|-------------------------------|
| Framework   | Laravel 13 (PHP 8.3+)         |
| CSS         | Tailwind CSS v4 (via Vite)    |
| Build Tool  | Vite 8                        |
| Templating  | Laravel Blade                 |
| Font        | Google Fonts — Hind Siliguri  |

---

## 🚀 Local Setup

### Prerequisites
- PHP 8.3+
- Composer
- Node.js 20+
- A local dev server (Herd, Laragon, or Valet)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/DelwarOfficial/dhaka-magazine-laravel.git
cd dhaka-magazine-laravel

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Run migrations (if applicable)
php artisan migrate

# 6. Link storage
php artisan storage:link

# 7. Start the dev server
npm run dev
```

Then visit `http://dhaka-magazine-laravel.test` (or your configured local URL).

---

## 📁 Project Structure

```
resources/
├── views/
│   ├── components/        # Blade components (cards, section headers, blocks)
│   ├── layouts/           # Base app layout
│   ├── pages/             # Page views (home, article, category…)
│   └── partials/          # Header, footer, ticker
├── css/                   # Tailwind entry point + custom design tokens
└── js/                    # App JS entry

app/
├── Http/Controllers/      # HomeController, ArticleController, etc.
└── Helpers/               # DateHelper (Bengali date formatting)
```

---

## 🎨 Design System

CSS custom properties are defined in `resources/css/app.css`:

| Token           | Purpose                   |
|-----------------|---------------------------|
| `--color-bg`    | Page background           |
| `--color-fg`    | Primary text              |
| `--color-primary` | Brand red `#e2231a`    |
| `--color-surface` | Card / panel background |
| `--color-border`  | Divider / border color  |

Dark mode is toggled via a `dark` class on `<html>` and persisted in `localStorage`.

---

## 📜 License

Private project — all rights reserved © Dhaka Magazine.