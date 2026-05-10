import './styles.css';

const DATA_URL = '/demo-data/posts.json';
const IMAGE_BASE = '/demo-images/';
const PLACEHOLDER_IMAGE = '/demo-images/news-1.jpg';

const categories = [
  ['সর্বশেষ', 'latest'],
  ['বাংলাদেশ', 'bangladesh'],
  ['অর্থনীতি', 'economy'],
  ['আন্তর্জাতিক', 'world'],
  ['সারাদেশ', 'local-news'],
  ['বিনোদন', 'entertainment'],
  ['খেলাধুলা', 'sports'],
  ['প্রযুক্তি', 'technology'],
];

const app = document.getElementById('app');

init();

async function init() {
  try {
    const posts = await fetchPosts();
    renderApp(posts);
    wireInteractions(posts);
  } catch (error) {
    console.error('[DhakaMagazineDemo] Failed to load demo data.', error);
    app.innerHTML = `
      <main class="min-h-screen flex items-center justify-center px-4 text-center">
        <div>
          <h1 class="font-serif text-2xl font-extrabold text-fg">Demo data could not be loaded.</h1>
          <p class="mt-2 text-fg-secondary">Check that <code>public/demo-data/posts.json</code> exists.</p>
        </div>
      </main>
    `;
  }
}

async function fetchPosts() {
  const response = await fetch(DATA_URL);
  if (!response.ok) throw new Error(`HTTP ${response.status}`);
  const posts = await response.json();

  return posts
    .map(normalizePost)
    .sort((a, b) => new Date(b.published_at) - new Date(a.published_at));
}

function normalizePost(post) {
  const imagePath = post.image_path || 'news-1.jpg';

  return {
    ...post,
    image_url: `${IMAGE_BASE}${imagePath}`,
    url: `#post-${post.id}`,
    time_ago: relativeTime(post.published_at),
    display_date: new Intl.DateTimeFormat('bn-BD', { dateStyle: 'medium' }).format(new Date(post.published_at)),
  };
}

function renderApp(posts) {
  const breaking = posts.filter((post) => post.is_breaking).slice(0, 10);
  const featured = posts.find((post) => post.is_featured) || posts[0];
  const sticky = uniquePosts(posts.filter((post) => post.is_sticky), [featured?.id]).slice(0, 6);
  const trending = uniquePosts(posts.filter((post) => post.is_trending), [featured?.id, ...sticky.map((post) => post.id)]).slice(0, 5);
  const editorPicks = uniquePosts(posts.filter((post) => post.is_editor_pick), [...trending.map((post) => post.id)]).slice(0, 3);
  const localNews = posts.filter((post) => post.category === 'local-news').slice(0, 9);
  const allPosts = posts.slice(0, 24);
  const carousel = posts.slice(0, 10);
  const popular = [...posts].sort((a, b) => b.view_count - a.view_count).slice(0, 10);

  app.innerHTML = `
    ${ticker(breaking)}
    ${header()}
    ${nav()}
    <main>
      ${heroSection({ featured, sticky, trending, editorPicks })}
      ${adSlot('homepage-top', '970x90')}
      ${photoCarousel(carousel, posts.slice(0, 8), popular)}
      ${localNewsSection(localNews)}
      ${allPostsSection(allPosts, popular)}
    </main>
    ${footer()}
  `;
}

function ticker(posts) {
  return `
    <section class="border-b border-border bg-surface" id="dms-scroll-nav">
      <div class="mx-auto flex h-9 max-w-screen-xl items-center overflow-hidden px-4">
        <span class="mr-4 flex h-full items-center bg-[#e2231a] px-4 text-sm font-extrabold text-white">সর্বশেষ</span>
        <div class="flex min-w-0 flex-1 gap-8 whitespace-nowrap text-[13px] text-fg-secondary">
          ${posts.map((post) => `<a href="${post.url}" class="hover:text-[#e2231a]">${post.title}</a>`).join('<span class="text-[#e2231a]">•</span>')}
        </div>
      </div>
    </section>
  `;
}

