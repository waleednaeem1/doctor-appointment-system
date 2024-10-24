<header class="header-section header-section-two">
    <div class="header">
        <div class="header-bottom-area">
            <div class="container-fluid">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg p-0">
                        <a class="site-logo site-title" href="<?php echo e(route('home')); ?>"><img
                            src="<?php echo e(getImage(getFilePath('logoIcon') . '/logo.png')); ?>" alt="logo">
                        </a>
                        <?php if($general->multi_language): ?>
                            <div class="d-block d-lg-none ml-auto">
                                <select class="langSel form-control">
                                    <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($item->code); ?>" <?php if(session('lang')==$item->code): ?> selected <?php endif; ?>>
                                            <?php echo e(__($item->name)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        <button class="navbar-toggler ml-auto collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="las la-bars"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav main-menu mx-auto justify-content-center">
                                <li class="<?php echo e(menuActive('home')); ?>"><a href="<?php echo e(route('home')); ?>"><i class="fas fa-home" style="font-size: 20px;"></i></a></li>
                                <li class="<?php echo e(menuActive('doctors.all')); ?>"><a href="<?php echo e(route('doctors.all')); ?>"><?php echo app('translator')->get('Doctors'); ?></a></li>
                                

                                <li class="<?php echo e(menuActive('blogs')); ?>">
                                    <a href="<?php echo e(route('blogs')); ?>"><?php echo app('translator')->get('Blogs'); ?></a>
                                </li>
                                <li class="<?php echo e(menuActive('clinics.all')); ?>">
                                    <a href="<?php echo e(route('clinics.all')); ?>"><?php echo app('translator')->get('Clinics/Hospitals'); ?></a>
                                </li>
                                <?php if(auth()->guard('user')->user()): ?>
                                    <li class="<?php echo e(menuActive('myPets')); ?>">
                                        <a href="<?php echo e(route('myPets')); ?>"><?php echo app('translator')->get('My Pets'); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if(auth()->guard('user')->user()): ?>
                                    <li class="<?php echo e(menuActive('doctors.nearByVetsLocation')); ?>">
                                        <a href="<?php echo e(route('doctors.nearByVetsLocation')); ?>"><?php echo app('translator')->get('Get Nearby Vets'); ?></a>
                                    </li>
                                <?php endif; ?>
                                
                                
                            </ul>
                            
                            <div class="header-bottom-action">
                                
                                <a href="<?php echo e(route('getAppointmentsHome')); ?>" class="cmn-btn"><?php echo app('translator')->get('Get Appointment'); ?></a>
                            </div>
                            <div class="header-bottom-action">
                                <?php if(!auth()->guard('user')->user()): ?>
                                    <a href="<?php echo e(route('login')); ?>" class="cmn-btn"><?php echo app('translator')->get('Login'); ?></a>
                                <?php else: ?>
                                    <div class="dropdown" style="margin-top: -7px;">
                                        <button class="btn cmn-btn dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <?php echo e(auth()->guard('user')->user()->name); ?>

                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
                                        <li><a class="dropdown-item" href="<?php echo e(route('user.dashboard')); ?>">Dashboard</a></li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('user.logout')); ?>">Logout</a></li>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header-section end -->
<?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/templates/basic/partials/header.blade.php ENDPATH**/ ?>