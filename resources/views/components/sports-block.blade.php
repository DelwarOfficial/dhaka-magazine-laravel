@props(['sportsArticles' => [], 'sportsSubcatArticles' => []])

<div class="w-full max-w-screen-xl mx-auto px-4 py-5">
  <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.5fr_1fr] gap-6 lg:gap-8 lg:divide-x divide-border">

    {{-- LEFT: Tabbed numbered list --}}
    <div class="lg:pr-2 pt-1 order-2 lg:order-1">
      <div class="flex mb-3">
        <button class="font-serif text-[14px] mr-5 pb-2 border-b-2 border-[#e2231a] text-fg font-extrabold transition-colors">পঠিত</button>
        <button class="font-serif text-[14px] mr-5 pb-2 border-b-2 border-transparent text-fg-muted hover:text-fg transition-colors">আলোচিত</button>
      </div>
      @if(isset($sportsArticles))
        @foreach(array_slice($sportsArticles, 0, 5) as $i => $a)
          @php
            $bengaliNumbers = ['১','২','৩','৪','৫','৬','৭','৮','৯','১০'];
            $number = $bengaliNumbers[$i] ?? ($i + 1);
          @endphp
          <a href="{{ route('article.show', $a['slug']) }}"
            class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
            <span class="font-serif font-bold text-[34px] text-fg-muted shrink-0 w-8 text-center leading-none mt-0.5">
              {{ $number }}
            </span>
            <div class="flex-1 pt-0.5">
              <h3 class="font-serif font-extrabold text-[14px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-3">
                {{ $a['title'] }}
              </h3>
              <div class="text-[11px] text-fg-muted mt-1">{{ $a['time_ago'] }}</div>
            </div>
          </a>
        @endforeach
      @endif
    </div>

    {{-- CENTER: Hero overlay + 2-col grid (Now ~40% smaller hero due to 1.5fr ratio) --}}
    <div class="lg:px-6 py-1 order-1 lg:order-2 border-b lg:border-b-0 border-border pb-6 lg:pb-0">
      <x-section-header title="খেলা" :moreUrl="route('category.show', 'খেলা')" />

      @if(isset($sportsArticles) && count($sportsArticles) > 0)
        <a href="{{ route('article.show', $sportsArticles[0]['slug']) }}" class="group block mb-4">
          <div class="relative w-full aspect-video overflow-hidden rounded-sm">
            <img src="{{ $sportsArticles[0]['image_url'] }}" alt="{{ $sportsArticles[0]['title'] }}" loading="lazy"
              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 px-4 py-3">
              <h3 class="font-serif font-extrabold text-[20px] md:text-[22px] text-white leading-tight group-hover:text-[#f8a0a0] transition-colors line-clamp-2 drop-shadow">
                {{ $sportsArticles[0]['title'] }}
              </h3>
              <div class="text-[12px] text-white/70 mt-1.5">{{ $sportsArticles[0]['time_ago'] }}</div>
            </div>
          </div>
        </a>

        <div class="grid grid-cols-2 gap-4">
          @foreach(array_slice($sportsArticles, 1, 2) as $i => $a)
            <a href="{{ route('article.show', $a['slug']) }}"
              class="group flex flex-col">
              <div class="relative w-full aspect-[16/9] overflow-hidden rounded-sm mb-2">
                <img src="{{ $a['image_url'] }}" alt="{{ $a['title'] }}" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
              <h3 class="font-serif font-extrabold text-[13px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-2">
                {{ $a['title'] }}
              </h3>
            </a>
          @endforeach
        </div>
      @endif
    </div>

    {{-- RIGHT: sub-category articles --}}
    <div class="lg:pl-6 pt-1 order-3 lg:order-3 border-t lg:border-t-0 border-border pt-6 lg:pt-0">
      <div class="w-full bg-surface border border-border flex items-center justify-center h-[80px] rounded-sm mb-4">
        <span class="text-fg-muted text-[12px] tracking-widest uppercase">বিজ্ঞাপন</span>
      </div>
      <div>
        @if(isset($sportsSubcatArticles))
          @foreach($sportsSubcatArticles as $item)
            <a href="{{ route('article.show', $item['article']['slug']) }}"
              class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px]">{{ $item['subcat'] }} &bull;</span>
                <h3 class="font-serif font-bold text-[14px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-2 mt-0.5">
                  {{ $item['article']['title'] }}
                </h3>
                <div class="text-[11px] text-fg-muted mt-1.5">{{ $item['article']['time_ago'] }}</div>
              </div>
              <div class="w-[80px] aspect-video shrink-0 overflow-hidden rounded-sm">
                <img src="{{ $item['article']['image_url'] }}" alt="{{ $item['article']['title'] }}" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
            </a>
          @endforeach
        @endif
      </div>
    </div>

  </div>
</div>