function header() {
  return `
    <header id="site-header" class="bg-bg">
      <div class="mx-auto flex max-w-screen-xl items-center justify-between px-4 py-4">
        <a href="#" class="block w-[160px]" aria-label="Dhaka Magazine">
          <img src="/demo-images/dhaka-magazine-color-logo.svg" alt="Dhaka Magazine" class="h-auto w-full" onerror="this.src='${PLACEHOLDER_IMAGE}'">
        </a>
        <div class="flex items-center gap-3 text-right text-[12px] text-fg-secondary">
          <span>রবিবার, ১০ মে ২০২৬</span>
          <button id="theme-toggle" class="rounded-full border border-border px-3 py-1 text-fg">☼</button>
          <button id="hamburger-btn" class="md:hidden rounded border border-border px-3 py-1">☰</button>
        </div>
      </div>
      <div id="mobile-menu" class="hidden border-t border-border px-4 pb-4 md:hidden">
        ${categories.map(([label]) => `<a href="#" class="block border-b border-border py-3 font-bold">${label}</a>`).join('')}
      </div>
    </header>
  `;
}

function nav() {
  return `
    <div id="site-nav" class="w-full bg-[#1d2640] text-white">
      <nav class="mx-auto hidden h-[48px] max-w-screen-xl items-center justify-between px-4 md:flex">
        <div class="flex items-center">
          ${categories.map(([label]) => `<a href="#" class="inline-flex h-12 items-center px-3 text-[15px] font-semibold hover:bg-white/10 hover:text-[#e2231a]">${label}</a>`).join('')}
        </div>
        <button id="theme-toggle-nav" class="h-9 w-9 rounded-full hover:bg-white/10">☼</button>
      </nav>
    </div>
  `;
}

function heroSection({ featured, sticky, trending, editorPicks }) {
  return `
    <section class="hero-section mx-auto w-full max-w-screen-xl px-4">
      <div class="hidden border-t border-border md:grid md:grid-cols-1 lg:grid-cols-[27%_46%_27%]">
        <div class="order-2 border-border py-4 pr-0 lg:order-1 lg:border-r lg:pr-5">
          ${trending.map((post) => sideItem(post, true)).join('')}
        </div>
        <div class="order-1 border-border py-4 px-0 lg:order-2 lg:border-r lg:px-5">
          ${featured ? heroCard(featured) : ''}
          <div class="grid grid-cols-3 gap-x-4 gap-y-4">
            ${sticky.map(gridMiniCard).join('')}
          </div>
        </div>
        <div class="order-3 py-4 pl-0 lg:pl-5">
          <div class="mb-4 h-[250px] w-full overflow-hidden rounded-lg">
            <img src="/demo-images/coming-soon-ad.webp" alt="Advertisement" class="h-full w-full object-cover" onerror="this.src='${PLACEHOLDER_IMAGE}'">
          </div>
          ${editorPicks.map((post) => sideItem(post, false)).join('')}
        </div>
      </div>
      <div class="grid grid-cols-1 gap-3 border-t border-border md:hidden">
        ${featured ? heroCard(featured) : ''}
        <div class="grid grid-cols-3 gap-3 py-2">${sticky.map(gridMiniCard).join('')}</div>
        <div>${trending.map((post) => mobileSideItem(post)).join('')}</div>
        <div>${editorPicks.map((post) => mobileSideItem(post)).join('')}</div>
      </div>
    </section>
  `;
}

function heroCard(post) {
  return `
    <a href="${post.url}" class="group mb-3 flex flex-col border-b border-border pb-3">
      <div class="mb-2 aspect-[16/9] w-full overflow-hidden rounded-sm">
        ${img(post, 'h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.05]')}
      </div>
      <h2 class="font-serif text-[22px] font-bold leading-[1.25] text-fg transition-colors group-hover:text-[#e2231a]">${post.title}</h2>
      <p class="mt-2 line-clamp-2 text-[13px] leading-relaxed text-fg-secondary">${post.excerpt}</p>
    </a>
  `;
}

function gridMiniCard(post) {
  return `
    <a href="${post.url}" class="group flex flex-col">
      <div class="mb-1.5 aspect-[16/9] w-full overflow-hidden">${img(post, 'h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.03]')}</div>
      <h3 class="line-clamp-2 font-serif text-[13px] font-bold leading-snug text-fg group-hover:text-[#e2231a]">${post.title}</h3>
      <div class="mt-0.5 text-[11px] text-fg-muted">${post.time_ago}</div>
    </a>
  `;
}

