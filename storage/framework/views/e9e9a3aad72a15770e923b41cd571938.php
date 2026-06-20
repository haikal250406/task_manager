

<?php $__env->startSection('title', 'Daftar Tugas'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4 px-3">
    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4">
        <div>
            <h1 class="display-5 fw-bold text-dark">Daftar Tugas</h1>
            <p class="text-muted mb-0">Kelola dan lacak semua tugas proyek Anda</p>
        </div>
        <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary rounded-3 px-4 py-2 fw-medium shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Tugas
        </a>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form method="GET" action="<?php echo e(route('tasks.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari judul atau deskripsi..." 
                                   value="<?php echo e(request('search')); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="project_id" class="form-select">
                            <option value="">Semua Proyek</option>
                            <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($project->id); ?>" <?php echo e(request('project_id') == $project->id ? 'selected' : ''); ?>>
                                    <?php echo e($project->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="to_do" <?php echo e(request('status') == 'to_do' ? 'selected' : ''); ?>>To Do</option>
                            <option value="in_progress" <?php echo e(request('status') == 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                            <option value="done" <?php echo e(request('status') == 'done' ? 'selected' : ''); ?>>Done</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="priority" class="form-select">
                            <option value="">Semua Prioritas</option>
                            <option value="low" <?php echo e(request('priority') == 'low' ? 'selected' : ''); ?>>Low</option>
                            <option value="medium" <?php echo e(request('priority') == 'medium' ? 'selected' : ''); ?>>Medium</option>
                            <option value="high" <?php echo e(request('priority') == 'high' ? 'selected' : ''); ?>>High</option>
                            <option value="critical" <?php echo e(request('priority') == 'critical' ? 'selected' : ''); ?>>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks Table -->
    <?php if($tasks->count() > 0): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Judul Tugas</th>
                                <th>Proyek</th>
                                <th>Status</th>
                                <th>Prioritas</th>
                                <th>Deadline</th>
                                <th>Assigned To</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e($task->isOverdue() ? 'table-warning' : ''); ?>">
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark"><?php echo e($task->title); ?></div>
                                        <?php if($task->description): ?>
                                            <small class="text-muted"><?php echo e(Str::limit($task->description, 60)); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <?php echo e($task->project->name ?? '-'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo e($task->getStatusBadgeClass()); ?>">
                                            <?php echo e($task->getStatusText()); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo e($task->getPriorityBadgeClass()); ?>">
                                            <?php echo e($task->getPriorityText()); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($task->deadline): ?>
                                            <div class="<?php echo e($task->isOverdue() ? 'text-danger fw-bold' : 'text-muted'); ?>">
                                                <i class="far fa-calendar me-1"></i>
                                                <?php echo e($task->deadline->format('d M Y')); ?>

                                            </div>
                                            <?php if($task->isOverdue()): ?>
                                                <small class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Terlambat</small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <small class="text-muted">-</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($task->user): ?>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo e($task->user->getAvatarUrl()); ?>" 
                                                     alt="<?php echo e($task->user->name); ?>" 
                                                     class="rounded-circle me-2" width="30" height="30">
                                                <small><?php echo e($task->user->name); ?></small>
                                            </div>
                                        <?php else: ?>
                                            <small class="text-muted">Unassigned</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="btn btn-sm btn-outline-primary" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('tasks.destroy', $task)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"
                                                        onclick="return confirm('Yakin ingin menghapus tugas <?php echo e($task->title); ?>?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php if($tasks->hasPages()): ?>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        <?php echo e($tasks->links()); ?>

                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-clipboard-list fa-4x text-muted opacity-25 mb-3"></i>
                <h4 class="text-muted">Belum ada tugas</h4>
                <p class="text-muted mb-4">Mulai buat tugas pertama Anda untuk proyek ini</p>
                <a href="<?php echo e(route('tasks.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Tambah Tugas Pertama
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\task_manager\resources\views/tasks/index.blade.php ENDPATH**/ ?>