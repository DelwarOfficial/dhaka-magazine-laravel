@extends('layouts.app')

@section('title', $metaTitle ?? ($categoryName . ' সংবাদ | Dhaka Magazine'))
@section('meta_description', $metaDescription ?? '')

@section('content')
  <main class="flex-1 container mx-auto px-4 py-8">

    @if(!empty($breadcrumbs))
      <nav class="mb-4 text-[13px] text-fg-secondary" aria-label="Breadcrumb">
        @foreach($breadcrumbs as $index => $crumb)
          @if($index > 0)
            <span class="mx-2">/</span>
          @endif
          @if($index < count($breadcrumbs) - 1)
            <a href="{{ $crumb['url'] }}" class="hover:text-[#e2231a] transition-colors">{{ $crumb['title'] }}</a>
          @else
            <span class="text-fg">{{ $crumb['title'] }}</span>
          @endif
        @endforeach
      </nav>
    @endif

    <div class="mb-8 border-b-2 border-border pb-2 inline-block">
      <h1 class="text-[32px] md:text-[40px] font-serif font-bold text-fg">
        {{ $categoryName }}
      </h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

      <div class="lg:col-span-8">

        @if(count($categoryArticles) > 0)
          <div class="space-y-8">

            <div class="border-b border-border pb-8">
              @php $featured = $categoryArticles[0]; @endphp
              <a href="{{ route('article.show', $featured['slug']) }}" class="group flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <div class="w-full sm:w-[35%] aspect-[16/9] shrink-0 overflow-hidden">
                  <img src="{{ $featured['image_url'] }}" alt="{{ $featured['title'] }}" loading="lazy"
                       class="w-full h-full object-cover transition-transform duration-400 ease-out group-hover:scale-[1.03]">
                </div>
                <div class="flex-1 flex flex-col justify-start">
                  <span class="text-[#e2231a] font-bold text-[12px] uppercase mb-1 block">{{ $featured['category'] }}</span>
                  <h3 class="font-serif font-bold text-[20px] md:text-[24px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors mb-2">
                    {{ $featured['title'] }}
                  </h3>
                  @if(!empty($featured['excerpt']))
                    <p class="text-fg-secondary line-clamp-2 text-[14px] mb-2 leading-relaxed">
                      {{ $featured['excerpt'] }}
                    </p>
                  @endif
                  <div class="text-[12px] text-gray-500 mt-auto">
                    {{ $featured['author'] ?? '' }}{{ !empty($featured['author']) ? ' • ' : '' }}{{ $featured['date'] ?? '' }}
                  </div>
                </div>
              </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              @foreach(array_slice($categoryArticles, 1) as $idx => $article)
                <div class="{{ $idx < count(array_slice($categoryArticles, 1)) - 3 ? 'border-b border-border pb-6' : '' }}">
                  <a href="{{ route('article.show', $article['slug']) }}" class="group flex flex-col">
                    <div class="w-full aspect-[16/9] overflow-hidden relative mb-3">
                      <img src="{{ $article['image_url'] }}" alt="{{ $article['title'] }}" loading="lazy"
                           class="w-full h-full object-cover transition-transform duration-400 ease-out group-hover:scale-[1.03]">
                      <div class="absolute bottom-0 left-0 bg-[#e2231a] text-white text-[11px] font-bold px-2 py-[2px] uppercase">
                        {{ $article['category'] }}
                      </div>
                    </div>
                    <div>
                      <h3 class="font-serif font-bold text-[18px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors mb-2 line-clamp-2">
                        {{ $article['title'] }}
                      </h3>
                      @if(!empty($article['excerpt']))
                        <p class="text-fg-secondary line-clamp-2 text-[14px] mb-2 leading-relaxed">
                          {{ $article['excerpt'] }}
                        </p>
                      @endif
                      <div class="text-[12px] text-gray-500 mt-1">{{ $article['date'] ?? '' }}</div>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>

            @if(count($categoryArticles) === 1)
              <div class="text-center text-gray-500 py-10 bg-surface mt-8 text-[15px]">
                এই বিভাগে আর কোনো সংবাদ নেই
              </div>
            @endif

            @if(count($categoryArticles) > 3)
              <div class="mt-10 text-center border-t border-border pt-8">
                <button class="bg-white border border-border text-fg font-bold py-2 px-6 hover:bg-[#111] hover:text-white transition-colors uppercase text-[14px]">
                  আরও সংবাদ
                </button>
              </div>
            @endif
          </div>
        @else
          <div class="text-center py-16 bg-surface border border-border">
            <h3 class="text-[20px] font-serif text-gray-500">এই বিভাগে এখনো কোনো সংবাদ প্রকাশিত হয়নি।</h3>
          </div>
        @endif

      </div>

      <div class="lg:col-span-4">
        @include('partials.sidebar', ['popularNews' => $popularNews ?? []])
      </div>

    </div>
  </main>
@endsection
