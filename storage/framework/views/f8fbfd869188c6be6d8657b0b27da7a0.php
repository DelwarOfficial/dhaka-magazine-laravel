<?php $__env->startSection('title', 'ঢাকা ম্যাগাজিন - হোম'); ?>

<?php $__env->startSection('content'); ?>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 hero-section">
    
    <div class="grid grid-cols-1 md:hidden gap-3 border-t border-border">
      
      
      <?php if(isset($featured)): ?>
        <a href="<?php echo e(route('article.show', $featured['slug'])); ?>" class="group flex flex-col py-4 border-b border-border">
          <div class="w-full aspect-[16/9] overflow-hidden rounded-sm mb-2">
            <img src="<?php echo e($featured['image_url']); ?>" alt="<?php echo e($featured['title']); ?>" loading="lazy"
              class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
          </div>
          <h2 class="font-serif font-bold text-[16px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
            <?php echo e($featured['title']); ?>

          </h2>
          <p class="text-fg-secondary text-[12px] line-clamp-2 mt-1 min-h-[2em]"><?php echo e($featured['excerpt']); ?></p>
        </a>
      <?php endif; ?>

      
      <?php if(isset($centerGrid)): ?>
        <div class="grid grid-cols-3 gap-3 py-2 border-b border-border">
          <?php $__currentLoopData = $centerGrid; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>" class="group flex flex-col">
              <?php if(!empty($a['image_url'])): ?>
                <div class="w-full aspect-[4/3] overflow-hidden rounded-sm mb-2">
                  <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
                </div>
              <?php endif; ?>
              <span class="text-[#e2231a] font-bold text-[11px] block mb-0.5"><?php echo e($a['category']); ?></span>
              <h3 class="font-serif font-bold text-[13px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                <?php echo e($a['title']); ?>

              </h3>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>

      
      <?php if(isset($leftCol)): ?>
        <div class="py-2">
          <?php $__currentLoopData = $leftCol; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>" class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px] block mb-0.5"><?php echo e($a['category']); ?></span>
                <h3 class="font-serif font-bold text-[14px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  <?php echo e($a['title']); ?>

                </h3>
              </div>
              <?php if(!empty($a['image_url'])): ?>
                <div class="w-[120px] h-[68px] shrink-0 overflow-hidden rounded-sm">
                  <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
                </div>
              <?php endif; ?>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>

      
      <?php if(isset($rightCol)): ?>
        <div class="py-2">
          <?php $__currentLoopData = $rightCol; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>" class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
              <?php if(!empty($a['image_url'])): ?>
                <div class="w-[120px] h-[68px] shrink-0 overflow-hidden rounded-sm">
                  <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
                </div>
              <?php endif; ?>
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px] block mb-0.5"><?php echo e($a['category']); ?></span>
                <h3 class="font-serif font-bold text-[14px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  <?php echo e($a['title']); ?>

                </h3>
              </div>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      <?php endif; ?>
    </div>

    
    <div class="hidden md:grid grid-cols-1 lg:grid-cols-[27%_46%_27%] hero-grid border-t border-border">

      
      <div class="py-4 pr-0 lg:pr-5 lg:border-r border-border order-2 lg:order-1">
        <?php if(isset($leftCol)): ?>
          <?php $__currentLoopData = $leftCol; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
              class="group flex flex-col-reverse md:flex-row items-start gap-3 py-3 border-b border-border last:border-b-0">
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px] block mb-0.5"><?php echo e($a['category']); ?> &bull;</span>
                <h3 class="font-serif font-bold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  <?php echo e($a['title']); ?>

                </h3>
                <?php if(!empty($a['excerpt'])): ?>
                  <p class="text-fg-secondary text-[12px] line-clamp-2 mt-1"><?php echo e($a['excerpt']); ?></p>
                <?php endif; ?>
                <div class="text-[11px] text-fg-muted mt-1"><?php echo e($a['time_ago']); ?></div>
              </div>
              <?php if(!empty($a['image_url'])): ?>
                <div class="w-full md:w-[88px] h-[140px] md:h-[50px] shrink-0 overflow-hidden rounded-sm mb-2 md:mb-0">
                  <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
                </div>
              <?php endif; ?>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>

      
      <div class="py-4 px-0 lg:px-5 lg:border-r border-border order-1 lg:order-2">
        
        <?php if(isset($featured)): ?>
          <a href="<?php echo e(route('article.show', $featured['slug'])); ?>" class="group flex flex-col mb-3 pb-3 border-b border-border">
            <div class="w-full aspect-[16/9] overflow-hidden rounded-sm mb-2">
              <img src="<?php echo e($featured['image_url']); ?>" alt="<?php echo e($featured['title']); ?>" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
            </div>
            <h2 class="font-serif font-bold text-[22px] leading-[1.25] text-fg group-hover:text-[#e2231a] transition-colors mb-2">
              <?php echo e($featured['title']); ?>

            </h2>
            <?php if(!empty($featured['excerpt'])): ?>
              <p class="text-fg-secondary text-[13px] line-clamp-2 leading-relaxed min-h-[2.5em]"><?php echo e($featured['excerpt']); ?></p>
            <?php endif; ?>
          </a>
        <?php endif; ?>

        
        <?php if(isset($centerGrid)): ?>
          <div class="grid grid-cols-3 gap-x-4 gap-y-4">
            <?php $__currentLoopData = $centerGrid; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a href="<?php echo e(route('article.show', $a['slug'])); ?>" class="group flex flex-col">
                <div class="w-full aspect-[16/9] overflow-hidden mb-1.5">
                  <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
                </div>
                <h3 class="font-serif font-bold text-[13px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  <?php echo e($a['title']); ?>

                </h3>
                <div class="text-[11px] text-fg-muted mt-0.5"><?php echo e($a['time_ago']); ?></div>
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php endif; ?>
      </div>

      
      <div class="py-4 pl-0 lg:pl-5 order-3 lg:order-3">
        
        
        <div class="w-full h-[250px] mb-4 overflow-hidden rounded-lg">
          <img src="<?php echo e(asset('images/coming-soon-ad.webp')); ?>" alt="Advertisement" class="w-full h-full object-cover" />
        </div>

        <?php if(isset($rightCol)): ?>
          <?php $__currentLoopData = $rightCol; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
              class="group flex flex-col-reverse md:flex-row items-start gap-3 py-3 border-b border-border last:border-b-0">
              <?php if(!empty($a['image_url'])): ?>
                <div class="w-full md:w-[88px] h-[140px] md:h-[50px] shrink-0 overflow-hidden rounded-sm mb-2 md:mb-0">
                  <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.05]" />
                </div>
              <?php endif; ?>
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px] block mb-0.5"><?php echo e($a['category']); ?> &bull;</span>
                <h3 class="font-serif font-bold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2">
                  <?php echo e($a['title']); ?>

                </h3>
                <?php if(!empty($a['excerpt'])): ?>
                  <p class="text-fg-secondary text-[12px] line-clamp-2 mt-1"><?php echo e($a['excerpt']); ?></p>
                <?php endif; ?>
                <div class="text-[11px] text-fg-muted mt-1"><?php echo e($a['time_ago']); ?></div>
              </div>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>
    </div>
    </div>
  </div>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 py-3">
    <div class="w-full bg-surface border border-border flex items-center justify-center h-[90px]">
      <span class="text-fg-muted text-[12px] tracking-widest uppercase">বিজ্ঞাপন</span>
    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <?php if (isset($component)) { $__componentOriginal436399e29d00ce6b8f47e38277d39536 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal436399e29d00ce6b8f47e38277d39536 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-header','data' => ['title' => 'বাংলাদেশ','moreUrl' => route('category.show', 'জাতীয়')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'বাংলাদেশ','moreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('category.show', 'জাতীয়'))]); ?>
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
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
      <?php if(isset($bangladeshArticles)): ?>
        <?php $__currentLoopData = $bangladeshArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if (isset($component)) { $__componentOriginala5a2a75fd15770ee9b311f6b18d722bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5a2a75fd15770ee9b311f6b18d722bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.grid','data' => ['article' => $a,'titleSize' => 15]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['article' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($a),'titleSize' => 15]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5a2a75fd15770ee9b311f6b18d722bc)): ?>
<?php $attributes = $__attributesOriginala5a2a75fd15770ee9b311f6b18d722bc; ?>
<?php unset($__attributesOriginala5a2a75fd15770ee9b311f6b18d722bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5a2a75fd15770ee9b311f6b18d722bc)): ?>
<?php $component = $__componentOriginala5a2a75fd15770ee9b311f6b18d722bc; ?>
<?php unset($__componentOriginala5a2a75fd15770ee9b311f6b18d722bc); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <div class="flex items-center justify-between pb-2 mb-4 border-b border-border">
      <div class="flex items-center gap-3">
        <span class="section-icon"></span>
        <h2 class="font-serif font-extrabold text-[20px] text-[#e2231a] leading-none">সারাদেশ</h2>
      </div>
      <a href="<?php echo e(route('category.show', 'country')); ?>" class="text-fg-secondary text-[13px] hover:text-[#e2231a] transition-colors flex items-center gap-0.5">
        আরও <span class="text-[15px] leading-none ml-0.5">&rsaquo;</span>
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-[1fr_2.2fr_1.3fr] gap-0 divide-x divide-border">

      
      <div class="pr-5 flex flex-col justify-between gap-5">
        <?php if(isset($countryLeft)): ?>
          <?php $__currentLoopData = $countryLeft; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>" class="group flex flex-col">
              <div class="w-full aspect-[16/9] overflow-hidden mb-2">
                <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
              <h3 class="font-serif font-bold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-3">
                <?php echo e($a['title']); ?>

              </h3>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>

      
      <div class="px-5">
        <?php if(isset($countryHero)): ?>
          <a href="<?php echo e(route('article.show', $countryHero['slug'])); ?>" class="group flex flex-col">
            <div class="w-full aspect-[16/9] overflow-hidden mb-3">
              <img src="<?php echo e($countryHero['image_url']); ?>" alt="<?php echo e($countryHero['title']); ?>" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
            </div>
            <h3 class="font-serif font-extrabold text-[21px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-2 mb-2">
              <?php echo e($countryHero['title']); ?>

            </h3>
            <p class="text-fg-secondary text-[13px] leading-relaxed line-clamp-2">
              <?php echo e($countryHero['excerpt']); ?>

            </p>
          </a>
        <?php endif; ?>
      </div>

      
      <div class="pl-5 flex flex-col divide-y divide-border">
        <?php if(isset($countryRight)): ?>
          <?php $__currentLoopData = $countryRight; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
              class="group flex items-start gap-3 py-3 first:pt-0 last:pb-0">
              <h3 class="font-serif font-bold text-[14px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-3 flex-1">
                <?php echo e($a['title']); ?>

              </h3>
              <div class="w-[68px] h-[38px] shrink-0 overflow-hidden">
                <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <div class="flex items-center gap-3 mb-4 border-b border-border pb-2">
      <span class="section-icon"></span>
      <h2 class="font-serif font-extrabold text-[20px] text-fg">আন্তর্জাতিক</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-[2fr_2fr_1fr] gap-0 divide-x divide-border">

      
      <div class="pr-6">
        <?php if(isset($internationalBig)): ?>
          <a href="<?php echo e(route('article.show', $internationalBig['slug'])); ?>" class="group block">
            <div class="w-full aspect-[16/9] overflow-hidden mb-3">
              <img src="<?php echo e($internationalBig['image_url']); ?>" alt="<?php echo e($internationalBig['title']); ?>" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
            </div>
            <h3 class="font-serif font-extrabold text-[20px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors mb-2 line-clamp-3">
              <?php echo e($internationalBig['title']); ?>

            </h3>
            <p class="text-fg-secondary text-[13px] leading-relaxed line-clamp-3"><?php echo e($internationalBig['excerpt']); ?></p>
            <div class="text-[11px] text-fg-muted mt-2"><?php echo e($internationalBig['time_ago']); ?></div>
          </a>
        <?php endif; ?>
      </div>

      
      <div class="px-6 flex flex-col divide-y divide-border">
        <?php if(isset($internationalSmall)): ?>
          <?php $__currentLoopData = $internationalSmall; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
              class="group flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
              <h3 class="font-serif font-extrabold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-3 flex-1">
                <?php echo e($a['title']); ?>

              </h3>
              <div class="w-[72px] h-[40px] shrink-0 overflow-hidden">
                <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>

      
      <div class="pl-6 flex flex-col gap-4">
        <div class="w-full flex flex-col items-center justify-center bg-surface border border-border py-8 px-3">
          <span class="text-[#e2231a] text-[11px] font-bold tracking-wide mb-1">বিজ্ঞাপন</span>
        </div>
        <?php if(isset($internationalRight)): ?>
          <a href="<?php echo e(route('article.show', $internationalRight['slug'])); ?>"
            class="group flex items-start gap-3">
            <div class="flex-1">
              <h3 class="font-serif font-extrabold text-[14px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-4">
                <?php echo e($internationalRight['title']); ?>

              </h3>
              <div class="text-[11px] text-fg-muted mt-1"><?php echo e($internationalRight['time_ago']); ?></div>
            </div>
            <div class="w-[64px] h-[36px] shrink-0 overflow-hidden">
              <img src="<?php echo e($internationalRight['image_url']); ?>" alt="<?php echo e($internationalRight['title']); ?>" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
            </div>
          </a>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <?php if (isset($component)) { $__componentOriginal436399e29d00ce6b8f47e38277d39536 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal436399e29d00ce6b8f47e38277d39536 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-header','data' => ['title' => 'মতামত','moreUrl' => route('category.show', 'মতামত')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'মতামত','moreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('category.show', 'মতামত'))]); ?>
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
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 divide-x divide-border">
      <?php if(isset($opinionArticles)): ?>
        <?php $__currentLoopData = $opinionArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
            class="group flex flex-col <?php echo e($i === 0 ? 'pr-5' : ($i === 3 ? 'pl-5' : 'px-5')); ?>">
            <div class="w-full aspect-[16/9] overflow-hidden mb-2">
              <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
            </div>
            <?php if(isset($opinionMeta) && isset($opinionMeta[$i])): ?>
              <span class="text-[#e2231a] font-bold text-[11px] mb-0.5 uppercase tracking-wide"><?php echo e($opinionMeta[$i]['tag']); ?></span>
              <p class="font-serif font-bold text-[13px] text-fg-secondary mb-1"><?php echo e($opinionMeta[$i]['name']); ?></p>
            <?php endif; ?>
            <h3 class="font-serif font-bold text-[15px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-3">
              <?php echo e($a['title']); ?>

            </h3>
            <div class="text-[11px] text-fg-muted mt-1"><?php echo e($a['time_ago']); ?></div>
          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <div class="grid grid-cols-1 md:grid-cols-[220px_1fr_220px]">

      
      <div class="border-r border-border pr-5 pt-1">
        <div class="flex mb-3">
          <button class="font-serif text-[14px] mr-5 pb-2 border-b-2 border-[#e2231a] text-fg font-extrabold transition-colors">পঠিত</button>
          <button class="font-serif text-[14px] mr-5 pb-2 border-b-2 border-transparent text-fg-muted hover:text-fg transition-colors">আলোচিত</button>
          <button class="font-serif text-[14px] mr-5 pb-2 border-b-2 border-transparent text-fg-muted hover:text-fg transition-colors">সুখবর</button>
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

      
      <div class="px-5 py-1 border-r border-border">
        <div class="flex items-center gap-2 mb-3">
          <span class="w-2.5 h-2.5 rounded-full bg-[#4a90d9] shrink-0"></span>
          <span class="font-serif font-extrabold text-[16px] text-fg">খেলা</span>
        </div>

        <?php if(isset($sportsArticles) && count($sportsArticles) > 0): ?>
          <a href="<?php echo e(route('article.show', $sportsArticles[0]['slug'])); ?>" class="group block mb-4">
            <div class="relative w-full aspect-video overflow-hidden">
              <img src="<?php echo e($sportsArticles[0]['image_url']); ?>" alt="<?php echo e($sportsArticles[0]['title']); ?>" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
              <div class="absolute bottom-0 left-0 right-0 px-4 py-3">
                <h3 class="font-serif font-extrabold text-[20px] text-white leading-tight group-hover:text-[#f8a0a0] transition-colors line-clamp-2 drop-shadow">
                  <?php echo e($sportsArticles[0]['title']); ?>

                </h3>
                <div class="text-[12px] text-white/70 mt-1"><?php echo e($sportsArticles[0]['time_ago']); ?></div>
              </div>
            </div>
          </a>

          <div class="grid grid-cols-2 gap-0">
            <?php $__currentLoopData = array_slice($sportsArticles, 1, 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
                class="group <?php echo e($i === 1 ? 'pl-3 border-l border-border' : 'pr-3'); ?>">
                <div class="relative w-full aspect-[16/9] overflow-hidden">
                  <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
                  <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/20 to-transparent"></div>
                  <div class="absolute bottom-0 left-0 right-0 px-2.5 py-2">
                    <h3 class="font-serif font-extrabold text-[13px] text-white leading-tight group-hover:text-[#f8a0a0] transition-colors line-clamp-2 drop-shadow">
                      <?php echo e($a['title']); ?>

                    </h3>
                    <div class="text-[11px] text-white/60 mt-0.5"><?php echo e($a['time_ago']); ?></div>
                  </div>
                </div>
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php endif; ?>
      </div>

      
      <div class="pl-5 pt-1">
        <div class="w-full bg-surface border border-border flex items-center justify-center h-[80px]">
          <span class="text-fg-muted text-[12px] tracking-widest uppercase">বিজ্ঞাপন</span>
        </div>
        <div class="mt-4">
          <?php if(isset($sportsSubcatArticles)): ?>
            <?php $__currentLoopData = $sportsSubcatArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a href="<?php echo e(route('article.show', $item['article']['slug'])); ?>"
                class="group flex items-start gap-2 py-3 border-b border-border last:border-b-0">
                <div class="flex-1 min-w-0">
                  <span class="text-[#e2231a] font-bold text-[12px]"><?php echo e($item['subcat']); ?> &bull;</span>
                  <h3 class="font-serif font-bold text-[14px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-3 mt-0.5">
                    <?php echo e($item['article']['title']); ?>

                  </h3>
                  <div class="text-[11px] text-fg-muted mt-1"><?php echo e($item['article']['time_ago']); ?></div>
                </div>
                <div class="w-[70px] h-[39px] shrink-0 overflow-hidden">
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

  <div class="border-t-4 border-border"></div>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <?php if (isset($component)) { $__componentOriginal436399e29d00ce6b8f47e38277d39536 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal436399e29d00ce6b8f47e38277d39536 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-header','data' => ['title' => 'প্রযুক্তি','moreUrl' => route('category.show', 'প্রযুক্তি')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'প্রযুক্তি','moreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('category.show', 'প্রযুক্তি'))]); ?>
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
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
      <?php if(isset($techArticles)): ?>
        <?php $__currentLoopData = $techArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if (isset($component)) { $__componentOriginala5a2a75fd15770ee9b311f6b18d722bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5a2a75fd15770ee9b311f6b18d722bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cards.grid','data' => ['article' => $a,'titleSize' => 15]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cards.grid'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['article' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($a),'titleSize' => 15]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5a2a75fd15770ee9b311f6b18d722bc)): ?>
