
<?php $__env->startSection('content'); ?>
<h1>Upload Archive</h1>
<form action="<?php echo e(route('archives.store')); ?>" method="post" enctype="multipart/form-data">
  <?php echo csrf_field(); ?>
  <label>Project</label>
  <select name="project_id"><?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
  <label>Version</label>
  <input name="version" placeholder="v1.0.0">
  <label>Platform</label>
  <select name="platform"><?php $__currentLoopData = $platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($pl); ?>"><?php echo e($pl); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
  <label>File</label>
  <input type="file" name="file">
  <button type="submit">Upload</button>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Asad-Huzaifa\release-manager\resources\views/archives/create.blade.php ENDPATH**/ ?>