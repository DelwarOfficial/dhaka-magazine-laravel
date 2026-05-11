@extends('layouts.app')

@section('title', 'ঢাকা ম্যাগাজিন - হোম')

@section('content')

  {{-- Placement-fed hero.
       Current source: post flags/order columns. Future CMS source: content_placements. --}}
  <div class="w-full max-w-screen-xl mx-auto px-4 hero-section">
    {{-- Mobile: 2-column grid for left+right --}}
    <div class="grid grid-cols-1 md:hidden gap-3 border-t border-border">
      
      {{-- Featured (Center) on Mobile - Full width --}}
      @if(isset($featured))
        <a href="{{ route('article.show', $featured['slug']) }}" class="group flex flex-col py-4 border-b border-border">
          <div class="w-full aspect-[16/9] overflow-hidden rounded-sm mb-2">
            <img src="{{ $featured['image_url'] }}" alt="{{ $featured['title'] }}" loading="lazy"
              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
          </div>
          <h2 class="font-serif font-bold text-[16px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
            {{ $featured['title'] }}
          </h2>
          <p class="text-fg-secondary text-[12px] line-clamp-2 mt-1 min-h-[2em]">{{ $featured['excerpt'] }}</p>
        </a>
      @endif

      {{-- Center Grid (6 articles) on Mobile - 3-column --}}
      @if(isset($centerGrid))
        <div class="grid grid-cols-3 gap-3 py-2 border-b border-border">
          @foreach($centerGrid as $a)
            <a href="{{ route('article.show', $a['slug']) }}" class="group flex flex-col">
              @if(!empty($a['image_url']))
                <div class="w-full aspect-[4/3] overflow-hidden rounded-sm mb-2">
                  <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
                </div>
              @endif
              <span class="text-[#e2231a] font-bold text-[11px] block mb-0.5">{{ $a['category'] }}</span>
              <h3 class="font-serif font-bold text-[13px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                {{ $a['title'] }}
              </h3>
            </a>
          @endforeach
        </div>
      @endif

      {{-- Left Column - 1-column: Title left + Image right (16:9) --}}
      @if(isset($leftCol))
        <div class="py-2">
          @foreach($leftCol as $a)
            <a href="{{ route('article.show', $a['slug']) }}" class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px] block mb-0.5">{{ $a['category'] }}</span>
                <h3 class="font-serif font-bold text-[14px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  {{ $a['title'] }}
                </h3>
              </div>
              @if(!empty($a['image_url']))
                <div class="w-[120px] h-[68px] shrink-0 overflow-hidden rounded-sm">
                  <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
                </div>
              @endif
            </a>
          @endforeach
        </div>
      @endif

      {{-- Right Column - 1-column: Image left + Title right --}}
      @if(isset($rightCol))
        <div class="py-2">
          @foreach($rightCol as $a)
            <a href="{{ route('article.show', $a['slug']) }}" class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
              @if(!empty($a['image_url']))
                <div class="w-[120px] h-[68px] shrink-0 overflow-hidden rounded-sm">
                  <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
                </div>
              @endif
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px] block mb-0.5">{{ $a['category'] }}</span>
                <h3 class="font-serif font-bold text-[14px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  {{ $a['title'] }}
                </h3>
              </div>
            </a>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Desktop: 3-column layout --}}
    <div class="hidden md:grid grid-cols-1 lg:grid-cols-[27%_46%_27%] hero-grid border-t border-border">

      {{-- LEFT: list articles - Text (left) + Image (right) on desktop, stacked on mobile --}}
      <div class="py-4 pr-0 lg:pr-5 lg:border-r border-border order-2 lg:order-1">
        @if(isset($leftCol))
          @foreach($leftCol as $a)
            <a href="{{ route('article.show', $a['slug']) }}"
              class="group flex flex-col-reverse md:flex-row items-start gap-3 py-3 border-b border-border last:border-b-0">
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px] block mb-0.5">{{ $a['category'] }} &bull;</span>
                <h3 class="font-serif font-bold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  {{ $a['title'] }}
                </h3>
                @if(!empty($a['excerpt']))
                  <p class="text-fg-secondary text-[12px] line-clamp-2 mt-1">{{ $a['excerpt'] }}</p>
                @endif
                <div class="text-[11px] text-fg-muted mt-1">{{ $a['time_ago'] }}</div>
              </div>
              @if(!empty($a['image_url']))
                <div class="w-full md:w-[88px] h-[140px] md:h-[50px] shrink-0 overflow-hidden rounded-sm mb-2 md:mb-0">
                  <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
                </div>
              @endif
            </a>
          @endforeach
        @endif
      </div>

      {{-- CENTER --}}
      <div class="py-4 px-0 lg:px-5 lg:border-r border-border order-1 lg:order-2">
        {{-- Featured: Image top + Title below (mobile style for desktop too) --}}
        @if(isset($featured))
          <a href="{{ route('article.show', $featured['slug']) }}" class="group flex flex-col mb-3 pb-3 border-b border-border">
            <div class="w-full aspect-[16/9] overflow-hidden rounded-sm mb-2">
              <img src="{{ $featured['image_url'] }}" alt="{{ $featured['title'] }}" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
            </div>
            <h2 class="font-serif font-bold text-[22px] leading-[1.25] text-fg group-hover:text-[#e2231a] transition-colors mb-2">
              {{ $featured['title'] }}
            </h2>
            @if(!empty($featured['excerpt']))
              <p class="text-fg-secondary text-[13px] line-clamp-2 leading-relaxed min-h-[2.5em]">{{ $featured['excerpt'] }}</p>
            @endif
          </a>
        @endif

        {{-- 3-column grid --}}
        @if(isset($centerGrid))
          <div class="grid grid-cols-3 gap-x-4 gap-y-4">
            @foreach($centerGrid as $a)
              <a href="{{ route('article.show', $a['slug']) }}" class="group flex flex-col">
                <div class="w-full aspect-[16/9] overflow-hidden mb-1.5">
                  <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
                </div>
                <h3 class="font-serif font-bold text-[13px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  {{ $a['title'] }}
                </h3>
                <div class="text-[11px] text-fg-muted mt-0.5">{{ $a['time_ago'] }}</div>
              </a>
            @endforeach
          </div>
        @endif
      </div>

      {{-- RIGHT: Image (left) + Text (right) on desktop, stacked on mobile --}}
      <div class="py-4 pl-0 lg:pl-5 order-3 lg:order-3">
        
        {{-- Advertisement 300x250 --}}
        <div class="w-full h-[250px] mb-4 overflow-hidden rounded-lg">
          <img src="{{ asset('images/coming-soon-ad.webp') }}" alt="Advertisement" class="w-full h-full object-cover" />
        </div>

        @if(isset($rightCol))
          @foreach($rightCol as $a)
            <a href="{{ route('article.show', $a['slug']) }}"
              class="group flex flex-col-reverse md:flex-row items-start gap-3 py-3 border-b border-border last:border-b-0">
              @if(!empty($a['image_url']))
                <div class="w-full md:w-[88px] h-[140px] md:h-[50px] shrink-0 overflow-hidden rounded-sm mb-2 md:mb-0">
                  <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
                </div>
              @endif
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px] block mb-0.5">{{ $a['category'] }} &bull;</span>
                <h3 class="font-serif font-bold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  {{ $a['title'] }}
                </h3>
                @if(!empty($a['excerpt']))
                  <p class="text-fg-secondary text-[12px] line-clamp-2 mt-1">{{ $a['excerpt'] }}</p>
                @endif
                <div class="text-[11px] text-fg-muted mt-1">{{ $a['time_ago'] }}</div>
              </div>
            </a>
          @endforeach
        @endif
      </div>
    </div>
  </div>


  {{-- ── ADVERTISEMENT BANNER ─────────────────────────────────── --}}
  <div class="w-full max-w-screen-xl mx-auto px-4 py-3">
    <div class="w-full bg-surface border border-border flex items-center justify-center h-[90px] rounded-lg">
      <span class="text-fg-muted text-[12px] tracking-widest uppercase">বিজ্ঞাপন</span>
    </div>
  </div>

  {{-- 1. PHOTO NEWS (ফটো সংবাদ) --}}
  <x-photo-news-block 
    :carousel-articles="$photoNewsArticles" 
    :latest-articles="$photoNewsLatest" 
    :popular-articles="$photoNewsPopular" 
  />

  <div class="border-t-4 border-border"></div>

  <x-home.category-grid-section
    title="বাংলাদেশ"
    :posts="$bangladeshArticles ?? []"
    :moreUrl="route('category.parent', 'bangladesh')"
  />

  <div class="border-t-4 border-border"></div>

  <x-home.local-news-section
    :left-posts="$countryLeft ?? []"
    :hero-post="$countryHero ?? null"
    :right-posts="$countryRight ?? []"
    :divisions="$saradeshDivisions ?? []"
  />

  <div class="border-t-4 border-border"></div>

  {{-- ══ POLITICS / রাজনীতি ════════════════════════════════════════════ --}}
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">

    <div class="flex items-center gap-3 mb-4 border-b border-border pb-2">
      <span class="section-icon"></span>
      <h2 class="font-serif font-extrabold text-[20px] text-fg">
        <a href="{{ route('category.child', ['bangladesh', 'politics']) }}" class="hover:text-[#e2231a] transition-colors">রাজনীতি</a>
      </h2>
    </div>

    @if(isset($opinionArticles) && count($opinionArticles) >= 1)
    @php
      $polFeatured = $opinionArticles[0];
      $polSmall = array_slice($opinionArticles, 1, 4);
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-[2fr_2fr_1fr] gap-0 divide-x divide-border">

      {{-- COL 1: featured politics post with image + title + excerpt --}}
      <div class="pr-6">
        <a href="{{ route('article.show', $polFeatured['slug']) }}" class="group block">
          <div class="w-full aspect-[16/9] overflow-hidden mb-3">
            <img src="{{ $polFeatured['image_url'] }}" alt="{{ $polFeatured['title'] }}" loading="lazy"
              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
          </div>
          <h3 class="font-serif font-extrabold text-[20px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors mb-2 line-clamp-3">
            {{ $polFeatured['title'] }}
          </h3>
          <p class="text-fg-secondary text-[13px] leading-relaxed line-clamp-3">{{ $polFeatured['excerpt'] }}</p>
          @if(!empty($polFeatured['time_ago']))
            <div class="text-[11px] text-fg-muted mt-2">{{ $polFeatured['time_ago'] }}</div>
          @endif
        </a>
      </div>

      {{-- COL 2: stacked politics post list --}}
      <div class="px-6 flex flex-col divide-y divide-border">
        @foreach($polSmall as $a)
          <a href="{{ route('article.show', $a['slug']) }}"
            class="group flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
            <h3 class="font-serif font-extrabold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-3 flex-1">
              {{ $a['title'] }}
            </h3>
            <div class="w-[72px] h-[40px] shrink-0 overflow-hidden">
              <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
            </div>
          </a>
        @endforeach
      </div>

      {{-- COL 3: ad unit placeholder --}}
      <div class="pl-6 flex flex-col items-center">
        <div class="ad-container bg-surface border border-border flex flex-col items-center justify-center relative">
          <span class="text-[#e2231a] text-[11px] font-bold tracking-wide mb-1 absolute top-2 right-2 z-10">বিজ্ঞাপন</span>
          <img src="{{ asset('images/coming-soon-ad.webp') }}" alt="Advertisement" class="w-full h-full object-cover opacity-50" />
        </div>
      </div>

    </div>
    @endif

  </div>

  <div class="border-t-4 border-border"></div>

  {{-- ══ INTERNATIONAL ════════════════════════════════════════ --}}
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <div class="flex items-center gap-3 mb-4 border-b border-border pb-2">
      <span class="section-icon"></span>
      <h2 class="font-serif font-extrabold text-[20px] text-fg">
        <a href="{{ route('category.parent', 'world') }}" class="hover:text-[#e2231a] transition-colors">আন্তর্জাতিক</a>
      </h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-[2fr_2fr_1fr] gap-0 divide-x divide-border">

      {{-- COL 1: big article with image + title + excerpt + time --}}
      <div class="pr-6">
        @if(isset($internationalBig))
          <a href="{{ route('article.show', $internationalBig['slug']) }}" class="group block">
            <div class="w-full aspect-[16/9] overflow-hidden mb-3">
              <img src="{{ $internationalBig['image_url'] }}" alt="{{ $internationalBig['title'] }}" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
            </div>
            <h3 class="font-serif font-extrabold text-[20px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors mb-2 line-clamp-3">
              {{ $internationalBig['title'] }}
            </h3>
            <p class="text-fg-secondary text-[13px] leading-relaxed line-clamp-3">{{ $internationalBig['excerpt'] }}</p>
            <div class="text-[11px] text-fg-muted mt-2">{{ $internationalBig['time_ago'] }}</div>
          </a>
        @endif
      </div>

      {{-- COL 2: horizontal list (title + image, no category/time) --}}
      <div class="px-6 flex flex-col divide-y divide-border">
        @if(isset($internationalSmall))
          @foreach($internationalSmall as $a)
            <a href="{{ route('article.show', $a['slug']) }}"
              class="group flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
              <h3 class="font-serif font-extrabold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-3 flex-1">
                {{ $a['title'] }}
              </h3>
              <div class="w-[72px] h-[40px] shrink-0 overflow-hidden">
                <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
            </a>
          @endforeach
        @endif
      </div>

      {{-- COL 3: 300x250 Ad Unit --}}
      <div class="pl-6 flex flex-col items-center">
        <div class="ad-container bg-surface border border-border flex flex-col items-center justify-center relative">
          <span class="text-[#e2231a] text-[11px] font-bold tracking-wide mb-1 absolute top-2 right-2 z-10">বিজ্ঞাপন</span>
          <img src="{{ asset('images/coming-soon-ad.webp') }}" alt="Advertisement" class="w-full h-full object-cover opacity-50" />
        </div>
      </div>

    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  {{-- ══ SPORTS (খেলাধুলা) — 3 panel ════════════════════════ --}}
  <x-sports-block 
    :sports-articles="$sportsArticles ?? []" 
    :sports-subcat-articles="$sportsSubcatArticles ?? []" 
  />

  <div class="border-t-4 border-border"></div>

  {{-- ══ মতামত ═════════════════════════════════════ --}}
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <x-section-header title="মতামত" :moreUrl="route('category.parent', 'opinion')" />
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
      @if(isset($matamatArticles))
        @foreach($matamatArticles as $a)
          <x-cards.grid :article="$a" :titleSize="15" />
        @endforeach
      @endif
    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  {{-- ══ VIDEO — dark bg, 1 large left + 3 small right ════════ --}}
  <x-video-block 
    :video-featured="$videoFeatured ?? null" 
    :video-small="$videoSmall ?? null" 
  />

  <div class="border-t-4 border-border"></div>

  {{-- ══ ENTERTAINMENT (বিনোদন) ═══════════════════════════════ --}}
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <x-section-header title="বিনোদন" :moreUrl="route('category.parent', 'entertainment')" />

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.5fr_1fr] gap-0 lg:divide-x divide-border bg-surface border border-border">

      {{-- LEFT COL --}}
      <div class="flex flex-col p-4 pb-0">
        @if(isset($entertainmentLeft))
          @foreach($entertainmentLeft as $a)
            <a href="{{ route('article.show', $a['slug']) }}"
              class="group flex items-start gap-3 py-4 border-b border-border first:pt-0 last:border-b-0 last:pb-4">
              <div class="flex-1 min-w-0">
                <h3 class="font-serif font-bold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  {{ $a['title'] }}
                </h3>
                <p class="text-fg-secondary text-[13px] line-clamp-2 mt-1.5 leading-relaxed">{{ $a['excerpt'] }}</p>
              </div>
              <div class="w-[90px] aspect-square shrink-0 overflow-hidden rounded-sm">
                <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
            </a>
          @endforeach
        @endif
      </div>

      {{-- CENTER COL --}}
      <div class="p-4 flex flex-col">
        @if(isset($entertainmentHero))
          <a href="{{ route('article.show', $entertainmentHero['slug']) }}" class="group flex flex-col">
            <div class="w-full overflow-hidden mb-4 rounded-sm">
              <div class="aspect-[16/9] w-full">
                <img src="{{ $entertainmentHero['image_url'] }}" alt="{{ $entertainmentHero['title'] }}" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
            </div>
            <h3 class="font-serif font-extrabold text-[24px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors mb-2.5">
              {{ $entertainmentHero['title'] }}
            </h3>
            <p class="text-fg-secondary text-[15px] leading-relaxed line-clamp-3">
              {{ $entertainmentHero['excerpt'] }}
            </p>
          </a>
        @endif
      </div>

      {{-- RIGHT COL --}}
      <div class="flex flex-col p-4 pb-0">
        @if(isset($entertainmentRight))
          @foreach($entertainmentRight as $a)
            <a href="{{ route('article.show', $a['slug']) }}"
              class="group flex items-start gap-3 py-4 border-b border-border first:pt-0 last:border-b-0 last:pb-4">
              <div class="flex-1 min-w-0">
                <h3 class="font-serif font-bold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  {{ $a['title'] }}
                </h3>
                <p class="text-fg-secondary text-[13px] line-clamp-2 mt-1.5 leading-relaxed">{{ $a['excerpt'] }}</p>
              </div>
              <div class="w-[90px] aspect-square shrink-0 overflow-hidden rounded-sm">
                <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
            </a>
          @endforeach
        @endif
      </div>

    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  {{-- ══ ECONOMY + LIFESTYLE + JOBS 3-Column ════════════════════════ --}}
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-0 lg:divide-x divide-border">
      
      {{-- Column 1: Economy --}}
      <div class="lg:pr-6">
        <x-section-header title="অর্থনীতি" :moreUrl="route('category.parent', 'economy')" />
        @if(isset($economyArticles))
          @foreach($economyArticles as $a)
            <a href="{{ route('article.show', $a['slug']) }}"
              class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
              <div class="w-[130px] h-[73px] shrink-0 overflow-hidden rounded-sm">
                <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px]">{{ $a['category'] }} &bull;</span>
                <h3 class="font-serif font-bold text-[16px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-2 mt-0.5">
                  {{ $a['title'] }}
                </h3>
                <p class="text-fg-secondary text-[13px] line-clamp-2 mt-1 leading-relaxed">{{ $a['excerpt'] }}</p>
                <div class="text-[11px] text-fg-muted mt-1">{{ $a['time_ago'] }}</div>
              </div>
            </a>
          @endforeach
        @endif
      </div>

      {{-- Column 2: Lifestyle --}}
      <div class="lg:px-6">
        <x-section-header title="লাইফস্টাইল" :moreUrl="route('category.parent', 'lifestyle')" />
        @if(isset($healthArticles))
          @foreach($healthArticles as $a)
            <a href="{{ route('article.show', $a['slug']) }}"
              class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
              <div class="w-[130px] h-[73px] shrink-0 overflow-hidden rounded-sm">
                <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px]">{{ $a['category'] }} &bull;</span>
                <h3 class="font-serif font-bold text-[16px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-2 mt-0.5">
                  {{ $a['title'] }}
                </h3>
                <p class="text-fg-secondary text-[13px] line-clamp-2 mt-1 leading-relaxed">{{ $a['excerpt'] }}</p>
                <div class="text-[11px] text-fg-muted mt-1">{{ $a['time_ago'] }}</div>
              </div>
            </a>
          @endforeach
        @endif
      </div>

      {{-- Column 3: Jobs --}}
      <div class="lg:pl-6 md:col-span-2 lg:col-span-1 mt-2 md:mt-0">
        <x-section-header title="চাকরি" :moreUrl="route('category.parent', 'jobs')" />
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-0 md:gap-6 lg:gap-0">
          @if(isset($jobArticles))
            @foreach($jobArticles as $a)
              <a href="{{ route('article.show', $a['slug']) }}"
                class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
                <div class="w-[130px] h-[73px] shrink-0 overflow-hidden rounded-sm">
                  <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
                </div>
                <div class="flex-1 min-w-0">
                  <span class="text-[#e2231a] font-bold text-[12px]">{{ $a['category'] }} &bull;</span>
                  <h3 class="font-serif font-bold text-[16px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-2 mt-0.5">
                    {{ $a['title'] }}
                  </h3>
                  <p class="text-fg-secondary text-[13px] line-clamp-2 mt-1 leading-relaxed">{{ $a['excerpt'] }}</p>
                  <div class="text-[11px] text-fg-muted mt-1">{{ $a['time_ago'] }}</div>
                </div>
              </a>
            @endforeach
          @endif
        </div>
      </div>

    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  {{-- ══ ঢাকা ম্যাগাজিন স্পেশাল ════════════════════════════ --}}
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <x-section-header title="ঢাকা ম্যাগাজিন স্পেশাল" :moreUrl="route('category.parent', 'dhaka-magazine-special')" />

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-0 divide-x divide-border">
      @if(isset($specialArticles))
        @foreach($specialArticles as $i => $a)
          <a href="{{ route('article.show', $a['slug']) }}"
            class="group flex flex-col {{ $i === 0 ? 'pr-4' : ($i === 4 ? 'pl-4' : 'px-4') }}">
            <div class="w-full aspect-[16/9] overflow-hidden mb-3 rounded-sm">
              <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
            </div>
            <h3 class="font-serif font-bold text-[15px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-3 mb-1.5">
              {{ $a['title'] }}
            </h3>
            <p class="text-fg-secondary text-[13px] leading-relaxed line-clamp-3">
              {{ $a['excerpt'] }}
            </p>
          </a>
        @endforeach
      @endif
    </div>
  </div>
  <div class="border-t-4 border-border"></div>

  {{-- ══ BOTTOM 4 COLUMNS (ধর্ম, তথ্য-প্রযুক্তি, শিক্ষা, প্রবাস) ══════════════ --}}
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
      
      @php
        $bottomCols = [
          ['name' => 'ধর্ম', 'articles' => $religionArticles ?? []],
          ['name' => 'রাজধানী', 'articles' => $rajdhaniArticles ?? []],
          ['name' => 'শিক্ষা', 'articles' => $educationArticles ?? []],
          ['name' => 'প্রবাস', 'articles' => $probashArticles ?? []],
        ];
      @endphp

      @foreach($bottomCols as $col)
        <div class="flex flex-col">
          @if(count($col['articles']) > 0)
            {{-- Hero Post --}}
            <a href="{{ route('article.show', $col['articles'][0]['slug']) }}" class="group flex flex-col mb-3">
              <div class="relative w-full aspect-[16/9] overflow-hidden mb-3">
                <img src="{{ $col['articles'][0]['image_url'] }}" alt="{{ $col['articles'][0]['title'] }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
                
                {{-- Red bottom border & category tag --}}
                <div class="absolute bottom-0 left-0 right-0 flex items-end">
                  <span class="bg-[#e2231a] text-white text-[11px] font-bold px-2 py-0.5 z-10">{{ $col['name'] }}</span>
                  <div class="flex-1 h-[2px] bg-[#e2231a] mb-0.5"></div>
                </div>
              </div>
              <h3 class="font-serif font-extrabold text-[17px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-3">
                {{ $col['articles'][0]['title'] }}
              </h3>
            </a>

            {{-- 3 text items --}}
            <div class="flex flex-col">
              @foreach(array_slice($col['articles'], 1, 3) as $a)
                <a href="{{ route('article.show', $a['slug']) }}" class="group py-3 border-t border-border">
                  <h3 class="font-serif font-bold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-3">
                    {{ $a['title'] }}
                  </h3>
                </a>
              @endforeach
            </div>
          @endif
        </div>
      @endforeach

    </div>
  </div>
  {{-- ── FOOTER AD ────────────────────────────────────────────── --}}
  <div class="w-full max-w-screen-xl mx-auto px-4 pb-4">
    <div class="w-full bg-surface border border-border flex items-center justify-center h-[80px]">
      <span class="text-fg-muted text-[12px] tracking-widest uppercase">বিজ্ঞাপন</span>
    </div>
  </div>

@endsection
