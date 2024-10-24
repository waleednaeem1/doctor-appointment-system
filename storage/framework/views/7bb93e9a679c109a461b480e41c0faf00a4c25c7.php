<?php
	$customCaptcha = loadCustomCaptcha();
    $googleCaptcha = loadReCaptcha()
?>
<?php if($googleCaptcha): ?>
    <div class="mb-3">
        <?php echo $googleCaptcha ?>
    </div>
<?php endif; ?>
<?php if($customCaptcha): ?>
    <div class="form-group">
        <div class="mb-2">
            <?php echo $customCaptcha ?>
        </div>
        <label class="form-label text--black"><?php echo app('translator')->get('Captcha'); ?></label>
        <input type="text" name="captcha" style="border: 1px solid black;" class="form-control form--control text--black" required>
    </div>
<?php endif; ?>
<?php if($googleCaptcha): ?>
<?php $__env->startPush('script'); ?>
    <script>
        (function($){
            "use strict"
            $('.verify-gcaptcha').on('submit',function(){
                var response = grecaptcha.getResponse();
                if (response.length == 0) {
                    document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger"><?php echo app('translator')->get("Captcha field is required."); ?></span>';
                    return false;
                }
                return true;
            });
        })(jQuery);
    </script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH /Users/dev/Documents/Personal Projects/doctor-appointment-system/resources/views/partials/captcha.blade.php ENDPATH**/ ?>