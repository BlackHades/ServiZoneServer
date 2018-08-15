<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('beautymail::templates.widgets.newfeatureStart', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <p class="secondary"><strong>Hello <?php echo e(isset($name) ? $name : "User"); ?>, </strong></p>
    <p>Kindly use the code <?php echo e(isset($code) ? $code : "Code"); ?> for your verification. It expires in 30 minutes.</p>
    <p>Thank You.</p>
    <p>Powered By OneflareTech.</p>

    <?php echo $__env->make('beautymail::templates.widgets.newfeatureEnd', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('beautymail::templates.widgets', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>