<?php
    $footerContent    = getContent('footer.content', true);
    $contactElement   = getContent('contact_us.element', false);
    $subscribeContent = getContent('subscribe.content', true);
    $socialIcons      = getContent('social_icon.element', false, null, true);

    $departments = \App\Models\Department::orderBy('id', 'DESC')->take(6)->get();
    $locations   = \App\Models\Location::orderBy('id', 'DESC')->take(6)->get();
    $states      = \App\Models\States::orderBy('id', 'DESC')->whereHas('doctors')->take(6)->get();

?>

<!-- call-to-action section start -->
<section class="call-to-action-section">
    <div class="container">
        <div class="row justify-content-center align-self-center">
            <div class="col-lg-8 text-center">
                <div class="call-to-action-area">
                    <div class="call-info">
                        <div class="call-info-thumb">
                            <img src="<?php echo e(getImage('assets/images/frontend/footer/' . @$footerContent->data_values->emergency_contact_image)); ?>"
                                alt="<?php echo app('translator')->get('Emergency Contact'); ?>">
                        </div>
                        <div class="call-info-content">
                            <h4 class="title">
                                <span><?php echo app('translator')->get('Emergency Call'); ?></span>
                                <a hre="tel:<?php echo e(@$footerContent->data_values->emergency_contact); ?>">
                                    <?php echo e(__($footerContent->data_values->emergency_contact)); ?></a>
                            </h4>
                        </div>
                    </div>
                    <div class="mail-info">
                        <div class="mail-info-thumb">
                            <img src="<?php echo e(getImage('assets/images/frontend/footer/' . @$footerContent->data_values->emergency_email_image)); ?>"
                                alt="<?php echo app('translator')->get('Emergency E-mail'); ?>">
                        </div>
                        <div class="mail-info-content">
                            <h4 class="title">
                                <span><?php echo app('translator')->get('24/7 Email Support'); ?></span>
                                <a href="mailto:<?php echo e(@$footerContent->data_values->emergency_email); ?>"><?php echo e(__($footerContent->data_values->emergency_email)); ?></a>
                            </h4>
                        </div>
                    </div>
                    <span class="dc-or-text">- <?php echo app('translator')->get('OR'); ?> -</span>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- call-to-action section end -->

