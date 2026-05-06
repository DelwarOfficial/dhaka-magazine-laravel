<aside class="w-full space-y-8">

  <div class="border-t border-border pt-4">
    <h3 class="text-[20px] font-bold font-serif mb-4 text-fg">
      সর্বাধিক পঠিত
    </h3>

    <div class="flex flex-col">
      <?php if(isset($popularNews) && count($popularNews) > 0): ?>
        <?php $__currentLoopData = $popularNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('article.show', $article['slug'])); ?>" class="group flex items-start space-x-3 py-3 border-b border-border last:border-0">
            <span class="text-[24px] font-serif font-bold text-[#e2231a] leading-none mt-1">
              <?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?>

            </span>
            <div>
              <h4 class="font-bold text-[16px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors font-serif">
                <?php echo e($article['title']); ?>

              </h4>
            </div>
          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="bg-surface p-6 border-t border-[#e2231a] pt-6 border-t-[3px]">
    <h3 class="text-lg font-bold text-center mb-2 font-serif text-fg">নিউজলেটার</h3>
    <p class="text-[13px] text-center text-fg-secondary mb-4">
      প্রতিদিনের বাছাই করা খবর পেতে সাবস্ক্রাইব করুন
    </p>
    <form class="flex flex-col space-y-3" action="#" method="POST">
      <?php echo csrf_field(); ?>
      <input
        type="email"
        name="email"
        placeholder="আপনার ইমেইল"
        class="px-3 py-2 border border-border focus:outline-none focus:border-border w-full text-sm rounded-none"
      />
      <button
        type="submit"
        class="bg-[#e2231a] text-white font-bold py-2 px-4 hover:bg-[#e2231a]/90 transition-colors text-sm rounded-none"
      >
        সাবস্ক্রাইব
      </button>
    </form>
  </div>

  <div class="bg-surface w-full h-[250px] flex items-center justify-center border border-border">
    <span class="text-gray-400 text-xs uppercase tracking-widest">বিজ্ঞাপন</span>
  </div>

</aside>
<?php /**PATH D:\Antigravity\Dhaka-Magazine-Laravel-App\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>