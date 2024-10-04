<?php
    $admin_payment_setting = Utility::getAdminPaymentSetting();
?>
<div class="table-responsive">
    <table class="table ">
        <tbody>
        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td>
                    <div class="font-style font-weight-bold"><?php echo e($plan->name); ?> (<?php echo e(isset($admin_payment_setting['CURRENCY_SYMBOL']) ? $admin_payment_setting['CURRENCY_SYMBOL'] : '$'); ?><?php echo e($plan->price); ?>) <?php echo e(' / '. $plan->duration); ?></div>
                </td>
                <td><?php echo e(__('Business')); ?> : <?php echo e($plan->business); ?></td>
                <td>
                    <?php if($user->plan==$plan->id): ?>
                        <button type="button" class="btn btn-xs btn-soft-success btn-icon">
                            <span class="btn-inner--icon"><i class="fas fa-check"></i></span>
                            <span class="btn-inner--text"><?php echo e(__('Active')); ?></span>
                        </button>
                    <?php else: ?>
                        <div>
                            <a href="<?php echo e(route('plan.active',[$user->id,$plan->id])); ?>" class="btn btn-sm btn-primary btn-icon" title="<?php echo e(__('Click to Upgrade Plan')); ?>"><i class="ti ti-shopping-cart"></i> <?php echo e(__('Upgrade')); ?></a>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php /**PATH /Applications/MAMP/htdocs/vcardgo/resources/views/user/plan.blade.php ENDPATH**/ ?>