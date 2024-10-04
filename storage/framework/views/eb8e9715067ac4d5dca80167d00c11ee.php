<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Login')); ?>

<?php $__env->stopSection(); ?>
<?php
    $languages = App\Models\Utility::languages();

    $logo = asset(Storage::url('uploads/logo/'));
    $company_logo = Utility::getValByName('company_logo');
    $settings = Utility::settings();
    $recaptcha = \App\Models\Utility::setCaptchaConfig();

?>
<?php $__env->startSection('language-bar'); ?>
    <div class="lang-dropdown-only-desk">
        <li class="dropdown dash-h-item drp-language">
            <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="drp-text"> <?php echo e($languages[$lang]); ?>

                </span>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('login', $code)); ?>"tabindex="0"
                        class="dropdown-item <?php echo e($code == $lang ? 'active' : ''); ?> ">
                        <span><?php echo e(Str::ucFirst($language)); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </li>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('custom-scripts'); ?>
    <?php if($settings['RECAPTCHA_MODULE'] == 'yes'): ?>
        <?php if(isset($settings['RECAPTCHA_VERSION']) && $settings['RECAPTCHA_VERSION'] == 'v2'): ?>
            <?php echo NoCaptcha::renderJs(); ?>

        <?php else: ?>
            <script src="https://www.google.com/recaptcha/api.js?render=<?php echo e($settings['NOCAPTCHA_SITEKEY']); ?>"></script>
            <script>
               document.addEventListener("DOMContentLoaded", function() {
                grecaptcha.ready(function() {
                    grecaptcha.execute('<?php echo e($settings['NOCAPTCHA_SITEKEY']); ?>', { action: 'submit' }).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                    });
                });
            });
            </script>
        <?php endif; ?>
    <?php endif; ?>
    <script>
        $(document).ready(function() {
            $("#loginForm").submit(function(e) {
                $("#saveBtn").attr("disabled", true);
                return true;
            });
        });
    </script>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
    <!-- [ auth-signup ] start -->


    <div class="card-body">
        <div>
            <h2 class="mb-3 f-w-600"><?php echo e(__('Login')); ?></h2>
        </div>
        <?php echo e(Form::open(['route' => 'login', 'method' => 'post', 'id' => 'loginForm', 'class' => 'login-form'])); ?>

        <div class="custom-login-form">
            <div class="form-group mb-3">
                <label class="form-label"><?php echo e(__('Email')); ?></label>
                <?php echo e(Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('Enter Your Email')])); ?>

                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error invalid-email text-danger" role="alert">
                        <strong><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group mb-3">
                <label class="form-label"><?php echo e(__('Password')); ?></label>
                <?php echo e(Form::password('password', ['class' => 'form-control', 'placeholder' => __('Enter Your Password'), 'id' => 'input-password'])); ?>

                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error invalid-password text-danger" role="alert">
                        <strong><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group mb-4">
                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    <?php if(Route::has('password.request')): ?>
                        <span><a href="<?php echo e(route('password.request', $lang)); ?>"
                                tabindex="0"><?php echo e(__('Forgot your password?')); ?></a></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php if($settings['RECAPTCHA_MODULE'] == 'yes'): ?>
                <?php if(isset($settings['RECAPTCHA_VERSION']) && $settings['RECAPTCHA_VERSION'] == 'v2'): ?>
                    <div class="form-group col-lg-12 col-md-12 mt-3">
                        <?php echo NoCaptcha::display($settings['cust_darklayout'] == 'on' ? ['data-theme' => 'dark'] : []); ?>

                        <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="small text-danger" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php else: ?>
                    <div class="form-group col-lg-12 col-md-12 mt-3">
                        <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" class="form-control">
                        <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="error small text-danger" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="d-grid">
                <?php echo e(Form::submit(__('Login'), ['class' => 'btn btn-primary mt-2', 'id' => 'saveBtn'])); ?>

            </div>
            <?php if(Utility::getValByName('signup_button') == 'on'): ?>
                <p class="my-4 text-center"><?php echo e(__("Don't have an account?")); ?>

                    <a href="<?php echo e(url('register', $lang)); ?>" tabindex="0"><?php echo e(__('Register')); ?></a>
                </p>
            <?php endif; ?>
        </div>
        <?php echo e(Form::close()); ?>


    </div>

    
    <!-- [ auth-signup ] end -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/vcardgo/resources/views/auth/login.blade.php ENDPATH**/ ?>