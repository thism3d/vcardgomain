<?php

    $company_logo = \App\Models\Utility::GetLogo();
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $users = \Auth::user();
    $bussiness_id = '';
    $bussiness_id = $users->current_business;
    $plan = \App\Models\Plan::getPlansUser($users->plan);
    $module = Nwidart\Modules\Facades\Module::all();
    $activemodule = \App\Models\userActiveModule::getActiveModule();
    $menus = \App\Models\Utility::getMenu();
   

?>

<!-- [ navigation menu ] start -->

<?php if(isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on'): ?>
    <nav class="dash-sidebar light-sidebar transprent-bg">
    <?php else: ?>
        <nav class="dash-sidebar light-sidebar">
<?php endif; ?>

<div class="navbar-wrapper">
    <div class="m-header main-logo">
        <a href="#" class="b-brand">

            <?php if($setting['cust_darklayout'] == 'on'): ?>
                <img src="<?php echo e($logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-light.png') . '?' . time()); ?>"
                    alt="" class="img-fluid" />
            <?php else: ?>
                <img src="<?php echo e($logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time()); ?>"
                    alt="" class="img-fluid" />
            <?php endif; ?>

        </a>
    </div>
    <div class="navbar-content">
        <ul class="dash-navbar">

            <li
                class="dash-item <?php echo e(Request::segment(1) == 'home' || Request::segment(1) == '' || Request::segment(1) == 'dashboard' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('home')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-home"></i></span><span class="dash-mtext"><?php echo e(__('Dashboard')); ?></span></a>

            </li>
            <?php if(Auth::user()->type != 'super admin'): ?>
                <li class="dash-item dash-hasmenu">
                    <a class="dash-link <?php echo e(Request::segment(1) == 'new_business' || Request::segment(1) == 'business' ? 'active' : ''); ?>"
                        data-toggle="collapse" role="button"
                        aria-expanded="<?php echo e(Request::segment(1) == 'new_business' || Request::segment(1) == 'business' ? 'true' : 'false'); ?>"
                        aria-controls="navbar-getting-started"><span class="dash-micon"><i
                                class="ti ti-credit-card"></i></span><span
                            class="dash-mtext"><?php echo e(__('Business')); ?></span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="dash-submenu">
                        <?php if(\Auth::user()->can('create business')): ?>
                            <li class="dash-item <?php echo e(Request::segment(1) == 'new_business' ? 'active' : ''); ?>">
                                <a href="#" class="dash-link" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" data-url="<?php echo e(route('business.create')); ?>"
                                    data-size="xl" data-bs-whatever="<?php echo e(__('Create New Business')); ?>">
                                    <?php echo e(__('Create business')); ?>

                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if(\Auth::user()->can('manage business')): ?>
                            <li class="dash-item <?php echo e(Request::segment(1) == 'business' ? 'active' : ''); ?>">
                                <a class="dash-link" href="<?php echo e(route('business.index')); ?>"><?php echo e(__('Business')); ?></a>

                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>
            <?php if(Auth::user()->type != 'super admin'): ?>

                <li
                    class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'userlogs' ? 'active dash-trigger' : ''); ?>">
                    <a class="dash-link " data-toggle="collapse" role="button"
                        aria-expanded="<?php echo e(Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'userlogs' ? 'true' : 'false'); ?>"
                        aria-controls="navbar-getting-started"><span class="dash-micon"><i
                                class="ti ti-users"></i></span><span class="dash-mtext"><?php echo e(__('Staff')); ?></span><span
                            class="dash-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="dash-submenu">
                        <?php if(Gate::check('manage user')): ?>
                            <li
                                class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'users' ? 'active open' : ''); ?>">
                                <a class="dash-link"
                                    <?php echo e(Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit' ? ' active' : ''); ?>

                                    href="<?php echo e(route('users.index')); ?>"><?php echo e(__('Users')); ?></span></a>
                            </li>
                        <?php endif; ?>
                        <?php if(Auth::user()->type == 'company'): ?>
                            <li
                                class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'roles' ? 'active open' : ''); ?>">
                                <a class="dash-link" href="<?php echo e(route('roles.index')); ?>"><?php echo e(__('Roles')); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php else: ?>
                <li class="dash-item <?php echo e(Request::segment(1) == 'users' ? 'active open' : ''); ?>">
                    <a href="<?php echo e(route('users.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-users"></i></span><span
                            class="dash-mtext"><?php echo e(__('Companies')); ?></span></a>

                </li>
            <?php endif; ?>
            <?php if(Auth::user()->type == 'super admin'): ?>
                <li class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'nfc' ? 'active dash-trigger' : ''); ?>">
                    <a class="dash-link " data-toggle="collapse" role="button"
                        aria-expanded="<?php echo e(Request::segment(1) == 'nfc' ? 'true' : 'false'); ?>"
                        aria-controls="navbar-getting-started"><span class="dash-micon"><i
                                class="ti ti-nfc"></i></span><span
                            class="dash-mtext"><?php echo e(__('NFC Business Card')); ?></span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="dash-submenu">

                        <li class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'nfc' ? 'active open' : ''); ?>">
                            <a class="dash-link"
                                <?php echo e(Request::route()->getName() == 'nfc.index' || Request::route()->getName() == 'nfc.create' || Request::route()->getName() == 'nfc.edit' ? ' active' : ''); ?>

                                href="<?php echo e(route('nfc.index')); ?>"><?php echo e(__('NFC Card')); ?></span></a>
                        </li>


                        <li
                            class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'nfcorder' ? 'active open' : ''); ?>">
                            <a class="dash-link"
                                href="<?php echo e(route('order.request.index')); ?>"><?php echo e(__('Order Request')); ?></a>
                        </li>

                    </ul>
                </li>
            <?php endif; ?>
            <?php if(Auth::user()->type == 'company'): ?>
                <li class="dash-item <?php echo e(Request::segment(1) == 'nfc' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('nfc.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-nfc"></i></span><span class="dash-mtext"><?php echo e(__('NFC Card')); ?></span></a>

                </li>
            <?php endif; ?>

            <?php if(\Auth::user()->can('manage appointment')): ?>
                <li class="dash-item <?php echo e(Request::segment(1) == 'appointments' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('appointments.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-calendar-time"></i></span><span
                            class="dash-mtext"><?php echo e(__('Appointments')); ?></span></a>

                </li>
            <?php endif; ?>
            <?php if(\Auth::user()->can('manage contact')): ?>
                <li class="dash-item <?php echo e(Request::segment(1) == 'contacts' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('contacts.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-phone"></i></span><span
                            class="dash-mtext"><?php echo e(__('Contacts')); ?></span></a>

                </li>
            <?php endif; ?>
            <?php if(\Auth::user()->can('calendar appointment')): ?>
                <li class="dash-item <?php echo e(Request::segment(1) == 'appointment-calendar' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('appointment.calendar')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-calendar"></i></span><span
                            class="dash-mtext"><?php echo e(__('Calendar')); ?></span></a>

                </li>
                </li>
            <?php endif; ?>


            <?php if(count($menus) > 0 && Auth::user()->type != 'super admin'): ?>
                <li class="dash-item dash-hasmenu">
                    <a class="dash-link <?php echo e(Request::segment(1) == 'employee' || Request::segment(1) == 'client' ? 'active' : ''); ?>"
                        data-toggle="collapse" role="button"
                        aria-expanded="<?php echo e(Request::segment(1) == 'employee' || Request::segment(1) == 'client' ? 'true' : 'false'); ?>"
                        aria-controls="navbar-getting-started"><span class="dash-micon"><i
                                class="ti ti-qrcode"></i></span><span
                            class="dash-mtext"><?php echo e(__('Qr Code')); ?></span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="dash-submenu">
                        <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="dash-item dash-hasmenu">
                                    <a class="dash-link"
                                        <?php echo e(Request::route()->getName() == $module['module'] ? 'active' : ''); ?>

                                        href="<?php echo e(route($module['route'])); ?>"><?php echo e(\App\Models\Utility::Module_Alias_Name($module['module'])); ?></span></a>
                                </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                    </ul>
                </li>
            <?php endif; ?>
            <?php if(\Auth::user()->type != 'super admin'): ?>
            <li
                class="dash-item <?php echo e(\Request::route()->getName() == 'marketplace' || \Request::route()->getName() == 'marketplace' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('marketplace.home')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-briefcase "></i></span><span class="dash-mtext"><?php echo e(__('Business Directory')); ?></span></a>

            </li>
            <?php endif; ?>
            <?php if(\Auth::user()->can('manage plan')): ?>
                <li
                    class="dash-item <?php echo e(\Request::route()->getName() == 'plans' || \Request::route()->getName() == 'stripe' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('plans.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-award "></i></span><span
                            class="dash-mtext"><?php echo e(__('Plans')); ?></span></a>

                </li>
            <?php endif; ?>
            <?php if(Auth::user()->type == 'company'): ?>
                <li class="dash-item <?php echo e(Request::route()->getName() == 'referral.index' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('referral.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-discount-2"></i></span><span
                            class="dash-mtext"><?php echo e(__('Referral Program')); ?></span></a>

                </li>
            <?php endif; ?>
            <?php if(Auth::user()->type == 'super admin'): ?>
                <li class="dash-item <?php echo e(Request::route()->getName() == 'plan_request.index' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('plan_request.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-brand-telegram"></i></span><span
                            class="dash-mtext"><?php echo e(__('Plan Request')); ?></span></a>

                </li>
                <li class="dash-item <?php echo e(Request::route()->getName() == 'referral.index' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('referral.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-discount-2"></i></span><span
                            class="dash-mtext"><?php echo e(__('Referral Program')); ?></span></a>

                </li>
                <li class="dash-item <?php echo e(Request::route()->getName() == 'domain_request.index' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('domain_request.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-browser"></i></span><span
                            class="dash-mtext"><?php echo e(__('Domain Request')); ?></span></a>

                </li>
                <li class="dash-item <?php echo e(Request::segment(1) == 'coupons' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('coupons.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-gift"></i></span><span class="dash-mtext"><?php echo e(__('Coupons')); ?></span></a>

                </li>

                <li class="dash-item <?php echo e(Request::segment(1) == 'order' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('order.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-shopping-cart"></i></span><span
                            class="dash-mtext"><?php echo e(__('Order')); ?></span></a>

                </li>
                <li class="dash-item <?php echo e(Request::segment(1) == 'email_template_lang' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('manage.email.language', \Auth::user()->lang)); ?>" class="dash-link"><span
                            class="dash-micon"><i class="ti ti-mail"></i></span><span
                            class="dash-mtext"><?php echo e(__('Email Template')); ?></span></a>

                </li>
            <?php endif; ?>

            <?php if(\Auth::user()->type == 'super admin'): ?>
                <?php echo $__env->make('landingpage::menu.landingpage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
            <?php if(Auth::user()->type == 'super admin'): ?>
                <li class="dash-item <?php echo e(Request::segment(1) == 'add-on' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('module.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-layout-2"></i></span><span
                            class="dash-mtext"><?php echo e(__('Add-on Manager')); ?></span></a>

                </li>
            <?php endif; ?>
            <?php if(\Auth::user()->type == 'super admin'): ?>
            <li
                class="dash-item <?php echo e(\Request::route()->getName() == 'marketplace' || \Request::route()->getName() == 'marketplace' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('marketplace.home')); ?>" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-briefcase "></i></span><span class="dash-mtext"><?php echo e(__('Business Directory')); ?></span></a>

            </li>
            <?php endif; ?>
            <?php if(\Auth::user()->type == 'super admin'): ?>
                <li
                    class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'campaigns' ? 'active dash-trigger' : ''); ?>">
                    <a class="dash-link " data-toggle="collapse" role="button"
                        aria-expanded="<?php echo e(Request::segment(1) == 'campaigns' ? 'true' : 'false'); ?>"
                        aria-controls="navbar-getting-started"><span class="dash-micon"><i
                                class="ti ti-nfc"></i></span><span
                            class="dash-mtext"><?php echo e(__('Marketplace')); ?></span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="dash-submenu">

                        <li
                            class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'campaigns' ? 'active open' : ''); ?>">
                            <a class="dash-link"
                                <?php echo e(Request::route()->getName() == 'campaigns.index' || Request::route()->getName() == 'campaigns.create' || Request::route()->getName() == 'campaigns.edit' ? ' active' : ''); ?>

                                href="<?php echo e(route('campaigns.index')); ?>"><?php echo e(__('Campaigns')); ?></span></a>
                        </li>
                        <li
                            class="dash-item dash-hasmenu <?php echo e(Request::route()->getName() == 'category.index' || Request::route()->getName() == 'business.setup'); ?>">
                            <a class="dash-link" href="<?php echo e(route('category.index')); ?>"><?php echo e(__('System Setup')); ?></a>
                        </li>

                    </ul>
                </li>
            <?php else: ?>
                <li class="dash-item <?php echo e(Request::segment(1) == 'campaigns' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('campaigns.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-package"></i></span><span
                            class="dash-mtext"><?php echo e(__('Campaigns')); ?></span></a>

                </li>
            <?php endif; ?>
          
            <?php if(\Auth::user()->can('manage company setting')): ?>
                <li class="dash-item <?php echo e(Request::segment(1) == 'systems' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('systems.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-settings"></i></span><span
                            class="dash-mtext"><?php echo e(__('Settings')); ?></span></a>

                </li>
            <?php elseif(\Auth::user()->can('manage system setting')): ?>
                <li class="dash-item <?php echo e(Request::segment(1) == 'systems' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('systems.index')); ?>" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-settings"></i></span><span
                            class="dash-mtext"><?php echo e(__('Settings')); ?></span></a>

                </li>
            <?php endif; ?>

        </ul>
    </div>
</div>
</nav>
<?php /**PATH /Applications/MAMP/htdocs/vcardgo/resources/views/partials/admin/sidemenu.blade.php ENDPATH**/ ?>