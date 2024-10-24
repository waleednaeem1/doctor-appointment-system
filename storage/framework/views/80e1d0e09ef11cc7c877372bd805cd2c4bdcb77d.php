<?php
    $departmentContent = getContent('department.content',true);
    $departmentData    = \App\Models\Department::orderBy('id', 'DESC')->get();

    if($departmentData->count() >= 4){
        $length = round($departmentData->count() / 4);
    }else{
        $length = $departmentData->count();
    }
    $item = [];
    $skip = 0;
    for($i = 0; $i<$length; $i++) {
        $item[$i] = $departmentData->skip($skip)->take(4);
        $skip += 4;
    }
?>

<!-- choose-section start -->
<section class="choose-section ptb-80">
    <div class="container">
        <div class="row justify-content-center align-items-center ml-b-30">
            <div class="col-lg-4 mrb-30">
                <div class="choose-left-content">
                    <h2 class="title"><?php echo e(__($departmentContent->data_values->heading)); ?></h2>
                    <p><?php echo e(__($departmentContent->data_values->subheading)); ?></p>
                    <div class="choose-btn">
                        
                        <a href="<?php echo e(route('getAppointmentsHome')); ?>" class="cmn-btn"><?php echo app('translator')->get('Get Appointment'); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mrb-30">
                <div class="choose-slider">
                    <div class="swiper-wrapper">
                        <?php for($d = 0; $d < count($item); $d++): ?>
                        <div class="swiper-slide">
                            <div class="choose-right-content">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="right-column-one">
                                            <?php $__currentLoopData = $item[$d]->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="choose-item">
                                                <div class="choose-thumb">
                                                    <a href="<?php echo e(route('doctors.departments', $department->id)); ?>">
                                                    <img src="<?php echo e(getImage(getFilePath('department').'/'. @$department->image, getFileSize('department'))); ?>" alt="<?php echo app('translator')->get('department'); ?>">
                                                    </a>
                                                </div>
                                                <div class="choose-content">
                                                <h6 class="title"><a href="<?php echo e(route('doctors.departments', $department->id)); ?>"><?php echo e(__($department->name)); ?></a></h6>
                                                    <p><?php echo e(__($department->details)); ?></p>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="right-column-two">
                                            <?php $__currentLoopData = $item[$d]->skip(2)->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="choose-item">
                                                <div class="choose-thumb">
                                                    <a href="<?php echo e(route('doctors.departments', $department->id)); ?>">
                                                    <img src="<?php echo e(getImage(getFilePath('department').'/'. $department->image, getFileSize('department'))); ?>" alt="<?php echo app('translator')->get('department'); ?>">
                                                    </a>
                                                </div>
                                                <div class="choose-content">
                                                    <h6 class="title"><a href="<?php echo e(route('doctors.departments', $department->id)); ?>"><?php echo e(__($department->name)); ?></a></h6>
                                                    <p><?php echo e(__($department->details)); ?></p>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- choose-section end -->
<?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/templates/basic/sections/department.blade.php ENDPATH**/ ?>