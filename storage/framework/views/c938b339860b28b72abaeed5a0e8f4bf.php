<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['videoFeatured' => null, 'videoSmall' => []]));

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

foreach (array_filter((['videoFeatured' => null, 'videoSmall' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="w-full max-w-screen-xl mx-auto px-4 py-5">
  <?php if (isset($component)) { $__componentOriginal436399e29d00ce6b8f47e38277d39536 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal436399e29d00ce6b8f47e38277d39536 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-header','data' => ['title' => 'ভিডিও','moreUrl' => route('category.show', 'বিনোদন')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'ভিডিও','moreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('category.show', 'বিনোদন'))]); ?>
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
  
  <div class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-6 lg:gap-8">
    
    
    <?php if($videoFeatured): ?>
      <a href="<?php echo e(route('article.show', $videoFeatured['slug'])); ?>" class="group flex flex-col">
        <div class="w-full overflow-hidden mb-3 relative aspect-video rounded-sm">
          <img src="<?php echo e($videoFeatured['image_url']); ?>" alt="<?php echo e($videoFeatured['title']); ?>" loading="lazy"
            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-[#e2231a] rounded-full flex items-center justify-center opacity-90 group-hover:opacity-100 transition-opacity w-14 h-14">
              <svg viewBox="0 0 24 24" fill="white" class="w-7 h-7 ml-1"><path d="M8 5v14l11-7z" /></svg>
            </div>
          </div>
        </div>
        <h3 class="font-serif font-bold text-[18px] md:text-[22px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-2">
          <?php echo e($videoFeatured['title']); ?>

        </h3>
        <div class="text-[12px] text-fg-muted mt-1.5"><?php echo e($videoFeatured['time_ago']); ?></div>
      </a>
    <?php endif; ?>

    
    <?php if($videoSmall && count($videoSmall) > 0): ?>
      <div class="flex flex-col gap-4 border-t border-border pt-5 mt-2 lg:border-t-0 lg:pt-0 lg:mt-0 lg:border-l lg:pl-8">
        <?php $__currentLoopData = $videoSmall; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
            class="group flex items-start gap-3">
            <div class="w-[140px] aspect-video shrink-0 overflow-hidden relative rounded-sm">
              <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-8 h-8 bg-[#e2231a] rounded-full flex items-center justify-center opacity-90">
                  <svg viewBox="0 0 24 24" fill="white" class="w-4 h-4 ml-0.5"><path d="M8 5v14l11-7z" /></svg>
                </div>
              </div>
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="font-serif font-bold text-[14px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-3">
                <?php echo e($a['title']); ?>

              </h3>
              <div class="text-[11px] text-fg-muted mt-1.5"><?php echo e($a['time_ago']); ?></div>
            </div>
          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php endif; ?>
    
  </div>
</div>
<?php /**PATH D:\websie\dhaka-magazine-laravel\resources\views/components/video-block.blade.php ENDPATH**/ ?>