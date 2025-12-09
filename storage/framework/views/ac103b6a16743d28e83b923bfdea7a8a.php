<form action="<?php echo e(route('archives.store')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <!-- New project name -->
    <input type="text" name="project_name" placeholder="Enter new project name" required>

    <input type="text" name="version" placeholder="v1.0.0" required>

    <select name="platform">
        <?php $__currentLoopData = $platforms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($platform); ?>"><?php echo e($platform); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <!-- File upload option -->
    <input type="file" name="file">

    <!-- OR GitHub URL option -->
    <input type="url" name="repo_url" placeholder="https://github.com/user/repo">

    <button type="submit">Archive Repository</button>
</form>
<?php /**PATH C:\Users\Asad-Huzaifa\release-manager\resources\views/archives/create.blade.php ENDPATH**/ ?>