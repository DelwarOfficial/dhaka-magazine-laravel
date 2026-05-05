# Dhaka Magazine - Bengali News Portal

A modern, responsive Bengali news portal built with Laravel 11 and Tailwind CSS v4.

![Dhaka Magazine](./public/images/dhaka-magazine-color-logo.svg)

## Features

### 🎨 Design & UI
- **Dark/Light Theme**: Fully functional theme toggle with smooth transitions
- **Responsive Layout**: Optimized for desktop, tablet, and mobile devices
- **Professional Typography**: Noto Serif Bengali font for authentic Bengali text
- **Prothom Alo Style**: Modern news card designs following Bangladeshi news portal standards

### 🧭 Navigation
- **Sticky Navbar**: Stays at top on scroll with mini logo
- **Dropdown Menus**: Category-based dropdown with subcategories
- **Mobile Hamburger**: Slide-in menu for mobile devices
- **Bengali Date**: Real-time Bengali date display

### 📰 Hero Section
- **Desktop Layout**: 3-column grid (Left: 27%, Center: 46%, Right: 27%)
- **Mobile Layout**: Optimized 2-column grid with featured news
- **Featured News**: Image + Title + Excerpt (minimum 2 lines)
- **Advertisement Space**: 300x250px placeholder in right sidebar

### 📱 Responsive Design
- Mobile-first approach
- Breakpoints: md (768px), lg (1024px)
- Consistent image sizing (16:9, 4:3 ratios)
- Object-cover for all images

## Tech Stack

- **Framework**: Laravel 11
- **Styling**: Tailwind CSS v4
- **Build Tool**: Vite
- **Font**: Noto Serif Bengali
- **Icons**: SVG inline icons

## Project Structure

```
dhaka-magazine-laravel/
├── app/
│   ├── Helpers/
│   │   └── DateHelper.php         # Bengali date conversion
│   └── Http/Controllers/
│       ├── HomeController.php     # Homepage data
│       ├── ArticleController.php  # Article pages
│       └── CategoryController.php # Category pages
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php      # Main layout
│   │   ├── pages/
│   │   │   ├── home.blade.php     # Homepage
│   │   │   ├── article.blade.php  # Article detail
│   │   │   └── category.blade.php # Category page
│   │   ├── partials/
│   │   │   ├── header.blade.php  # Header with nav
│   │   │   └── footer.blade.php   # Footer
│   │   └── components/            # Reusable components
│   ├── css/
│   │   └── app.css               # Custom styles
│   └── js/
│       └── app.js                # JavaScript interactions
├── public/
│   └── images/                   # Static images
└── routes/
    └── web.php                   # Application routes
```

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL or SQLite

### Setup Steps

1. **Clone the repository**
```bash
git clone https://github.com/DelwarOfficial/dhaka-magazine-laravel.git
cd dhaka-magazine-laravel
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
```

4. **Generate application key**
```bash
php artisan key:generate
```

5. **Build assets**
```bash
npm run build
```

6. **Run the server**
```bash
php artisan serve
```

7. **Visit**: http://localhost:8000

## Configuration

### Theme Colors (in resources/css/app.css)
```css
--color-primary: rgb(226, 35, 26);  /* Red accent */
--color-bg: #ffffff;                /* Light background */
--color-fg: #1f2a44;               /* Text color */
```

### Dark Mode Colors
```css
--color-bg: #0f172a;                /* Dark background */
--color-fg: #f8fafc;                /* Light text */
```

## Available Routes

| Route | Description |
|-------|-------------|
| `/` | Homepage |
| `/category/{slug}` | Category page |
| `/article/{slug}` | Article detail |

## Customization

### Adding New Categories
Edit `app/Http/Controllers/HomeController.php` and modify the `$categories` array.

### Changing Logo
Replace images in `public/images/`:
- `dhaka-magazine-color-logo.svg` (light theme)
- `dhaka-magazine-white-logo.svg` (dark theme)

### Advertisement
Edit `resources/views/pages/home.blade.php` - search for "Advertisement" section.

## Development

### Running in Development Mode
```bash
# Terminal 1
npm run dev

# Terminal 2
php artisan serve
```

### Building for Production
```bash
npm run build
php artisan view:clear
php artisan route:clear
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin main`)
5. Open a Pull Request

## License

This project is open source and available under the [MIT License](LICENSE).

## Credits

- [Laravel](https://laravel.com) - The PHP Framework
- [Tailwind CSS](https://tailwindcss.com) - A utility-first CSS framework
- [Noto Serif Bengali](https://fonts.google.com/noto/specimen/Noto+Serif+Bengali) - Bengali font

---

**Dhaka Magazine** - বাংলাদেশের নির্ভরযোগ্য অনলাইন নিউজ পোর্টাল