<?php
    $cardLogo = \App\Models\Utility::get_file('card_logo');
?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Business')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
    <?php echo e(__('Business')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Business')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-btn'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create business')): ?>
        <div class="col-xl-12 col-lg-12 col-md-12 d-flex align-items-center justify-content-between justify-content-md-end"
            data-bs-placement="top">
            <a href="#" data-size="xl" data-url="<?php echo e(route('business.create')); ?>" data-ajax-popup="true"
                data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-title="<?php echo e(__('Create New Business')); ?>"
                class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
        <div class=" mt-2 " id="multiCollapseExample1" style="">
            <div class="card">
                <div class="card-body">
                    <?php echo e(Form::open(['route' => ['business.index'], 'method' => 'get', 'id' => 'business_filter'])); ?>

                    <div class="d-flex align-items-center justify-content-end">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                <?php echo e(Form::label('business', __('Name'), ['class' => 'form-label'])); ?>

                                <input type="text" name="business" class="form-control"
                                    value="<?php echo e(isset($_GET['business']) ? $_GET['business'] : ''); ?>"
                                    placeholder ="Enter a business name">
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                <?php echo e(Form::label('start_date', __('Start date'), ['class' => 'form-label'])); ?>

                                <input type="date" name="start_date" class="form-control"
                                    value="<?php echo e(isset($_GET['start_date']) ? $_GET['start_date'] : ''); ?>" placeholder ="">
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                <?php echo e(Form::label('end_date', __('End date'), ['class' => 'form-label'])); ?>

                                <input type="date" name="end_date" class="form-control"
                                    value="<?php echo e(isset($_GET['end_date']) ? $_GET['end_date'] : ''); ?>" placeholder ="">
                            </div>
                        </div>

                        <div class="col-auto float-end ms-2 mt-4">
                            <a href="#" class="btn btn-sm btn-primary"
                                onclick="document.getElementById('business_filter').submit(); return false;"
                                data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="<?php echo e(route('business.index')); ?>" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                title="" data-bs-original-title="Reset">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                            </a>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('#')); ?></th>
                                    <th><?php echo e(__('Business Logo')); ?></th>
                                    <th><?php echo e(__('Businesses')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Generate Date')); ?></th>
                                    <th><?php echo e(__('Directory')); ?></th>
                                    <th class="text-end"><?php echo e(__('Operations')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $business; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="<?php echo e($val->admin_enable == 'off' ? 'row-disabled' : ''); ?>">
                                        <td><?php echo e($loop->index + 1); ?></td>
                                        <td>
                                            <div class="avatar">
                                                <img style="width: 55px;height: 55px;" class="rounded-circle "
                                                    src="<?php echo e(isset($val->logo) && !empty($val->logo) ? $cardLogo . '/' . $val->logo : asset('custom/img/logo-placeholder-image-21.png')); ?>"
                                                    alt="">
                                            </div>
                                        </td>

                                        <td class="<?php echo e($val->status == 'locked' ? 'row-disabled' : ''); ?>">
                                            <a class="" href="<?php echo e(route('business.edit', $val->id)); ?>"
                                                class=""><b><?php echo e(ucFirst($val->title)); ?></b></a>
                                        </td>
                                        <td><span
                                                class="badge fix_badge <?php if($val->status == 'locked'): ?> bg-danger <?php else: ?> bg-info <?php endif; ?> p-2 px-3 rounded"><?php echo e(ucFirst($val->status)); ?></span>
                                        </td>

                                        <?php
                                            $now = $val->created_at;
                                            $date = $now->format('Y-m-d');
                                            $time = $now->format('H:i:s');
                                        ?>
                                        <td><?php echo e($val->created_at); ?></td>
                                        <td><span
                                                class="badge fix_badge <?php if($val->directory_status == 'off'): ?> bg-danger <?php else: ?> bg-info <?php endif; ?> p-2 px-3 rounded"><?php echo e($val->directory_status == 'on' ? 'Active' : 'Deactive'); ?></span>
                                        </td>
                                        <td class="text-end">

                                            <div class="action-btn bg-dark ms-2">
                                                <a href="#"
                                                    class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-confirm="<?php echo e(__('You want to confirm this action')); ?>"
                                                    data-text="<?php echo e(__('Press Yes to continue or No to go back')); ?>"
                                                    data-confirm-yes="duplicate-form-<?php echo e($val->id); ?>"
                                                    title="<?php echo e(__('Duplicate')); ?>" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"><span class="text-white"><i
                                                            class="ti ti-copy"></i></span></a>
                                                <?php echo Form::open([
                                                    'method' => 'POST',
                                                    'route' => ['business.duplicate', $val->id],
                                                    'id' => 'duplicate-form-' . $val->id,
                                                ]); ?>

                                                <?php echo Form::close(); ?>


                                            </div>
                                            <div class="action-btn bg-success  ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center cp_link"
                                                    data-link="<?php echo e(url('/' . $val->slug)); ?>" data-bs-toggle="tooltip"
                                                    data-bs-original-title="<?php echo e(__('Click to copy card link')); ?>"
                                                    onclick="copyToClipboard(this)"> <span class="text-white"> <i
                                                            class="ti ti-link text-white"></i></span></a>
                                            </div>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view analytics business')): ?>
                                                <div class="action-btn bg-secondary  ms-2">
                                                    <a href="<?php echo e(route('business.analytics', $val->id)); ?>"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="<?php echo e(__('Business Analytics')); ?>"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-brand-google-analytics  text-white"></i></span></a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('calendar appointment')): ?>
                                                <div class="action-btn bg-warning  ms-2">
                                                    <a href="<?php echo e(route('appointment.calendar', $val->id)); ?>"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="<?php echo e(__('Business Calender')); ?>"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-calendar text-white"></i></span></a>
                                                </div>
                                            <?php endif; ?>
                                            <div class="action-btn bg-dark  ms-2">
                                                <a href="#" data-size="md"
                                                    data-url="<?php echo e(route('business.qrcode', $val->id)); ?>"
                                                    data-ajax-popup="true" data-bs-toggle="tooltip"
                                                    title="<?php echo e(__('QR Code')); ?>" data-title="<?php echo e(__('Qr Code')); ?>"
                                                    class="btn btn-sm   "><i class="fa fa-qrcode text-white"></i></a>
                                            </div>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage contact')): ?>
                                                <div class="action-btn bg-success  ms-2">
                                                    <a href="<?php echo e(route('business.contacts.show', $val->id)); ?>"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="<?php echo e(__('Business Contacts')); ?>"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-phone text-white"></i></span></a>
                                                </div>
                                            <?php endif; ?>
                                            <div class="action-btn bg-primary  ms-2">
                                                <a class="btn btn-sm  btn-icon ml-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="bottom"
                                                    data-bs-original-title="<?php echo e(__('Preview')); ?>"
                                                    href="<?php echo e(url('/' . $val->slug)); ?>" target="-blank"><span
                                                        class="text-white"><i class="ti ti-eye"></i></span></a>
                                            </div>
                                            <?php if($val->status != 'locked'): ?>
                                                <div class="action-btn bg-info  ms-2">
                                                    <a href="<?php echo e(route('business.edit', $val->id)); ?>"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="<?php echo e(__('Business Edit')); ?>"> <span
                                                            class="text-white"> <i
                                                                class="ti ti-edit text-white"></i></span></a>
                                                </div>


                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete business')): ?>
                                                    <div class="action-btn bg-danger ms-2">
                                                        <a href="#"
                                                            class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                            data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                            data-confirm-yes="delete-form-<?php echo e($val->id); ?>"
                                                            title="<?php echo e(__('Delete')); ?>" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"><span class="text-white"><i
                                                                    class="ti ti-trash"></i></span></a>
                                                    </div>
                                                    <?php echo Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['business.destroy', $val->id],
                                                        'id' => 'delete-form-' . $val->id,
                                                    ]); ?>

                                                    <?php echo Form::close(); ?>

                                                <?php else: ?>
                                                    <span class="edit-icon align-middle bg-gray"><i
                                                            class="fas fa-lock text-white"></i></span>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                        </td>

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('custom-scripts'); ?>
    <script type="text/javascript">
        function copyToClipboard(element) {
            var value = $(element).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('<?php echo e(__('Success')); ?>', '<?php echo e(__('Link Copy on Clipboard')); ?>', 'success');
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/vcardgo/resources/views/business/index.blade.php ENDPATH**/ ?>