<?php $attributes = $__attributesOriginala5a2a75fd15770ee9b311f6b18d722bc; ?>
<?php unset($__attributesOriginala5a2a75fd15770ee9b311f6b18d722bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5a2a75fd15770ee9b311f6b18d722bc)): ?>
<?php $component = $__componentOriginala5a2a75fd15770ee9b311f6b18d722bc; ?>
<?php unset($__componentOriginala5a2a75fd15770ee9b311f6b18d722bc); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  
  <div class="w-full bg-[#1d2640] py-6">
    <div class="max-w-screen-xl mx-auto px-4">
      <div class="flex items-center justify-between mb-4 pb-2 border-b border-[#333]">
        <h2 class="font-serif font-bold text-[18px] text-white flex items-center gap-2">
          <svg viewBox="0 0 24 24" fill="#e2231a" class="w-5 h-5"><path d="M8 5v14l11-7z" /></svg>
          ভিডিও
        </h2>
        <a href="<?php echo e(route('category.show', 'বিনোদন')); ?>" class="text-[#e2231a] text-[13px] font-bold hover:underline">সব ভিডিও &rarr;</a>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-[1fr_320px] gap-6">
        <?php if(isset($videoFeatured)): ?>
          <a href="<?php echo e(route('article.show', $videoFeatured['slug'])); ?>" class="group flex flex-col">
            <div class="w-full overflow-hidden mb-2 relative aspect-video">
              <img src="<?php echo e($videoFeatured['image_url']); ?>" alt="<?php echo e($videoFeatured['title']); ?>" loading="lazy"
                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="bg-[#e2231a] rounded-full flex items-center justify-center opacity-90 group-hover:opacity-100 transition-opacity w-14 h-14">
                  <svg viewBox="0 0 24 24" fill="white" class="w-7 h-7 ml-1"><path d="M8 5v14l11-7z" /></svg>
                </div>
              </div>
            </div>
            <h3 class="font-serif font-bold text-[18px] text-white leading-tight group-hover:text-[#f8a0a0] transition-colors line-clamp-2">
              <?php echo e($videoFeatured['title']); ?>

            </h3>
            <div class="text-[11px] text-fg-muted mt-1"><?php echo e($videoFeatured['time_ago']); ?></div>
          </a>
        <?php endif; ?>
        <?php if(isset($videoSmall)): ?>
          <div class="flex flex-col gap-4 border-l-0 md:border-l border-[#333] md:pl-6">
            <?php $__currentLoopData = $videoSmall; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
                class="group flex items-start gap-3">
                <div class="w-[110px] h-[62px] shrink-0 overflow-hidden relative">
                  <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
                  <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-8 h-8 bg-[#e2231a] rounded-full flex items-center justify-center opacity-90">
                      <svg viewBox="0 0 24 24" fill="white" class="w-4 h-4 ml-0.5"><path d="M8 5v14l11-7z" /></svg>
                    </div>
                  </div>
                </div>
                <div class="flex-1">
                  <h3 class="font-serif font-bold text-[14px] text-white leading-tight group-hover:text-[#f8a0a0] transition-colors line-clamp-3">
                    <?php echo e($a['title']); ?>

                  </h3>
                  <div class="text-[11px] text-fg-muted mt-1"><?php echo e($a['time_ago']); ?></div>
                </div>
              </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <?php if (isset($component)) { $__componentOriginal436399e29d00ce6b8f47e38277d39536 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal436399e29d00ce6b8f47e38277d39536 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-header','data' => ['title' => 'বিনোদন','moreUrl' => route('category.show', 'বিনোদন')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'বিনোদন','moreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('category.show', 'বিনোদন'))]); ?>
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

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_2.2fr_1fr] gap-0 lg:divide-x divide-border bg-surface border border-border">

      
      <div class="flex flex-col p-4 pb-0">
        <?php if(isset($entertainmentLeft)): ?>
          <?php $__currentLoopData = $entertainmentLeft; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
              class="group flex items-start gap-3 py-4 border-b border-border first:pt-0 last:border-b-0 last:pb-4">
              <h3 class="font-serif font-bold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-4 flex-1">
                <?php echo e($a['title']); ?>

              </h3>
              <div class="w-[90px] aspect-square shrink-0 overflow-hidden">
                <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>

      
      <div class="p-4 flex flex-col">
        <?php if(isset($entertainmentHero)): ?>
          <a href="<?php echo e(route('article.show', $entertainmentHero['slug'])); ?>" class="group flex flex-col">
            <div class="w-full overflow-hidden mb-4">
              <div class="aspect-[16/9] w-full">
                <img src="<?php echo e($entertainmentHero['image_url']); ?>" alt="<?php echo e($entertainmentHero['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
            </div>
            <h3 class="font-serif font-extrabold text-[24px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors mb-2.5">
              <?php echo e($entertainmentHero['title']); ?>

            </h3>
            <p class="text-fg-secondary text-[15px] leading-relaxed line-clamp-3">
              <?php echo e($entertainmentHero['excerpt']); ?>

            </p>
          </a>
        <?php endif; ?>
      </div>

      
      <div class="flex flex-col p-4 pb-0">
        <?php if(isset($entertainmentRight)): ?>
          <?php $__currentLoopData = $entertainmentRight; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
              class="group flex items-start gap-3 py-4 border-b border-border first:pt-0 last:border-b-0 last:pb-4">
              <h3 class="font-serif font-bold text-[15px] text-fg leading-snug group-hover:text-[#e2231a] transition-colors line-clamp-4 flex-1">
                <?php echo e($a['title']); ?>

              </h3>
              <div class="w-[90px] aspect-square shrink-0 overflow-hidden">
                <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:divide-x divide-border">
      <div class="md:pr-6">
        <?php if (isset($component)) { $__componentOriginal436399e29d00ce6b8f47e38277d39536 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal436399e29d00ce6b8f47e38277d39536 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-header','data' => ['title' => 'অর্থনীতি','moreUrl' => route('category.show', 'অর্থনীতি')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'অর্থনীতি','moreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('category.show', 'অর্থনীতি'))]); ?>
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
        <?php if(isset($economyArticles)): ?>
          <?php $__currentLoopData = $economyArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
              class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
              <div class="w-[130px] h-[73px] shrink-0 overflow-hidden">
                <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px]"><?php echo e($a['category']); ?> &bull;</span>
                <h3 class="font-serif font-bold text-[16px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-2 mt-0.5">
                  <?php echo e($a['title']); ?>

                </h3>
                <p class="text-fg-secondary text-[13px] line-clamp-2 mt-1 leading-relaxed"><?php echo e($a['excerpt']); ?></p>
                <div class="text-[11px] text-fg-muted mt-1"><?php echo e($a['time_ago']); ?></div>
              </div>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>
      <div class="md:pl-6 mt-6 md:mt-0">
        <?php if (isset($component)) { $__componentOriginal436399e29d00ce6b8f47e38277d39536 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal436399e29d00ce6b8f47e38277d39536 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-header','data' => ['title' => 'লাইফস্টাইল','moreUrl' => route('category.show', 'লাইফস্টাইল')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'লাইফস্টাইল','moreUrl' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('category.show', 'লাইফস্টাইল'))]); ?>
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
        <?php if(isset($healthArticles)): ?>
          <?php $__currentLoopData = $healthArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
              class="group flex items-start gap-3 py-3 border-b border-border last:border-b-0">
              <div class="w-[130px] h-[73px] shrink-0 overflow-hidden">
                <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
              <div class="flex-1 min-w-0">
                <span class="text-[#e2231a] font-bold text-[12px]"><?php echo e($a['category']); ?> &bull;</span>
                <h3 class="font-serif font-bold text-[16px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-2 mt-0.5">
                  <?php echo e($a['title']); ?>

                </h3>
                <p class="text-fg-secondary text-[13px] line-clamp-2 mt-1 leading-relaxed"><?php echo e($a['excerpt']); ?></p>
                <div class="text-[11px] text-fg-muted mt-1"><?php echo e($a['time_ago']); ?></div>
              </div>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="border-t-4 border-border"></div>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 py-5">
    <div class="flex items-center justify-between border-b-[3px] border-[#e2231a] pb-2 mb-4">
      <h2 class="font-serif font-bold text-[20px] text-fg leading-none">ঢাকা ম্যাগাজিন স্পেশাল</h2>
      <a href="<?php echo e(route('home')); ?>" class="text-[13px] font-bold text-fg-secondary hover:text-[#e2231a] transition-colors border border-border px-3 py-1 rounded-sm hover:border-[#e2231a]">আরও ০</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-0 divide-x divide-border">
      <?php if(isset($specialArticles)): ?>
        <?php $__currentLoopData = $specialArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('article.show', $a['slug'])); ?>"
            class="group flex flex-col <?php echo e($i === 0 ? 'pr-4' : ($i === 4 ? 'pl-4' : 'px-4')); ?>">
            <?php if($i === 0): ?>
              <div class="relative w-full aspect-[16/9] overflow-hidden mb-3">
                <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-3">
                  <h3 class="font-serif font-bold text-[16px] text-white leading-tight line-clamp-3 group-hover:text-[#f87171] transition-colors">
                    <?php echo e($a['title']); ?>

                  </h3>
                </div>
              </div>
            <?php else: ?>
              <div class="w-full aspect-[16/9] overflow-hidden mb-3">
                <img src="<?php echo e($a['image_url']); ?>" alt="<?php echo e($a['title']); ?>" loading="lazy"
                  class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" />
              </div>
              <h3 class="font-serif font-bold text-[15px] text-fg leading-tight group-hover:text-[#e2231a] transition-colors line-clamp-3 mb-1.5">
                <?php echo e($a['title']); ?>

              </h3>
            <?php endif; ?>

            <p class="text-fg-secondary text-[13px] leading-relaxed line-clamp-3">
              <?php echo e($a['excerpt']); ?>

            </p>
          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
    </div>
  </div>

  
  <div class="w-full max-w-screen-xl mx-auto px-4 pb-4">
    <div class="w-full bg-surface border border-border flex items-center justify-center h-[80px]">
      <span class="text-fg-muted text-[12px] tracking-widest uppercase">বিজ্ঞাপন</span>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Antigravity\Dhaka-Magazine-Laravel-App\resources\views/pages/home.blade.php ENDPATH**/ ?>