<?php
    $partnerElement = getContent('partner.element',false);
?>
<!-- brand-section start -->
<div class="brand-section pd-t-80">
    <div class="container">
        <div class="row ml-b-20">
            <div class="col-lg-12">
                <div class="brand-wrapper">
                    <div class="swiper-wrapper">
                        <?php $__currentLoopData = $partnerElement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $partner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="swiper-slide">
                                <div class="BrandSlider">
                                    <div class="brand-item">
                                        <a href="<?php echo e($partner->data_values->url); ?>" target="_blank"><img src="<?php echo e(getImage('assets/images/frontend/partner/'. @$partner->data_values->image, '120x48')); ?>" alt="<?php echo app('translator')->get('partner'); ?>"></a>
                                    </div>
                                </div>
                            </div>
                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- brand-section end -->
<?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/templates/basic/sections/partner.blade.php ENDPATH**/ ?>