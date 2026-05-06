@props(['carouselArticles' => [], 'latestArticles' => [], 'popularArticles' => []])

@php
    $blockId = 'photo-news-' . Str::random(6);
    $fallback = ['a.jpg', 'b.jpg', 'c.jpg', 'd.jpg', 'e.jpg', 'f.jpg', 'g.jpg', 'h.jpg', 'i.jpg', 'j.jpg'];

    $carousel = collect($carouselArticles)->values()->map(function ($item, $i) use ($fallback) {
        $img = $item['image_url'] ?? asset('images/' . $fallback[$i % count($fallback)]);
        return [
            'headline' => $item['headline'] ?? $item['title'] ?? 'ডেমো ছবির খবর',
            'slug' => $item['slug'] ?? '#',
            'timestamp' => $item['timestamp'] ?? $item['time_ago'] ?? '১ ঘণ্টা আগে',
            'image_url' => $img,
        ];
    })->all();

    if (empty($carousel)) {
        $carousel = collect($fallback)->take(5)->map(fn($img, $i) => [
            'headline' => 'ডেমো ছবির খবর ' . ($i + 1),
            'slug' => '#',
            'timestamp' => ($i + 1) . ' ঘণ্টা আগে',
            'image_url' => asset('images/' . $img),
        ])->all();
    }

    $latest = collect($latestArticles)->values()->map(function ($item) {
        return [
            'headline' => $item['headline'] ?? $item['title'] ?? '',
            'slug' => $item['slug'] ?? '#',
            'timestamp' => $item['timestamp'] ?? $item['time_ago'] ?? '১ ঘণ্টা আগে',
        ];
    })->all();

    $popular = collect($popularArticles)->values()->map(function ($item) {
        return [
            'headline' => $item['headline'] ?? $item['title'] ?? '',
            'slug' => $item['slug'] ?? '#',
            'timestamp' => $item['timestamp'] ?? $item['time_ago'] ?? '১ ঘণ্টা আগে',
        ];
    })->all();
@endphp

<div class="border-t-4 border-border"></div>

