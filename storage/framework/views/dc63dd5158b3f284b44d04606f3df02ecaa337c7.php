<?php
    $faqContent = getContent('faq.content',true);
    $faqElement = getContent('faq.element',null, false, true);
?>
<section class="faq-section pd-t-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-header">
                    <h2 class="section-title"><?php echo e(__($faqContent->data_values->heading)); ?></h2>
                    <p class="m-0"><?php echo e(__($faqContent->data_values->subheading)); ?></p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center ml-b-30">
            <div class="col-lg-12 mrb-30">
                <div class="faq-wrapper">
                    <?php $__currentLoopData = $faqElement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($loop->odd): ?>
                            <div class="faq-item">
                                <h3 class="faq-title"><span class="title"><?php echo e(__($faq->data_values->question)); ?> </span><span class="right-icon"></span></h3>
                                <div class="faq-content">
                                    <p><?php echo e(__($faq->data_values->answer)); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            
        </div>

        <div class="row justify-content-center mrt-60">
            <div class="col-lg-12 text-center">
                <div class="team-btn">
                    <a href="<?php echo e(route('faqs')); ?>" class="cmn-btn-active"><?php echo app('translator')->get('View More'); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/templates/basic/sections/faq.blade.php ENDPATH**/ ?>