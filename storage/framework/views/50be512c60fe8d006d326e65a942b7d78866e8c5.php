<?php
    $doctorContent = getContent('doctor.content', true);
    $doctors    = \App\Models\Doctor::active()
        ->with('department', 'location')
        ->orderBy('id', 'DESC')
        ->get(['id', 'name', 'qualification', 'fees', 'image', 'department_id', 'location_id']);
?>
<!-- our Doctors section start -->
<section class="booking-section ptb-80">
    <div class="container-fluid">
        <div class="row ml-b-20">
            <div class="booking-right-area">
                <div class="col-lg-12">
                    <div class="section-header">
                        <h2 class="section-title"><?php echo e(__($doctorContent->data_values->heading)); ?></h2>
                        <p class="m-0"><?php echo e(__($doctorContent->data_values->subheading)); ?></p>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="booking-slider">
                        <div class="swiper-wrapper">
                            <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="swiper-slide">
                                    <div class="booking-item" style="height: 380px; display: flex; flex-direction: column;">
                                        <div class="booking-thumb">
                                            <a href="<?php echo e(route('doctors.booking',[$doctor->id,$doctor->id])); ?>"><img src="<?php echo e(getImage(getFilePath('doctorProfile') . '/' . @$doctor->image, getFileSize('doctorProfile'))); ?>" alt="<?php echo app('translator')->get('doctor'); ?>"></a>
                                            
                                            <?php if($doctor->featured): ?>
                                                <span class="fav-btn"><i class="las la-medal"></i></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="booking-content">
                                            <?php if(isset($doctor->department) && $doctor->department !==''): ?>
                                                <span class="sub-title">
                                                    <a href="<?php echo e(route('doctors.departments', $doctor->department->id)); ?>"><?php echo e(__($doctor->department->name)); ?></a>
                                                </span>
                                            <?php endif; ?>
                                            <h5 class="title"><?php echo e(__($doctor->name)); ?><i
                                                    class="fas fa-check-circle m-0"></i></h5>
                                            <p><?php echo e(strLimit(__($doctor->qualification), 50)); ?></p>
                                            <ul class="booking-list">
                                                
                                                
                                            </ul>
                                            <div class="booking-btn for-booking-class">
                                                <a href="<?php echo e(route('doctors.booking',[$doctor->id,$doctor->id])); ?>" class="cmn-btn w-100 text-center"><?php echo app('translator')->get('Get Appointment'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="ruddra-next">
                            <i class="las la-angle-right"></i>
                        </div>
                        <div class="ruddra-prev">
                            <i class="las la-angle-left"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- booking-section end -->
<?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/templates/basic/sections/doctor.blade.php ENDPATH**/ ?>