<div class="w-full max-w-screen-xl mx-auto px-4 py-5" id="{{ $blockId }}">
  <div class="flex items-center gap-3 mb-4 border-b border-border pb-2">
    <span class="section-icon"></span>
    <h2 class="font-serif font-extrabold text-[20px] text-fg">ছবির খবর</h2>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-[1fr_2.2fr_1fr] gap-4 lg:gap-6 items-start">
    <aside class="order-3 lg:order-1 flex flex-col">
      <div class="w-full bg-[#f1f5f9] border border-gray-200 flex flex-col items-center justify-center h-[250px] lg:h-[420px] rounded-xl relative overflow-hidden">
        <span class="text-gray-400 text-[13px] tracking-wide lowercase">advertisement</span>
      </div>
    </aside>

    <section class="order-1 lg:order-2 flex flex-col">
      <div class="relative w-full h-[350px] sm:h-[400px] lg:h-[420px] bg-white border border-gray-200 rounded-xl overflow-hidden" id="{{ $blockId }}-carousel">
        <div class="relative w-full h-full flex items-center justify-center pt-4 pb-10">
          
          <div class="absolute left-[-2%] sm:left-[2%] lg:left-[4%] opacity-50 blur-[2px] scale-90 cursor-pointer transition-all duration-500 rounded-2xl overflow-hidden z-10 shadow-md photo-carousel-prev-preview photo-prev" style="width: 25%; max-width: 200px; aspect-ratio: 1 / 1; background-size: cover; background-position: center; background-image: url('{{ $carousel[count($carousel)-1]['image_url'] ?? asset('images/a.jpg') }}');"></div>

          <a href="{{ ($carousel[0]['slug'] ?? '#') === '#' ? '#' : route('article.show', $carousel[0]['slug']) }}" class="relative z-20 shadow-2xl rounded-2xl overflow-hidden bg-white block mx-auto transition-transform duration-500 hover:scale-[1.02] photo-carousel-link photo-main-link" style="width: 52%; max-width: 350px; aspect-ratio: 1 / 1;">
            <img src="{{ $carousel[0]['image_url'] }}" alt="{{ $carousel[0]['headline'] }}" class="w-full h-full object-cover object-center bg-[#f3f4f6] photo-carousel-main-image photo-main-img" loading="lazy" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/15 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-4">
              <h3 class="font-serif font-extrabold text-white text-[16px] leading-tight line-clamp-2 drop-shadow photo-main-title">{{ $carousel[0]['headline'] }}</h3>
              <div class="text-[11px] text-white/80 mt-1.5 photo-main-time"><span>{{ $carousel[0]['timestamp'] }}</span></div>
            </div>
          </a>

          <div class="absolute right-[-2%] sm:right-[2%] lg:right-[4%] opacity-50 blur-[2px] scale-90 cursor-pointer transition-all duration-500 rounded-2xl overflow-hidden z-10 shadow-md photo-carousel-next-preview photo-next" style="width: 25%; max-width: 200px; aspect-ratio: 1 / 1; background-size: cover; background-position: center; background-image: url('{{ $carousel[1]['image_url'] ?? asset('images/b.jpg') }}');"></div>

          <button type="button" class="absolute left-[8%] sm:left-[10%] lg:left-[12%] z-30 bg-black/40 hover:bg-black/70 text-white w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center transition-colors shadow-lg border border-white/40 photo-carousel-prev photo-btn-prev">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
          </button>
          <button type="button" class="absolute right-[8%] sm:right-[10%] lg:right-[12%] z-30 bg-black/40 hover:bg-black/70 text-white w-9 h-9 sm:w-10 sm:h-10 rounded-full flex items-center justify-center transition-colors shadow-lg border border-white/40 photo-carousel-next photo-btn-next">
            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
          </button>
        </div>

        <div class="absolute bottom-5 left-0 right-0 flex justify-center gap-2 z-50 photo-carousel-indicators photo-dots">
          @foreach($carousel as $i => $item)
            <button type="button" class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-black' : 'bg-gray-300 hover:bg-gray-400' }}" data-index="{{ $i }}"></button>
          @endforeach
        </div>
      </div>
    </section>

    <aside class="order-2 lg:order-3 flex flex-col border border-gray-200 rounded-sm bg-white overflow-hidden lg:h-[420px]">
      <div class="flex border-b border-gray-200 bg-[#f8fafc]">
        <button type="button" class="flex-1 flex items-center justify-center gap-2 py-3 border-b-[2.5px] border-[#e2231a] text-fg font-extrabold photo-tab-btn" data-tab="latest">
            <div class="w-4 h-4 bg-[#e2231a] rounded-full flex items-center justify-center text-white shrink-0">
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </div>
            <span class="font-serif text-[15px]">সর্বশেষ সংবাদ</span>
        </button>
        <button type="button" class="flex-1 flex items-center justify-center gap-2 py-3 border-b-[2.5px] border-transparent text-fg-muted hover:text-fg font-extrabold photo-tab-btn" data-tab="popular">
            <div class="w-4 h-4 bg-[#e2231a] rounded-full flex items-center justify-center text-white shrink-0">
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
            </div>
            <span class="font-serif text-[15px]">সর্বাধিক পঠিত</span>
        </button>
      </div>

      <div class="h-[320px] lg:h-[376px] overflow-y-auto custom-scrollbar px-2 py-1 photo-tab-latest">
        @foreach($latest as $idx => $item)
          <a href="{{ ($item['slug'] ?? '#') === '#' ? '#' : route('article.show', $item['slug']) }}" class="group flex items-start gap-4 py-3.5 px-3 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">
            <span class="font-serif font-extrabold text-[32px] text-[#fca5a5] group-hover:text-[#e2231a] transition-colors shrink-0 w-8 text-center leading-none mt-1">{{ ['১','২','৩','৪','৫','৬','৭','৮','৯','১০'][$idx] ?? $idx + 1 }}</span>
            <div class="flex-1 min-w-0">
              <h3 class="font-serif font-bold text-[14.5px] text-gray-800 leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">{{ $item['headline'] }}</h3>
              <div class="text-[11px] text-gray-500 mt-1.5 flex items-center gap-1">
                 <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                 <span class="truncate">{{ $item['timestamp'] }}</span>
              </div>
            </div>
          </a>
        @endforeach
      </div>

      <div class="h-[320px] lg:h-[376px] overflow-y-auto custom-scrollbar px-2 py-1 hidden photo-tab-popular">
        @foreach($popular as $idx => $item)
          <a href="{{ ($item['slug'] ?? '#') === '#' ? '#' : route('article.show', $item['slug']) }}" class="group flex items-start gap-4 py-3.5 px-3 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">
            <span class="font-serif font-extrabold text-[32px] text-[#fca5a5] group-hover:text-[#e2231a] transition-colors shrink-0 w-8 text-center leading-none mt-1">{{ ['১','২','৩','৪','৫','৬','৭','৮','৯','১০'][$idx] ?? $idx + 1 }}</span>
            <div class="flex-1 min-w-0">
              <h3 class="font-serif font-bold text-[14.5px] text-gray-800 leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">{{ $item['headline'] }}</h3>
              <div class="text-[11px] text-gray-500 mt-1.5 flex items-center gap-1">
                 <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                 <span class="truncate">{{ $item['timestamp'] }}</span>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </aside>
  </div>

  <script type="application/json" id="{{ $blockId }}-data">@json($carousel)</script>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const block = document.getElementById('{{ $blockId }}');
  if (!block) return;

  const dataEl = document.getElementById('{{ $blockId }}-data');
  const slides = dataEl ? JSON.parse(dataEl.textContent || '[]') : [];
  if (!slides.length) return;

  const mainLink = block.querySelector('.photo-main-link');
  const mainImg = block.querySelector('.photo-main-img');
  const mainTitle = block.querySelector('.photo-main-title');
  const mainTime = block.querySelector('.photo-main-time span');
  const prevWrap = block.querySelector('.photo-prev');
  const nextWrap = block.querySelector('.photo-next');
  const dots = Array.from(block.querySelectorAll('.photo-dots button'));

  let idx = 0;
  function articleUrl(slug) {
    if (!slug || slug === '#') return '#';
    return '{{ url('/article') }}/' + slug;
  }

  function render(i) {
    idx = (i + slides.length) % slides.length;
    const cur = slides[idx];
    const prev = slides[(idx - 1 + slides.length) % slides.length];
    const next = slides[(idx + 1) % slides.length];

    mainLink.setAttribute('href', articleUrl(cur.slug));
    mainImg.setAttribute('src', cur.image_url);
    mainImg.setAttribute('alt', cur.headline);
    mainTitle.textContent = cur.headline;
    mainTime.textContent = cur.timestamp;

    prevWrap.innerHTML = '<img src="' + prev.image_url + '" class="w-full h-full object-cover" alt="Previous">';
    nextWrap.innerHTML = '<img src="' + next.image_url + '" class="w-full h-full object-cover" alt="Next">';

    dots.forEach((d, di) => {
      d.classList.toggle('bg-black', di === idx);
      d.classList.toggle('bg-gray-300', di !== idx);
    });
  }

  block.querySelector('.photo-btn-prev').addEventListener('click', () => render(idx - 1));
  block.querySelector('.photo-btn-next').addEventListener('click', () => render(idx + 1));
  prevWrap.addEventListener('click', () => render(idx - 1));
  nextWrap.addEventListener('click', () => render(idx + 1));
  dots.forEach((d) => d.addEventListener('click', () => render(parseInt(d.dataset.index, 10) || 0)));

  const latestTab = block.querySelector('.photo-tab-latest');
  const popularTab = block.querySelector('.photo-tab-popular');
  const tabBtns = block.querySelectorAll('.photo-tab-btn');
  tabBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
      const isPopular = btn.dataset.tab === 'popular';
      latestTab.classList.toggle('hidden', isPopular);
      popularTab.classList.toggle('hidden', !isPopular);
      tabBtns.forEach((b) => {
        const active = b === btn;
        b.classList.toggle('border-[#e2231a]', active);
        b.classList.toggle('text-fg', active);
        b.classList.toggle('border-transparent', !active);
        b.classList.toggle('text-fg-muted', !active);
      });
    });
  });

  render(0);
});
</script>
@endpush
