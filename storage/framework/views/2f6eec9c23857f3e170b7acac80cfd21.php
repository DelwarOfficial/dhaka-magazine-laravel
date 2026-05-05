


<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title', 'moreUrl' => null, 'moreText' => 'আরও পড়ুন', 'showIcon' => true, 'class' => '']));

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

foreach (array_filter((['title', 'moreUrl' => null, 'moreText' => 'আরও পড়ুন', 'showIcon' => true, 'class' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="flex items-center justify-between gap-3 pb-2 mb-4 border-b border-border <?php echo e($class); ?>">
    <h2 class="font-serif font-extrabold text-[20px] text-fg leading-none flex items-center gap-3">
        <?php if($showIcon): ?>
            <span class="section-icon"></span>
        <?php endif; ?>
        <?php echo e($title); ?>

    </h2>
    <?php if($moreUrl): ?>
        <a href="<?php echo e($moreUrl); ?>" class="text-fg-secondary text-[13px] hover:text-primary transition-colors flex items-center gap-0.5">
            <?php echo e($moreText); ?>

            <span class="text-[15px] leading-none ml-0.5">&rsaquo;</span>
        </a>
    <?php endif; ?>
</div>
<?php /**PATH D:\Antigravity\Dhaka-Magazine-Laravel-App\resources\views/components/section-header.blade.php ENDPATH**/ ?>