<!-- footer-section start -->
<footer class="footer-section ptb-80">
    <div class="custom-container">
        <div class="footer-area">
            <div class="row ml-b-30">
                
                <div class="col-lg-4 col-sm-6 mrb-30" style="display: none;">
                    <div class="footer-widget">
                        <h3 class="widget-title"><?php echo app('translator')->get('Department Based Doctors'); ?></h3>
                        <ul class="footer-menus">
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><a href="<?php echo e(route('doctors.departments',$department->id)); ?>"><i class="fas fa-long-arrow-alt-right"></i><?php echo e(__($department->name)); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                
                <div class="col-lg-4 col-sm-6 mrb-30">
                    <div class="footer-widget">
                        <div class="footer-logo">
                            <a href="<?php echo e(route('home')); ?>" class="site-logo">
                                <img src="<?php echo e(getImage(getFilePath('logoIcon') . '/logo.png')); ?>" alt="logo"></a>
                        </div>
                        <p><?php echo e(__($footerContent->data_values->footer_details)); ?></p>
                        <ul class="footer-contact">
                            <?php $__currentLoopData = $contactElement; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($contact->data_values->content =='info@searchavet.com'): ?>
                                    <li><a href="mailto:<?php echo e(__($contact->data_values->content)); ?>" >  <?php echo $contact->data_values->contact_icon ?> <?php echo e(__($contact->data_values->content)); ?> </a> </li>
                                    <?php else: ?> 
                                    <li><?php echo $contact->data_values->contact_icon ?> <?php echo e(__($contact->data_values->content)); ?></li>
                                    <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 mrb-30 ">
                    <div class="footer-widget">
                        <h3 class="widget-title"><?php echo app('translator')->get('Department Based Doctors'); ?></h3>
                        <ul class="footer-menus">
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><a href="<?php echo e(route('doctors.departments',$department->id)); ?>"><i class="fas fa-long-arrow-alt-right"></i><?php echo e(__($department->name)); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-6 mrb-30">
                    <div class="footer-widget">
                        <h3 class="widget-title"><?php echo app('translator')->get('Area Based Doctors'); ?></h3>
                        <ul class="footer-menus" style="padding-top: 23px;">
                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><a href="<?php echo e(route('doctors.states',$state->id)); ?>"><i class="fas fa-long-arrow-alt-right"></i><?php echo e(__($state->name)); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mrb-30">
                    <div class="footer-widget">
                        <h3 class="widget-title"><?php echo e(__($subscribeContent->data_values->heading)); ?></h3>
                        <p><?php echo e(__($subscribeContent->data_values->subheading)); ?></p>
                        <form class="footer-form">
                            <input type="email" name="email" id="subscriber" placeholder="<?php echo app('translator')->get('Enter Your Email'); ?>"
                                autocomplete="off">
                            <button type="submit" class="submit-btn"><i class="lab la-telegram-plane"></i></button>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="privacy-area">
    <div class="custom-container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-sm-2 mrb-30">
                <div class="footer-widget mt-0">
                    <h3 class="widget-title" style="color:white;"><?php echo app('translator')->get('About'); ?></h3>
                    <ul class="footer-about">
                        <li><a href="<?php echo e(route('about')); ?>"><i class="fas fa-long-arrow-alt-right"></i>About Us</a></li>
                        <li><a href="<?php echo e(route('contact')); ?>"><i class="fas fa-long-arrow-alt-right"></i>Contact Us</a></li>
                        <li><a href="<?php echo e(route('terms_of_service')); ?>"><i class="fas fa-long-arrow-alt-right"></i>Terms of Service</a></li>
                        <li><a href="<?php echo e(route('privacy_policy')); ?>"><i class="fas fa-long-arrow-alt-right"></i>Privacy Policy</a></li>
                        <?php if(auth()->guard('user')->user()): ?>
                        <li><a href="<?php echo e(route('feedback')); ?>"><i class="fas fa-long-arrow-alt-right"></i>Feedback</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 mt-5">
                <div class="copyright-area d-flex flex-wrap align-items-center justify-content-center">
                    <div class="copyright">
                        <p><?php echo app('translator')->get('Copyright'); ?> &copy; <?php echo e(\Carbon\Carbon::now()->format('Y')); ?> | <?php echo app('translator')->get('All Rights Reserved'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-sm-6 mrb-30 mt-2">
                <div class="footer-widget">
                    <div class="social-area">
                        <ul class="footer-social d-flex flex-row-reverse">
                            <li style="margin-left: 10px;">
                                <a href="https://www.pinterest.com/" target="_blank">
                                    <img src="<?php echo e(getImage('assets/images/socialIcons/pintrest.png')); ?>">
                                </a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/" target="_blank">
                                    <img src="<?php echo e(getImage('assets/images/socialIcons/linkedIn.png')); ?>">
                                </a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/" target="_blank">
                                    <img src="<?php echo e(getImage('assets/images/socialIcons/youtube.png')); ?>">
                                </a>
                            </li>
                            <li>
                                <a href="https://www.twitter.com/" target="_blank">
                                    <img src="<?php echo e(getImage('assets/images/socialIcons/twitter.png')); ?>">
                                </a>
                            </li>
                            <li>
                                <a href="https://www.facebook.com/" target="_blank">
                                    <img src="<?php echo e(getImage('assets/images/socialIcons/facebook.png')); ?>">
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $__env->startPush('script'); ?>
    <script>
        'use strict';

        $(function() {

            $('.footer-form').on('submit', function(event) {
                event.preventDefault();
                let url = `<?php echo e(route('subscribe')); ?>`;

                let data = {
                    email: $(this).find('input[name=email]').val()
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                    }
                });

                $.post(url, data, function(response) {
                    if (response.errors) {
                        for (var i = 0; i < response.errors.length; i++) {
                            iziToast.error({
                                message: response.errors[i],
                                position: "topRight"
                            });
                        }
                    } else {
                        $('.footer-form').trigger("reset");
                        iziToast.success({
                            message: response.success,
                            position: "topRight"
                        });
                    }
                });
                this.reset();
            })
        })
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/templates/basic/partials/footer.blade.php ENDPATH**/ ?>