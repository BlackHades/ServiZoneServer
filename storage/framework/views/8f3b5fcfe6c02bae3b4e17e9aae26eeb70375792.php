<?php $__env->startSection('content'); ?>

	<?php echo $__env->make('beautymail::templates.widgets.articleStart', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<h4 class="secondary"><strong>Hello World</strong></h4>
		<p>This is a test</p>

	<?php echo $__env->make('beautymail::templates.widgets.articleEnd', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


	<?php echo $__env->make('beautymail::templates.widgets.newfeatureStart', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		<h4 class="secondary"><strong>Hello World again</strong></h4>
		<p>This is another test</p>

	<?php echo $__env->make('beautymail::templates.widgets.newfeatureEnd', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('beautymail::templates.widgets', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>