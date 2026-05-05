<header class="w-full bg-bg flex flex-col font-serif dark:bg-surface" id="site-header">

  {{-- Top Bar: Logo + Right Side --}}
  <div class="w-full max-w-screen-xl mx-auto px-4 py-3">
    <div class="flex items-center justify-between">
      
      {{-- Logo --}}
      <a href="{{ route('home') }}" class="logo-link" aria-label="Dhaka Magazine">
        <img src="{{ asset('images/dhaka-magazine-color-logo.svg') }}" class="logo logo-light h-10 md:h-12" alt="Dhaka Magazine" />
        <img src="{{ asset('images/dhaka-magazine-white-logo.svg') }}" class="logo logo-dark h-10 md:h-12" alt="Dhaka Magazine" />
      </a>

      {{-- Right: Date + E-paper + Search + Hamburger --}}
      <div class="flex items-center gap-3 md:gap-4">
        
        {{-- Date + E-paper (stacked, right aligned) - Desktop only --}}
        <div class="hidden md:flex flex-col items-end">
          <span class="text-fg-secondary text-[13px] font-bengali">{{ \App\Helpers\DateHelper::getBengaliDate() }}</span>
          <a href="#" class="flex items-center gap-1 text-fg-secondary hover:text-fg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
            <span class="text-[12px]">ই-পেপার</span>
          </a>
        </div>

        {{-- Search --}}
        <button class="flex items-center gap-1 text-fg-secondary hover:text-fg transition-colors" aria-label="Search">
          <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </button>

        {{-- Hamburger (mobile only) --}}
        <button id="hamburger-btn" class="p-1 text-fg hover:text-fg-secondary md:hidden" aria-label="Toggle menu">
          <svg id="icon-menu" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
          <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="hidden"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>

      </div>
    </div>
  </div>

  {{-- Sticky Nav with Dropdowns --}}
  <div id="site-nav" class="w-full bg-[#1d2640] text-white sticky top-0 z-40">
    <div class="w-full max-w-screen-xl mx-auto px-4">
      <nav class="hidden md:flex items-center h-[48px] the-sticky-nav justify-between">
        
        <div class="flex items-center">
        {{-- Mini Logo (shows on scroll) --}}
        <a href="{{ route('home') }}" class="nav-mini-logo">
          <img src="{{ asset('images/dhaka-magazine-white-logo.svg') }}" class="h-0 transition-all duration-300" alt="Dhaka Magazine">
        </a>

        <a href="{{ route('home') }}" class="nav-item">সর্বশেষ</a>
        
        {{-- বাংলাদেশ with Dropdown --}}
        <div class="nav-dropdown">
          <a href="{{ route('category.show', 'জাতীয়') }}" class="nav-item flex items-center gap-1">
            বাংলাদেশ
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </a>
          <div class="nav-dropdown-menu">
            <a href="{{ route('category.show', 'জাতীয়') }}" class="nav-dropdown-item">জাতীয়</a>
            <a href="{{ route('category.show', 'রাজধানী') }}" class="nav-dropdown-item">রাজধানী</a>
            <a href="{{ route('category.show', 'অপরাধ') }}" class="nav-dropdown-item">অপরাধ</a>
            <a href="{{ route('category.show', 'দুর্ঘটনা') }}" class="nav-dropdown-item">দুর্ঘটনা</a>
            <a href="{{ route('category.show', 'আইন-বিচার') }}" class="nav-dropdown-item">আইন-বিচার</a>
          </div>
        </div>

        {{-- রাজনীতি with Dropdown --}}
        <div class="nav-dropdown">
          <a href="{{ route('category.show', 'রাজনীতি') }}" class="nav-item flex items-center gap-1">
            রাজনীতি
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </a>
          <div class="nav-dropdown-menu">
            <a href="{{ route('category.show', 'আওয়ামী-লীগ') }}" class="nav-dropdown-item">আওয়ামী লীগ</a>
            <a href="{{ route('category.show', 'বিএনপি') }}" class="nav-dropdown-item">বিএনপি</a>
            <a href="{{ route('category.show', 'জাতীয়-পার্টি') }}" class="nav-dropdown-item">জাতীয় পার্টি</a>
          </div>
        </div>

        {{-- অর্থনীতি with Dropdown --}}
        <div class="nav-dropdown">
          <a href="{{ route('category.show', 'অর্থনীতি') }}" class="nav-item flex items-center gap-1">
            অর্থনীতি
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </a>
          <div class="nav-dropdown-menu">
            <a href="{{ route('category.show', 'শেয়ারবাজার') }}" class="nav-dropdown-item">শেয়ারবাজার</a>
            <a href="{{ route('category.show', 'ব্যাংকিং') }}" class="nav-dropdown-item">ব্যাংকিং ও বীমা</a>
            <a href="{{ route('category.show', 'শিল্প') }}" class="nav-dropdown-item">শিল্প</a>
            <a href="{{ route('category.show', 'কৃষি') }}" class="nav-dropdown-item">কৃষি</a>
          </div>
        </div>

        <a href="{{ route('category.show', 'বিশ্ব') }}" class="nav-item">আন্তর্জাতিক</a>

        {{-- বিনোদন with Dropdown --}}
        <div class="nav-dropdown">
          <a href="{{ route('category.show', 'বিনোদন') }}" class="nav-item flex items-center gap-1">
            বিনোদন
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </a>
          <div class="nav-dropdown-menu">
            <a href="{{ route('category.show', 'ঢালিউড') }}" class="nav-dropdown-item">ঢালিউড</a>
            <a href="{{ route('category.show', 'বলিউড') }}" class="nav-dropdown-item">বলিউড</a>
            <a href="{{ route('category.show', 'হলিউড') }}" class="nav-dropdown-item">হলিউড</a>
            <a href="{{ route('category.show', 'টলিউড') }}" class="nav-dropdown-item">টলিউড</a>
          </div>
        </div>

        {{-- খেলাধুলা with Dropdown --}}
        <div class="nav-dropdown">
          <a href="{{ route('category.show', 'খেলা') }}" class="nav-item flex items-center gap-1">
            খেলাধুলা
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </a>
          <div class="nav-dropdown-menu">
            <a href="{{ route('category.show', 'ফুটবল') }}" class="nav-dropdown-item">ফুটবল</a>
            <a href="{{ route('category.show', 'ক্রিকেট') }}" class="nav-dropdown-item">ক্রিকেট</a>
            <a href="{{ route('category.show', 'অন্যান্য') }}" class="nav-dropdown-item">অন্যান্য</a>
          </div>
        </div>

        {{-- চাকরি with Dropdown --}}
        <div class="nav-dropdown">
          <a href="{{ route('category.show', 'চাকরি') }}" class="nav-item flex items-center gap-1">
            চাকরি
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </a>
          <div class="nav-dropdown-menu">
            <a href="{{ route('category.show', 'সরকারি-চাকরি') }}" class="nav-dropdown-item">সরকারি</a>
            <a href="{{ route('category.show', 'বেসরকারি-চাকরি') }}" class="nav-dropdown-item">বেসরকারি</a>
            <a href="{{ route('category.show', 'ব্যাংক-চাকরি') }}" class="nav-dropdown-item">ব্যাংক</a>
          </div>
        </div>

        {{-- লাইফস্টাইল with Dropdown --}}
        <div class="nav-dropdown">
          <a href="{{ route('category.show', 'লাইফস্টাইল') }}" class="nav-item flex items-center gap-1">
            জীবনযাপন
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </a>
          <div class="nav-dropdown-menu">
            <a href="{{ route('category.show', 'স্বাস্থ্য') }}" class="nav-dropdown-item">স্বাস্থ্য</a>
            <a href="{{ route('category.show', 'রূপচর্চা') }}" class="nav-dropdown-item">রূপচর্চা</a>
            <a href="{{ route('category.show', 'খাবার') }}" class="nav-dropdown-item">খাবার</a>
          </div>
        </div>

        <a href="{{ route('category.show', 'ভিডিও') }}" class="nav-item">ভিডিও</a>

        {{-- অন্যান্য with Dropdown --}}
        <div class="nav-dropdown">
          <a href="#" class="nav-item flex items-center gap-1">
            অন্যান্য
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </a>
          <div class="nav-dropdown-menu">
            <a href="{{ route('category.show', 'ধর্ম') }}" class="nav-dropdown-item">ধর্ম</a>
            <a href="{{ route('category.show', 'তথ্য-প্রযুক্তি') }}" class="nav-dropdown-item">তথ্য-প্রযুক্তি</a>
            <a href="{{ route('category.show', 'শিক্ষা') }}" class="nav-dropdown-item">শিক্ষা</a>
            <a href="{{ route('category.show', 'প্রবাস') }}" class="nav-dropdown-item">প্রবাস</a>
          </div>
        </div>
        </div>

        {{-- Search Icon (right side) --}}
        <a href="#" class="nav-search-link flex items-center gap-1 text-white hover:text-red-400 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
          <span class="text-[13px]">খুঁজুন</span>
        </a>

        {{-- Language (Eng) --}}
        <a href="#" class="nav-item flex items-center gap-1 text-white hover:text-red-400">
          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          <span class="text-[13px]">Eng</span>
        </a>

        {{-- Theme Toggle --}}
        <button id="theme-toggle-nav" class="flex items-center gap-1 text-white hover:text-red-400 transition-colors" aria-label="Toggle theme">
          <svg id="theme-icon-sun-nav" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
          <svg id="theme-icon-moon-nav" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="hidden"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        </button>

      </nav>
    </div>
  </div>

  {{-- Mobile Menu --}}
  <div id="mobile-menu" class="hidden fixed inset-0 top-[48px] bg-bg dark:bg-surface z-50 md:hidden">
    <nav class="flex flex-col p-4 overflow-y-auto h-full">
      
      {{-- Mobile Search --}}
      <div class="mb-4">
        <input type="text" placeholder="খুঁজুন..." class="w-full px-4 py-2 border border-border rounded-lg bg-bg text-fg">
      </div>

      <a href="{{ route('home') }}" class="mobile-nav-item">সর্বশেষ</a>
      
      {{-- বাংলাদেশ Accordion --}}
      <div class="mobile-accordion">
        <button class="mobile-accordion-btn" onclick="toggleMobileSubmenu(this)">
          <span>বাংলাদেশ</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div class="mobile-submenu hidden">
          <a href="{{ route('category.show', 'জাতীয়') }}" class="mobile-submenu-item">জাতীয়</a>
          <a href="{{ route('category.show', 'রাজধানী') }}" class="mobile-submenu-item">রাজধানী</a>
          <a href="{{ route('category.show', 'অপরাধ') }}" class="mobile-submenu-item">অপরাধ</a>
          <a href="{{ route('category.show', 'দুর্ঘটনা') }}" class="mobile-submenu-item">দুর্ঘটনা</a>
        </div>
      </div>

      <a href="{{ route('category.show', 'রাজনীতি') }}" class="mobile-nav-item">রাজনীতি</a>
      <a href="{{ route('category.show', 'অর্থনীতি') }}" class="mobile-nav-item">অর্থনীতি</a>
      <a href="{{ route('category.show', 'বিশ্ব') }}" class="mobile-nav-item">আন্তর্জাতিক</a>
      <a href="{{ route('category.show', 'বিনোদন') }}" class="mobile-nav-item">বিনোদন</a>
      <a href="{{ route('category.show', 'খেলা') }}" class="mobile-nav-item">খেলাধুলা</a>
      <a href="{{ route('category.show', 'চাকরি') }}" class="mobile-nav-item">চাকরি</a>
      <a href="{{ route('category.show', 'লাইফস্টাইল') }}" class="mobile-nav-item">জীবনযাপন</a>
      <a href="{{ route('category.show', 'ভিডিও') }}" class="mobile-nav-item">ভিডিও</a>

      {{-- Mobile Action Buttons --}}
      <div class="mt-4 pt-4 border-t border-border flex gap-2">
        <button onclick="toggleTheme()" class="flex-1 py-2 px-4 bg-primary text-white rounded-lg flex items-center justify-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
          Dark Mode
        </button>
        <button class="flex-1 py-2 px-4 border border-border rounded-lg flex items-center justify-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
          ই-পেপার
        </button>
      </div>
    </nav>
  </div>

</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const hamburgerBtn = document.getElementById('hamburger-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const iconMenu = document.getElementById('icon-menu');
  const iconClose = document.getElementById('icon-close');

  if (hamburgerBtn && mobileMenu) {
    hamburgerBtn.addEventListener('click', function() {
      mobileMenu.classList.toggle('hidden');
      iconMenu.classList.toggle('hidden');
      iconClose.classList.toggle('hidden');
    });
  }

  // Scroll detection for mini logo
  const siteNav = document.getElementById('site-nav');
  if (siteNav) {
    window.addEventListener('scroll', function() {
      const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
      if (currentScroll > 80) {
        siteNav.classList.add('is-sticky-scrolled');
      } else {
        siteNav.classList.remove('is-sticky-scrolled');
      }
    });
  }
});

function toggleMobileSubmenu(btn) {
  const submenu = btn.nextElementSibling;
  submenu.classList.toggle('hidden');
  btn.querySelector('svg').classList.toggle('rotate-180');
}
</script>