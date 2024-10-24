<?php $__env->startSection('content'); ?>
    <?php
        $socialElement = getContent('social_icon.element', false, null, true);
        $bannerElement = getContent('banner.element', false, null, true);
    ?>

    <section class="banner">
        <?php if(count($socialElement) > 0): ?>
            <div class="banner-social-area">
                <span><?php echo app('translator')->get('Follow Us'); ?></span>
                <ul class="banner-social">
                    <?php $__currentLoopData = $socialElement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e($social->data_values->url); ?>" target="_blank"><?php echo $social->data_values->social_icon ?></a></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        

        <div class="swiper-slide">
            <div class="banner-section bg-overlay-custom bg_img"
                data-background="<?php echo e(getImage('assets/images/frontend/banner/' . @$bannerElement[2]->data_values->image, '1150x700')); ?>">
                <div class="custom-container">
                    <div class="row align-items-center">
                        <div class="col-xl-6 text-center">
                            <div class="banner-content-custom">
                                <h2 class="title custom-style-for-home-title"><?php echo e(__($bannerElement[2]->data_values->heading)); ?></h2>
                                <p class="custom-style-for-home-p"><?php echo e(__($bannerElement[2]->data_values->subheading)); ?></p>
                                <div class="banner-btn custom-style-for-home-button">
                                    
                                    <a href="<?php echo e(route('getAppointmentsHome')); ?>" class="btn cmn-btn"><?php echo app('translator')->get('Get Appointment'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php if($sections->secs != null): ?>
        <?php $__currentLoopData = json_decode($sections->secs); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make($activeTemplate . 'sections.' . $sec, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($activeTemplate . 'layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/templates/basic/home.blade.php ENDPATH**/ ?>