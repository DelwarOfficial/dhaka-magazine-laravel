<?php $__env->startSection('title', $article['title'] . ' - ঢাকা ম্যাগাজিন'); ?>

<?php $__env->startSection('content'); ?>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

      
      <article class="lg:col-span-8">

        
        <div class="mb-4">
          <a href="<?php echo e(route('category.show', $article['category'])); ?>"
             class="text-[14px] font-bold text-[#e2231a] hover:underline font-serif">
            <?php echo e($article['category']); ?>

          </a>
        </div>

        
        <h1 class="text-[28px] md:text-[36px] lg:text-[42px] font-serif font-bold leading-[1.3] text-fg mb-4">
          <?php echo e($article['title']); ?>

        </h1>

        
        <div class="flex flex-col md:flex-row md:items-center justify-between border-t border-border border-b py-3 mb-6">
          <div class="flex flex-col mb-3 md:mb-0">
            <div class="flex items-center gap-2">
              <span class="text-[14px] font-bold text-fg"><?php echo e($article['author'] ?? 'নিজস্ব প্রতিবেদক'); ?></span>
              <?php if(!empty($article['location'])): ?>
                <span class="text-[14px] text-fg-secondary">· <?php echo e($article['location']); ?></span>
              <?php endif; ?>
            </div>
            <div class="text-[13px] text-gray-500 mt-1">
              প্রকাশ: <?php echo e($article['date'] ?? ''); ?>

            </div>
          </div>

          
          <div class="flex items-center gap-2">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(url()->current())); ?>"
               target="_blank"
               class="w-9 h-9 rounded-full bg-surface flex items-center justify-center text-fg-secondary hover:bg-[#e2231a] hover:text-white transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(url()->current())); ?>&text=<?php echo e(urlencode($article['title'])); ?>"
               target="_blank"
               class="w-9 h-9 rounded-full bg-surface flex items-center justify-center text-fg-secondary hover:bg-[#e2231a] hover:text-white transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
            </a>
            <button onclick="navigator.clipboard.writeText('<?php echo e(url()->current()); ?>')"
                    class="w-9 h-9 rounded-full bg-surface flex items-center justify-center text-fg-secondary hover:bg-[#e2231a] hover:text-white transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            </button>
            <button onclick="window.print()"
                    class="w-9 h-9 rounded-full bg-surface flex items-center justify-center text-fg-secondary hover:bg-[#e2231a] hover:text-white transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="12" x="6" y="14"/></svg>
            </button>
          </div>
        </div>

        
        <?php if(!empty($article['image_url'])): ?>
          <figure class="mb-6">
            <div class="w-full overflow-hidden">
              <img src="<?php echo e($article['image_url']); ?>"
                   alt="<?php echo e($article['title']); ?>"
                   class="w-full h-auto object-cover">
            </div>
            <figcaption class="text-[12px] text-gray-500 mt-2 font-ui">
              ছবি: সংগৃহীত
            </figcaption>
          </figure>
        <?php endif; ?>

        
        <div class="prose max-w-none font-serif text-fg-secondary">
          <?php if(!empty($article['excerpt'])): ?>
            <p class="text-[18px] font-bold leading-[1.7] text-fg mb-5">
              <?php echo e($article['excerpt']); ?>

            </p>
          <?php endif; ?>

          <?php if(isset($article['body']) && is_array($article['body'])): ?>
            <?php $__currentLoopData = $article['body']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paragraph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <p class="text-[18px] leading-[1.8] mb-5 text-fg-secondary">
                <?php echo e($paragraph); ?>

              </p>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </div>

        
        <div class="bg-surface border border-border rounded-md p-4 my-8 flex items-center gap-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#e2231a" stroke-width="2" class="shrink-0">
            <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
          </svg>
          <a href="#" class="text-[14px] text-fg-secondary hover:text-[#e2231a] transition-colors font-serif">
            <span class="font-bold">ঢাকা ম্যাগাজিনের</span> খবর পেতে গুগল নিউজ চ্যানেল ফলো করুন
          </a>
        </div>

        
        <?php if(!empty($relatedArticles)): ?>
          <section class="border-t-2 border-[#111] pt-5 mb-8">
            <div class="flex items-center gap-2 mb-4">
              <a href="<?php echo e(route('category.show', $article['category'])); ?>" class="text-[#e2231a] text-[14px] font-bold hover:underline">
                <?php echo e($article['category']); ?>

              </a>
              <span class="text-[14px] text-fg-secondary">থেকে আরও পড়ুন</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
              <?php $__currentLoopData = $relatedArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('article.show', $related['slug'])); ?>" class="group flex flex-col">
                  <?php if(!empty($related['image_url'])): ?>
                    <div class="w-full aspect-[16/9] overflow-hidden mb-2">
                      <img src="<?php echo e($related['image_url']); ?>"
                           alt="<?php echo e($related['title']); ?>"
                           loading="lazy"
                           class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
                    </div>
                  <?php endif; ?>
                  <h3 class="font-serif font-bold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-3">
                    <?php echo e($related['title']); ?>

                  </h3>
                  <div class="text-[11px] text-fg-muted mt-1"><?php echo e($related['time_ago'] ?? ''); ?></div>
                </a>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </section>
        <?php endif; ?>

        
        <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-border">
          <span class="text-[13px] font-bold text-fg mr-1">বিষয়:</span>
          <span class="bg-surface px-3 py-1 text-[13px] text-fg-secondary rounded-sm hover:bg-surface transition-colors cursor-pointer">
            <?php echo e($article['category']); ?>

          </span>
          <span class="bg-surface px-3 py-1 text-[13px] text-fg-secondary rounded-sm hover:bg-surface transition-colors cursor-pointer">
            বাংলাদেশ
          </span>
        </div>

      </article>

      
      <aside class="lg:col-span-4">
        <?php echo $__env->make('partials.sidebar', ['popularNews' => $popularNews ?? []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      </aside>

    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\websie\dhaka-magazine-laravel\resources\views/pages/article.blade.php ENDPATH**/ ?>