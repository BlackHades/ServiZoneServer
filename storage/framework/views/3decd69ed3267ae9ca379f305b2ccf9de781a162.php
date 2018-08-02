<div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden; background: <?php echo e($color); ?>">
    <div class="dimmer"></div>
    <div class="panel-content">
        <?php if(isset($icon)): ?><i class='<?php echo e($icon); ?>'></i><?php endif; ?>
        <h4><a href="<?php echo e($button['link']); ?>"><?php echo e($title); ?></a></h4>
    </div>
</div>