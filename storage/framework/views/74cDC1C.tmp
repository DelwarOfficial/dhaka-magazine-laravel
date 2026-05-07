<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['sportsArticles' => [], 'sportsSubcatArticles' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['sportsArticles' => [], 'sportsSubcatArticles' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="w-full max-w-screen-xl mx-auto px-4 py-5">
  <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.5fr_1fr] gap-6 lg:gap-8 lg:divide-x divide-border">

    
    <div class="lg:pr-2 pt-1 order-2 lg:order-1">
      <div class="flex mb-3">
        <button class="font-serif text-[14px] mr-5 pb-2 border-b-2 border-[#e2231a] text-fg font-extrabold transition-colors">পঠিত</button>
        <button class="font-serif text-[14px] mr-5 pb-2 border-b-2 border-transparent text-fg-muted hover:text-fg transition-colors">আলোচিত</button>
      </div>
      <?php if(isset($sportsArticles)): ?>
        <?php $__currentLoopData = array_slice($sportsArticles, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $bengaliNumbers = ['১','২','৩','৪','৫','৬','৭','৮','৯','১০'];
            $number = $bengaliNumbers[$i] ?? ($i + 1);
          ?>
          <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
            class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
            <span class="font-serif font-bold text-[34px] text-fg-muted shrink-0 w-8 text-center leading-none mt-0.5">
              <?php echo e($number); ?>

            </span>
            <div class="flex-1 pt-0.5">
              <h3 class="font-serif font-extrabold text-[14px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-3">
                <?php echo e($a['title']); ?>

              </h3>
              <div class="text-[11px] text-fg-muted mt-1"><?php echo e($a['time_ago']); ?></div>
            </div>
          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </div>

    
    <div class="lg:px-6 py-1 order-1 lg:order-2 border-b lg:border-b-0 border-border pb-6 lg:pb-0">
      <?php if (isset($component)) { $__componentOriginal436399e29d00ce6b8f47e38277d39536 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal436399e29d00ce6b8f47e38277d39536 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-header','data' => ['title' => 'খেলা','moreUrl' => route('category.show', 'খেলা')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'খেলা','moreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('category.show', 'খেলা'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal436399e29d00ce6b8f47e38277d39536)): ?>
<?php $attributes = $__attributesOriginal436399e29d00ce6b8f47e38277d39536; ?>
<?php unset($__attributesOriginal436399e29d00ce6b8f47e38277d39536); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal436399e29d00ce6b8f47e38277d39536)): ?>
<?php $component = $__componentOriginal436399e29d00ce6b8f47e38277d39536; ?>
<?php unset($__componentOriginal436399e29d00ce6b8f47e38277d39536); ?>
<?php endif; ?>

      <?php if(isset($sportsArticles) && count($sportsArticles) > 0): ?>
        <a href="<?php echo e(route('article.show', $sportsArticles[0]['slug'])); ?>" class="group block mb-4">
          <div class="relative w-full aspect-video overflow-hidden rounded-sm">
            <img src="<?php echo e($sportsArticles[0]['image_url']); ?>" alt="<?php echo e($sportsArticles[0]['title']); ?>" loading="lazy"
              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 px-4 py-3">
              <h3 class="font-serif font-extrabold text-[20px] md:text-[22px] text-white leading-tight group-hover:text-[#f8a0a0] transition-colors line-clamp-2 drop-shadow">
                <?php echo e($sportsArticles[0]['title']); ?>

              </h3>
              <div class="text-[12px] text-white/70 mt-1.5"><?php echo e($sportsArticles[0]['time_ago']); ?></div>
            </div>
          </div>
        </a>

        <div class="grid grid-cols-2 gap-4">
          <?php $__currentLoopData = array_slice($sportsArticles, 1, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
              class="group flex flex-col">
              <div class="relative w-full aspect-[16/9] overflow-hidden rounded-sm mb-2">
                <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
              <h3 class="font-serif font-extrabold text-[13px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-2">
                <?php echo e($a['title']); ?>

              </h3>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </div>

    
    <div class="lg:pl-6 pt-1 order-3 lg:order-3 border-t lg:border-t-0 border-border pt-6 lg:pt-0">
      <div class="w-full bg-surface border border-border flex items-center justify-center h-[80px] rounded-sm mb-4">
        <span class="text-fg-muted text-[12px] tracking-widest uppercase">বিজ্ঞাপন</span>
      </div>
      <div>
        <?php if(isset($sportsSubcatArticles)): ?>
          <?php $__currentLoopData = $sportsSubcatArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $item['article']['slug'])); ?>"
              class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px]"><?php echo e($item['subcat']); ?> &bull;</span>
                <h3 class="font-serif font-bold text-[14px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-2 mt-0.5">
                  <?php echo e($item['article']['title']); ?>

                </h3>
                <div class="text-[11px] text-fg-muted mt-1.5"><?php echo e($item['article']['time_ago']); ?></div>
              </div>
              <div class="w-[80px] aspect-video shrink-0 overflow-hidden rounded-sm">
                <img src="<?php echo e($item['article']['image_url']); ?>" alt="<?php echo e($item['article']['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>
    </div>

  </div>
</div>
<?php /**PATH D:\websie\dhaka-magazine-laravel\resources\views/components/sports-block.blade.php ENDPATH**/ ?>