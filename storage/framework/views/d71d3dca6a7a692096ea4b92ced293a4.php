


<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['article', 'showCategory' => true, 'showTime' => true, 'aspectRatio' => '16/9', 'titleSize' => 15, 'class' => '']));

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

foreach (array_filter((['article', 'showCategory' => true, 'showTime' => true, 'aspectRatio' => '16/9', 'titleSize' => 15, 'class' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<a href="<?php echo e(route('article.show', $article['slug'])); ?>" class="group flex flex-col <?php echo e($class); ?>">
    <?php if(!empty($article['image_url'])): ?>
        <div class="w-full overflow-hidden mb-2" style="aspect-ratio: <?php echo e($aspectRatio); ?>;">
            <img src="<?php echo e($article['image_url']); ?>" alt="<?php echo e($article['title']); ?>" loading="lazy"
                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]">
        </div>
    <?php endif; ?>
    <?php if($showCategory && !empty($article['category'])): ?>
        <span class="text-[#e2231a] font-bold text-[12px] mb-0.5"><?php echo e($article['category']); ?></span>
    <?php endif; ?>
    <h3 class="font-serif font-bold text-[<?php echo e($titleSize); ?>px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-2">
        <?php echo e($article['title']); ?>

    </h3>
    <?php if($showTime && !empty($article['time_ago'])): ?>
        <div class="text-[11px] text-fg-muted mt-1"><?php echo e($article['time_ago']); ?></div>
    <?php endif; ?>
</a>
<?php /**PATH D:\websie\dhaka-magazine-laravel\resources\views/components/cards/grid.blade.php ENDPATH**/ ?>