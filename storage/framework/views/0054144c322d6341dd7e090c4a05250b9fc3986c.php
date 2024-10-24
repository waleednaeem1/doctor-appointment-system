<?php
    $blogContent = getContent('blog.content',true);
    $blogElement =  getContent('blog.element',false,3);
?>
<!-- blog-section start -->
<section class="blog-section ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center">
                <div class="section-header">
                    <h2 class="section-title"><?php echo e(__($blogContent->data_values->heading)); ?></h2>
                    <p><?php echo e(__($blogContent->data_values->subheading)); ?></p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <?php $__currentLoopData = $blogElement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="col-lg-4 col-md-6 col-sm-8 mrb-30">
                <div class="blog-item">
                    <div class="blog-thumb">
                        <img src="<?php echo e(getImage('assets/images/frontend/blog/'. @$blog->data_values->blog_image, '730x400')); ?>" alt="<?php echo app('translator')->get('blog-image'); ?>">
                        <span class="blog-cat"><?php echo e(__($blog->data_values->category)); ?></span>
                    </div>
                    <div class="blog-content">
                        <h4 class="title">
                            <a href="<?php echo e(route('blog.details',[slug($blog->data_values->title),$blog->id])); ?>"><?php echo e(StrLimit(strip_tags(__($blog->data_values->title)),35)); ?> </a>
                        </h4>
                        <p><?php echo e(StrLimit(strip_tags(__($blog->data_values->description_nic)),80)); ?></p>
                        <div class="blog-btn">
                            <a href="<?php echo e(route('blog.details',[slug($blog->data_values->title),$blog->id])); ?>" class="custom-btn text-primary cmn--text"><?php echo app('translator')->get('Continue Reading'); ?><i class="las la-angle-double-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<!-- blog-section end -->

<?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/templates/basic/sections/blog.blade.php ENDPATH**/ ?>