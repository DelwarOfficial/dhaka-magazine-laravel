<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ঢাকা ম্যাগাজিন')</title>
    <meta name="description" content="@yield('meta_description', 'ঢাকা ম্যাগাজিন - বাংলাদেশের নির্ভরযোগ্য অনলাইন নিউজ পোর্টাল')">
    <script>
        // Prevent flash of wrong theme
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-bg text-fg">

    @include('partials.header')

    <main class="flex-1 w-full">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
