<?php
 $testimonialContent = getContent('testimonial.content',true);
 $testimonialElement = getContent('testimonial.element', null, false, true);
?>

<div class="client-section ptb-80">
    <div class="client-element-one">
        <img src="<?php echo e(getImage('assets/images/shape.png')); ?>" alt="<?php echo app('translator')->get('shape'); ?>">
    </div>
    <div class="client-element-two">
        <img src="<?php echo e(getImage('assets/images/shape.png')); ?>" alt="<?php echo app('translator')->get('shape'); ?>">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-header d-flex flex-wrap align-items-center justify-content-between">
                    <div class="section-header-left">
                        <h2 class="section-title"><?php echo e(__($testimonialContent->data_values->heading)); ?></h2>
                        <p class="m-0"><?php echo e(__($testimonialContent->data_values->subheading)); ?> </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center ml-b-20">
            <div class="col-lg-12 text-center">
                <div class="client-slider">
                    <div class="swiper-wrapper">
                        <?php $__currentLoopData = $testimonialElement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="swiper-slide">
                                <div class="client-item">
                                    <div class="client-content">
                                        <p><?php echo e(__($testimonial->data_values->quote)); ?></p>
                                        <div class="client-icon" style="top: -5%;">
                                            <i class="las la-quote-left"></i>
                                        </div>
                                    </div>
                                    <div class="client-thumb">
                                        <img src="<?php echo e(getImage('assets/images/frontend/testimonial/'. @$testimonial->data_values->image, '80x80')); ?>" alt="<?php echo app('translator')->get('client'); ?>">
                                    </div>
                                    <div class="client-footer">
                                        <h4 class="title"><?php echo e(__($testimonial->data_values->name)); ?></h4>
                                        <span class="sub-title"><?php echo e(__($testimonial->data_values->designation)); ?></span>
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
<?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/templates/basic/sections/testimonial.blade.php ENDPATH**/ ?>