function sideItem(post, imageRight) {
  const image = `<div class="mb-2 h-[140px] w-full shrink-0 overflow-hidden rounded-sm md:mb-0 md:h-[50px] md:w-[88px]">${img(post, 'h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.05]')}</div>`;
  const text = `
    <div class="min-w-0 flex-1">
      <span class="mb-0.5 block text-[12px] font-bold text-[#e2231a]">${post.category_bn} •</span>
      <h3 class="line-clamp-2 font-serif text-[15px] font-bold leading-snug text-fg group-hover:text-[#e2231a]">${post.title}</h3>
      <p class="mt-1 line-clamp-2 text-[12px] text-fg-secondary">${post.excerpt}</p>
      <div class="mt-1 text-[11px] text-fg-muted">${post.time_ago}</div>
    </div>
  `;

  return `<a href="${post.url}" class="group flex flex-col-reverse items-start gap-3 border-b border-border py-3 last:border-b-0 md:flex-row">${imageRight ? `${text}${image}` : `${image}${text}`}</a>`;
}

function mobileSideItem(post) {
  return `
    <a href="${post.url}" class="group flex items-start gap-3 border-b border-border py-3 last:border-b-0">
      <div class="min-w-0 flex-1">
        <span class="mb-0.5 block text-[12px] font-bold text-[#e2231a]">${post.category_bn}</span>
        <h3 class="line-clamp-2 font-serif text-[14px] font-bold leading-snug text-fg group-hover:text-[#e2231a]">${post.title}</h3>
      </div>
      <div class="h-[68px] w-[120px] shrink-0 overflow-hidden rounded-sm">${img(post, 'h-full w-full object-cover')}</div>
    </a>
  `;
}

function photoCarousel(carousel, latest, popular) {
  const blockId = 'photo-news-demo';
  const first = carousel[0];
  const previous = carousel[carousel.length - 1] || first;
  const next = carousel[1] || first;

  return `
    <div class="border-t-4 border-border"></div>
    <section class="mx-auto w-full max-w-screen-xl px-4 py-5" id="${blockId}">
      <div class="mb-4 flex items-center gap-3 border-b border-border pb-2">
        <span class="section-icon"></span>
        <h2 class="font-serif text-[20px] font-extrabold text-fg">ফটো সংবাদ</h2>
      </div>
      <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-[1fr_2.2fr_1fr] lg:gap-6">
        <aside class="order-3 flex flex-col lg:order-1">
          <div class="relative flex h-[250px] w-full items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-[#f1f5f9] lg:h-[420px]">
            <span class="text-[13px] lowercase tracking-wide text-gray-400">advertisement</span>
          </div>
        </aside>
        <section class="order-1 flex flex-col lg:order-2">
          <div class="relative h-[350px] w-full overflow-hidden rounded-xl border border-gray-200 bg-white sm:h-[400px] lg:h-[420px]" id="${blockId}-carousel">
            <div class="relative flex h-full w-full items-center justify-center pt-4 pb-10">
              <div class="photo-prev absolute left-[-2%] z-10 aspect-square w-[25%] max-w-[200px] scale-90 cursor-pointer overflow-hidden rounded-2xl opacity-50 blur-[2px] transition-all duration-500 sm:left-[2%] lg:left-[4%]">${img(previous, 'h-full w-full object-cover pointer-events-none')}</div>
              <a href="${first.url}" class="photo-main-link relative z-20 mx-auto block aspect-square w-[52%] max-w-[350px] overflow-hidden rounded-2xl bg-white shadow-2xl transition-transform duration-500 hover:scale-[1.02]">
                ${img(first, 'photo-main-img h-full w-full object-cover object-center bg-[#f3f4f6]')}
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/15 to-transparent"></div>
                <div class="absolute right-0 bottom-0 left-0 p-4">
                  <h3 class="photo-main-title line-clamp-2 font-serif text-[16px] font-extrabold leading-tight text-white drop-shadow">${first.title}</h3>
                  <div class="mt-1.5 text-[11px] text-white/80"><span class="photo-main-time">${first.time_ago}</span></div>
                </div>
              </a>
              <div class="photo-next absolute right-[-2%] z-10 aspect-square w-[25%] max-w-[200px] scale-90 cursor-pointer overflow-hidden rounded-2xl opacity-50 blur-[2px] transition-all duration-500 sm:right-[2%] lg:right-[4%]">${img(next, 'h-full w-full object-cover pointer-events-none')}</div>
              <button type="button" class="photo-btn-prev absolute left-[8%] z-30 flex h-9 w-9 items-center justify-center rounded-full border border-white/40 bg-black/40 text-white shadow-lg transition-colors hover:bg-black/70 sm:left-[10%] sm:h-10 sm:w-10 lg:left-[12%]">‹</button>
              <button type="button" class="photo-btn-next absolute right-[8%] z-30 flex h-9 w-9 items-center justify-center rounded-full border border-white/40 bg-black/40 text-white shadow-lg transition-colors hover:bg-black/70 sm:right-[10%] sm:h-10 sm:w-10 lg:right-[12%]">›</button>
            </div>
            <div class="photo-dots absolute right-0 bottom-5 left-0 z-50 flex justify-center gap-2">
              ${carousel.map((_, index) => `<button type="button" class="h-2.5 w-2.5 rounded-full transition-all duration-300 ${index === 0 ? 'bg-black' : 'bg-gray-300 hover:bg-gray-400'}" data-index="${index}"></button>`).join('')}
            </div>
          </div>
        </section>
        <aside class="order-2 flex flex-col overflow-hidden rounded-sm border border-gray-200 bg-white lg:order-3 lg:h-[420px]">
          <div class="flex border-b border-gray-200 bg-[#f8fafc]">
            <button type="button" class="photo-tab-btn flex flex-1 items-center justify-center gap-2 border-b-[2.5px] border-[#e2231a] py-3 font-extrabold text-fg" data-tab="latest"><span class="font-serif text-[15px]">সর্বশেষ সংবাদ</span></button>
            <button type="button" class="photo-tab-btn flex flex-1 items-center justify-center gap-2 border-b-[2.5px] border-transparent py-3 font-extrabold text-fg-muted hover:text-fg" data-tab="popular"><span class="font-serif text-[15px]">সর্বাধিক পঠিত</span></button>
          </div>
          <div class="photo-tab-latest custom-scrollbar h-[320px] overflow-y-auto px-2 py-1 lg:h-[376px]">${rankedList(latest)}</div>
          <div class="photo-tab-popular custom-scrollbar hidden h-[320px] overflow-y-auto px-2 py-1 lg:h-[376px]">${rankedList(popular)}</div>
        </aside>
      </div>
      <script type="application/json" id="${blockId}-data">${escapeJson(carousel)}</script>
    </section>
  `;
}

