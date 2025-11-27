

<?php $__env->startSection('content'); ?>
<h1>Archives</h1>

<!-- Create Archive Button -->
<a href="<?php echo e(route('archives.create')); ?>" style="display:inline-block; margin-bottom:15px; padding:8px 12px; background:#4CAF50; color:white; text-decoration:none; border-radius:4px;">+ Create Archive</a>

<!-- Filter Form -->
<form method="get" class="mb-4">
  <select name="project_id">
    <option value="">All projects</option>
    <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <option value="<?php echo e($p->id); ?>" <?php echo e(request('project_id')==$p->id?'selected':''); ?>><?php echo e($p->name); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </select>

  <input type="text" name="version" placeholder="v1.0.0" value="<?php echo e(request('version')); ?>">

  <select name="platform">
    <option value="">All</option>
    <option value="web">web</option>
    <option value="android_phone">android_phone</option>
    <option value="android_tv">android_tv</option>
    <option value="ios">ios</option>
  </select>

  <button type="submit">Filter</button>
</form>

<!-- Archives Table -->
<table>
<thead>
<tr>
  <th>Project</th>
  <th>Version</th>
  <th>Platform</th>
  <th>Commit</th>
  <th>File</th>
  <th>Action</th>
</tr>
</thead>
<tbody>
<?php $__currentLoopData = $archives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
  <td><?php echo e($a->project->name); ?></td>
  <td><?php echo e($a->version); ?></td>
  <td><?php echo e($a->platform); ?></td>
  <td><?php echo e($a->commit_hash ?? '-'); ?></td>
  <td><?php echo e($a->file_path ? basename($a->file_path) : '-'); ?></td>
  <td>
    <?php if($a->file_path): ?>
      <a href="<?php echo e(route('archives.download', $a)); ?>">Download</a>
    <?php endif; ?>
  </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
</table>

<?php echo e($archives->links()); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Asad-Huzaifa\release-manager\resources\views/archives/index.blade.php ENDPATH**/ ?>