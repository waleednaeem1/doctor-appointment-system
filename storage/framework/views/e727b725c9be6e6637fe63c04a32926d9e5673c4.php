<?php
    $featureContent = getContent('feature.content', true);
    $doctors        = \App\Models\Doctor::active()->where('featured', Status::YES)
        ->with(['department:id,name', 'location:id,name', 'socialIcons'])
        ->orderBy('id', 'DESC')
        ->take(6)->get(['id', 'name', 'about', 'qualification', 'department_id', 'location_id', 'image', 'mobile']);
?>

<section class="team-section ptb-80">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-header">
                    <h2 class="section-title"><?php echo e(__($featureContent->data_values->heading)); ?></h2>
                    <p class="m-0"><?php echo e(__($featureContent->data_values->subheading)); ?> </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center ml-b-30">
            <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-6 col-lg-4 col-md-6 mrb-30">
                <div class="team-item d-flex flex-wrap align-items-center justify-content-between">
                    <div class="team-thumb">
                        <a href="<?php echo e(route('doctors.booking',[$doctor->id,$doctor->id])); ?>"><img src="<?php echo e(getImage(getFilePath('doctorProfile').'/'. @$doctor->image, getFileSize('doctorProfile'))); ?> " alt="<?php echo app('translator')->get('doctor-image'); ?>">
                        <div class="team-thumb-overlay">
                            <ul class="social-icon">
                                    <?php $__currentLoopData = $doctor->socialIcons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><a href="<?php echo e($social->url); ?>" target="_blank"><?php echo $social->icon ?></a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        </div></a>
                        <div class="team-content">
                            <h5 class="title"><?php echo e(__($doctor->name)); ?></h5>
                            <p><?php echo e(StrLimit(__($doctor->about),70)); ?></p>
                            <h6 class="title"><?php echo app('translator')->get('Qualification'); ?></h6>
                            <p><?php echo e(StrLimit(__($doctor->qualification),30)); ?></p>

                            <div class="booking-btn">

                                <a href="<?php echo e(route('doctors.booking',[$doctor->id,$doctor->id])); ?>" class="cmn-btn"><?php echo app('translator')->get('Get Appointment'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="row justify-content-center mrt-60">
            <div class="col-lg-12 text-center">
                <div class="team-btn">
                    <a href="<?php echo e(route('doctors.featured')); ?>" class="cmn-btn-active"><?php echo app('translator')->get('View More'); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/templates/basic/sections/feature.blade.php ENDPATH**/ ?>