function rankedList(posts) {
  const numerals = ['১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '১০'];
  return posts.map((post, index) => `
    <a href="${post.url}" class="group flex items-start gap-4 border-b border-gray-100 px-3 py-3.5 transition-colors last:border-b-0 hover:bg-gray-50">
      <span class="mt-1 w-8 shrink-0 text-center font-serif text-[32px] font-extrabold leading-none text-[#fca5a5] transition-colors group-hover:text-[#e2231a]">${numerals[index] || index + 1}</span>
      <div class="min-w-0 flex-1">
        <h3 class="line-clamp-2 font-serif text-[14.5px] font-bold leading-snug text-gray-800 transition-colors group-hover:text-[#e2231a]">${post.title}</h3>
        <div class="mt-1.5 flex items-center gap-1 text-[11px] text-gray-500"><span class="truncate">${post.time_ago}</span></div>
      </div>
    </a>
  `).join('');
}

function localNewsSection(posts) {
  const left = posts.slice(0, 2);
  const hero = posts[2];
  const right = posts.slice(3, 9);

  return `
    <div class="border-t-4 border-border"></div>
    <section class="mx-auto w-full max-w-screen-xl px-4 py-5">
      <div class="mb-4 flex items-center justify-between border-b border-border pb-2">
        <div class="flex items-center gap-3"><span class="section-icon"></span><h2 class="font-serif text-[20px] font-extrabold leading-none text-fg">সারাদেশ</h2></div>
        <a href="#" class="text-[13px] text-fg-secondary hover:text-[#e2231a]">আরও ›</a>
      </div>
      <div class="grid grid-cols-1 gap-0 divide-border md:grid-cols-[1fr_2.2fr_1.3fr] md:divide-x">
        <div class="flex flex-col justify-between gap-5 pr-0 md:pr-5">${left.map(localCard).join('')}</div>
        <div class="px-0 py-5 md:px-5 md:py-0">${hero ? heroLocalCard(hero) : ''}</div>
        <div class="flex flex-col divide-y divide-border pl-0 md:pl-5">${right.map(localListItem).join('')}</div>
      </div>
    </section>
  `;
}

function localCard(post) {
  return `<a href="${post.url}" class="group flex flex-col"><div class="mb-2 aspect-[16/9] w-full overflow-hidden">${img(post, 'h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.03]')}</div><h3 class="line-clamp-3 font-serif text-[15px] font-bold leading-snug text-fg group-hover:text-[#e2231a]">${post.title}</h3></a>`;
}

function heroLocalCard(post) {
  return `<a href="${post.url}" class="group flex flex-col"><div class="mb-3 aspect-[16/9] w-full overflow-hidden">${img(post, 'h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.03]')}</div><h3 class="mb-2 line-clamp-2 font-serif text-[21px] font-extrabold leading-snug text-fg group-hover:text-[#e2231a]">${post.title}</h3><p class="line-clamp-2 text-[13px] leading-relaxed text-fg-secondary">${post.excerpt}</p></a>`;
}

function localListItem(post) {
  return `<a href="${post.url}" class="group flex items-start gap-3 py-3 first:pt-0 last:pb-0"><h3 class="line-clamp-3 flex-1 font-serif text-[14px] font-bold leading-snug text-fg group-hover:text-[#e2231a]">${post.title}</h3><div class="h-[38px] w-[68px] shrink-0 overflow-hidden">${img(post, 'h-full w-full object-cover')}</div></a>`;
}

function allPostsSection(posts, popular) {
  return `
    <div class="border-t-4 border-border"></div>
    <section class="mx-auto grid w-full max-w-screen-xl grid-cols-1 gap-8 px-4 py-5 lg:grid-cols-12">
      <div class="min-w-0 lg:col-span-8">
        <div class="mb-4 flex items-center gap-3 border-b border-border pb-2">
          <span class="section-icon"></span>
          <h2 class="font-serif text-[20px] font-extrabold text-fg">All Posts</h2>
        </div>
        <div class="grid min-w-0 grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
          ${posts.map(newsCard).join('')}
        </div>
        ${adSlot('category-bottom', '728x90', 'mt-8')}
      </div>
      <aside class="min-w-0 lg:col-span-4">
        <div class="space-y-6 lg:sticky lg:top-20">
          ${mostRead(popular.slice(0, 5))}
          ${adSlot('sidebar-rectangle-1', '300x250')}
          ${adSlot('sidebar-half-page', '300x600')}
        </div>
      </aside>
    </section>
  `;
}

function newsCard(post) {
  return `
    <article class="group h-full min-w-0">
      <a href="${post.url}" class="flex h-full min-w-0 flex-col overflow-hidden rounded-[8px] border border-border bg-bg shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-lg">
        <div class="relative aspect-[16/10] overflow-hidden bg-surface">${img(post, 'h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.04]')}<span class="absolute top-3 left-3 bg-[#e2231a] px-2 py-1 text-[11px] font-bold leading-none text-white shadow-sm">${post.category_bn}</span></div>
        <div class="flex min-w-0 flex-1 flex-col p-4">
          <h3 class="line-clamp-2 break-words font-serif text-[18px] font-bold leading-snug text-fg transition-colors group-hover:text-[#e2231a]">${post.title}</h3>
          <p class="mt-2 line-clamp-2 break-words text-[14px] leading-relaxed text-fg-secondary">${post.excerpt}</p>
          <div class="mt-auto flex flex-wrap items-center gap-x-2 gap-y-1 pt-3 text-[12px] text-fg-muted"><time>${post.time_ago}</time><span>${formatNumber(post.view_count)} বার পড়া</span></div>
        </div>
      </a>
    </article>
  `;
}

function mostRead(posts) {
  return `
    <section class="border border-border bg-bg p-4">
      <h2 class="mb-3 border-b border-border pb-2 font-serif text-[18px] font-extrabold">সর্বাধিক পঠিত</h2>
      <div class="divide-y divide-border">${posts.map((post, index) => `<a href="${post.url}" class="flex gap-3 py-3"><span class="font-serif text-2xl font-extrabold text-[#e2231a]">${index + 1}</span><h3 class="line-clamp-2 font-serif text-[15px] font-bold leading-snug hover:text-[#e2231a]">${post.title}</h3></a>`).join('')}</div>
    </section>
  `;
}

function adSlot(name, size, extraClass = '') {
  const [width, height] = size.split('x');
  return `<div class="ad-slot ${extraClass}" data-ad-slot="${name}" style="--ad-width:${width}px;--ad-height:${height}px"><span class="ad-slot__label">advertisement</span><div class="ad-slot__box">${size}</div></div>`;
}

function footer() {
  return `<footer class="border-t border-border bg-[#1d2640] px-4 py-8 text-center text-sm text-white/70">Dhaka Magazine Frontend Demo · JSON powered static UI</footer>`;
}

function img(post, className) {
  return `<img src="${post.image_url}" alt="${post.title}" loading="lazy" class="${className}" onerror="this.onerror=null;this.src='${PLACEHOLDER_IMAGE}'">`;
}

function uniquePosts(posts, excludedIds = []) {
  const seen = new Set(excludedIds.filter(Boolean));
  return posts.filter((post) => {
    if (seen.has(post.id)) return false;
    seen.add(post.id);
    return true;
  });
}

function wireInteractions(posts) {
  const hamburgerBtn = document.getElementById('hamburger-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  hamburgerBtn?.addEventListener('click', () => mobileMenu?.classList.toggle('hidden'));

  const toggleTheme = () => {
    document.documentElement.classList.toggle('dark');
    localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
  };
  if (localStorage.getItem('theme') === 'dark') document.documentElement.classList.add('dark');
  document.getElementById('theme-toggle')?.addEventListener('click', toggleTheme);
  document.getElementById('theme-toggle-nav')?.addEventListener('click', toggleTheme);

  const siteNav = document.getElementById('site-nav');
  window.addEventListener('scroll', () => siteNav?.classList.toggle('sticky', window.scrollY > 120), { passive: true });

  wirePhotoCarousel(posts.slice(0, 10));
  wirePhotoTabs();
}

function wirePhotoCarousel(slides) {
  const block = document.getElementById('photo-news-demo');
  if (!block || !slides.length) return;

  const mainLink = block.querySelector('.photo-main-link');
  const mainImg = block.querySelector('.photo-main-img');
  const mainTitle = block.querySelector('.photo-main-title');
  const mainTime = block.querySelector('.photo-main-time');
  const prevWrap = block.querySelector('.photo-prev');
  const nextWrap = block.querySelector('.photo-next');
  const dots = Array.from(block.querySelectorAll('.photo-dots button'));
  let index = 0;

  function render(nextIndex) {
    index = (nextIndex + slides.length) % slides.length;
    const current = slides[index];
    const previous = slides[(index - 1 + slides.length) % slides.length];
    const next = slides[(index + 1) % slides.length];

    mainLink.href = current.url;
    mainImg.src = current.image_url;
    mainImg.alt = current.title;
    mainTitle.textContent = current.title;
    mainTime.textContent = current.time_ago;
    prevWrap.innerHTML = img(previous, 'h-full w-full object-cover pointer-events-none');
    nextWrap.innerHTML = img(next, 'h-full w-full object-cover pointer-events-none');
    dots.forEach((dot, dotIndex) => {
      dot.classList.toggle('bg-black', dotIndex === index);
      dot.classList.toggle('bg-gray-300', dotIndex !== index);
    });
  }

  block.querySelector('.photo-btn-prev')?.addEventListener('click', () => render(index - 1));
  block.querySelector('.photo-btn-next')?.addEventListener('click', () => render(index + 1));
  prevWrap?.addEventListener('click', () => render(index - 1));
  nextWrap?.addEventListener('click', () => render(index + 1));
  dots.forEach((dot) => dot.addEventListener('click', () => render(Number(dot.dataset.index || 0))));
}

function wirePhotoTabs() {
  const latestTab = document.querySelector('.photo-tab-latest');
  const popularTab = document.querySelector('.photo-tab-popular');
  document.querySelectorAll('.photo-tab-btn').forEach((button) => {
    button.addEventListener('click', () => {
      const isPopular = button.dataset.tab === 'popular';
      latestTab?.classList.toggle('hidden', isPopular);
      popularTab?.classList.toggle('hidden', !isPopular);
      document.querySelectorAll('.photo-tab-btn').forEach((item) => {
        const active = item === button;
        item.classList.toggle('border-[#e2231a]', active);
        item.classList.toggle('text-fg', active);
        item.classList.toggle('border-transparent', !active);
        item.classList.toggle('text-fg-muted', !active);
      });
    });
  });
}

function relativeTime(value) {
  const diffHours = Math.max(1, Math.round((Date.now() - new Date(value).getTime()) / 36e5));
  if (diffHours < 24) return `${toBanglaNumber(diffHours)} ঘণ্টা আগে`;
  return `${toBanglaNumber(Math.round(diffHours / 24))} দিন আগে`;
}

function toBanglaNumber(value) {
  return String(value).replace(/\d/g, (digit) => '০১২৩৪৫৬৭৮৯'[Number(digit)]);
}

function formatNumber(value) {
  return new Intl.NumberFormat('bn-BD').format(value || 0);
}

function escapeJson(value) {
  return JSON.stringify(value).replace(/</g, '\\u003c');
}
