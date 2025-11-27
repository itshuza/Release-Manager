

<?php $__env->startSection('content'); ?>
<h1>Create Release Note</h1>


<?php if($errors->any()): ?>
    <div style="color:red; margin-bottom: 15px;">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <p><?php echo e($error); ?></p>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<form action="<?php echo e(route('release-notes.store')); ?>" method="post">
    <?php echo csrf_field(); ?>

    <label>Archive (optional)</label>
    <select name="archive_id">
        <option value="">--none--</option>
        <?php $__currentLoopData = $archives; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($a->id); ?>">
                <?php echo e($a->project->name); ?> - <?php echo e($a->version); ?> - <?php echo e($a->platform); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <br><br>

    <label>Version</label>
    <input name="version" placeholder="v1.0.0" required>

    <br><br>

    <label>Release date</label>
    <input type="date" name="release_date">

    <br><br>

    <label>Notes</label>
    <textarea id="summernote" name="notes"></textarea>

    <br>

    <button type="submit">Save</button>
</form>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('scripts'); ?>
    <!-- Summernote CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    <script>
        $(function() {
            $('#summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['codeview']]
                ]
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Asad-Huzaifa\release-manager\resources\views/release_notes/create.blade.php ENDPATH**/ ?>