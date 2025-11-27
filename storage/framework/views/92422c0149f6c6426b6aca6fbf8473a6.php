

<?php $__env->startSection('content'); ?>
<h1>Release Notes</h1>

<?php $__currentLoopData = $notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div>
        <h3><?php echo e($note->version); ?></h3>
        <?php echo $note->notes; ?>

    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php echo e($notes->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Asad-Huzaifa\release-manager\resources\views/release_notes/index.blade.php ENDPATH**/ ?>