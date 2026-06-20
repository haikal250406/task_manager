<?php $__env->startSection('title', $project->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-folder-open text-primary fa-2x"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h2 class="fw-bold mb-2"><?php echo e($project->name); ?></h2>
                            <p class="text-muted mb-2"><?php echo e($project->description ?? 'Tidak ada deskripsi'); ?></p>
                            <div class="d-flex gap-3 flex-wrap">
                                <span class="badge bg-primary"><?php echo e(ucfirst($project->status)); ?></span>
                                <span class="text-muted small">
                                    <i class="far fa-calendar me-1"></i>Dibuat <?php echo e($project->created_at->format('d M Y')); ?>

                                </span>
                                <span class="text-muted small">
                                    <i class="far fa-user me-1"></i><?php echo e($project->user->name ?? 'Unknown'); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <a href="<?php echo e(route('tasks.create', ['project_id' => $project->id])); ?>" class="btn btn-primary btn-lg mb-2">
                        <i class="fas fa-plus me-2"></i>Tambah Tugas Baru
                    </a>
                    <br>
                    <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="mt-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-semibold">Progres Proyek</span>
                    <span class="fw-bold text-primary"><?php echo e($progressPercentage ?? 0); ?>%</span>
                </div>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: <?php echo e($progressPercentage ?? 0); ?>%" 
                         aria-valuenow="<?php echo e($progressPercentage ?? 0); ?>" aria-valuemin="0" aria-valuemax="100">
                        <?php echo e($completedCount ?? 0); ?>/<?php echo e($totalTasks ?? 0); ?> Tugas Selesai
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($notifications) && count($notifications) > 0): ?>
        <div class="mb-4">
            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="alert alert-<?php echo e($notif['type']); ?> alert-dismissible fade show shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas <?php echo e($notif['icon']); ?> fa-lg me-3"></i>
                        <div class="flex-grow-1">
                            <strong><?php echo e($notif['message']); ?></strong>
                            <?php if(isset($notif['tasks']) && $notif['tasks']->count() > 0): ?>
                                <ul class="mb-0 mt-1 small">
                                    <?php $__currentLoopData = $notif['tasks']->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($task->title); ?> 
                                            <?php if($task->deadline): ?>
                                                (Deadline: <?php echo e($task->deadline->format('d M Y')); ?>)
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($notif['tasks']->count() > 3): ?>
                                        <li class="text-muted">... dan <?php echo e($notif['tasks']->count() - 3); ?> tugas lainnya</li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-info bg-opacity-10">
                <div class="card-body text-center p-4">
                    <div class="mb-2"><i class="fas fa-tasks fa-2x text-info"></i></div>
                    <h3 class="fw-bold text-info mb-1"><?php echo e($tasksBaru->count()); ?></h3>
                    <small class="text-muted">Tugas Baru</small>
                    <?php if($tasksBaru->count() > 0): ?>
                        <br><small class="text-info fw-semibold"><i class="fas fa-arrow-up"></i> Menunggu dikerjakan</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body text-center p-4">
                    <div class="mb-2"><i class="fas fa-spinner fa-spin fa-2x text-warning"></i></div>
                    <h3 class="fw-bold text-warning mb-1"><?php echo e($tasksSedangDikerjakan->count()); ?></h3>
                    <small class="text-muted">Sedang Dikerjakan</small>
                    <?php if($tasksSedangDikerjakan->count() > 0): ?>
                        <br><small class="text-warning fw-semibold"><i class="fas fa-users"></i> Tim sedang bekerja</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body text-center p-4">
                    <div class="mb-2"><i class="fas fa-check-circle fa-2x text-success"></i></div>
                    <h3 class="fw-bold text-success mb-1"><?php echo e($tasksSelesai->count()); ?></h3>
                    <small class="text-muted">Tugas Selesai</small>
                    <?php if($tasksSelesai->count() > 0): ?>
                        <br><small class="text-success fw-semibold"><i class="fas fa-trophy"></i> Kerja bagus!</small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" id="taskTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="baru-tab" data-bs-toggle="tab" data-bs-target="#baru" type="button" role="tab">
                <i class="fas fa-tasks me-2"></i>Baru (<?php echo e($tasksBaru->count()); ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="progress-tab" data-bs-toggle="tab" data-bs-target="#progress" type="button" role="tab">
                <i class="fas fa-spinner me-2"></i>Sedang Dikerjakan (<?php echo e($tasksSedangDikerjakan->count()); ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="selesai-tab" data-bs-toggle="tab" data-bs-target="#selesai" type="button" role="tab">
                <i class="fas fa-check-circle me-2"></i>Selesai (<?php echo e($tasksSelesai->count()); ?>)
            </button>
        </li>
    </ul>

    <div class="tab-content" id="taskTabsContent">
        <div class="tab-pane fade show active" id="baru" role="tabpanel">
            <?php if($tasksBaru->count() > 0): ?>
                <div class="row g-3">
                    <?php $__currentLoopData = $tasksBaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 border-info border-opacity-25">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0"><?php echo e($task->title); ?></h5>
                                        <span class="badge bg-info"><?php echo e(ucfirst($task->status)); ?></span>
                                    </div>
                                    <?php if($task->description): ?>
                                        <p class="card-text text-muted small mb-2"><?php echo e(Str::limit($task->description, 100)); ?></p>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <?php if($task->priority): ?>
                                                <span class="badge bg-<?php echo e($task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'secondary')); ?>">
                                                    <?php echo e(ucfirst($task->priority)); ?>

                                                </span>
                                            <?php endif; ?>
                                            <?php if($task->deadline): ?>
                                                <span class="badge bg-<?php echo e(now()->greaterThan($task->deadline) ? 'danger' : 'secondary'); ?> ms-1">
                                                    <i class="far fa-calendar me-1"></i><?php echo e($task->deadline->format('d M')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="btn-group">
                                            <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-tasks fa-4x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">Belum ada tugas baru</p>
                    <a href="<?php echo e(route('tasks.create', ['project_id' => $project->id])); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Buat Tugas Pertama
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="tab-pane fade" id="progress" role="tabpanel">
            <?php if($tasksSedangDikerjakan->count() > 0): ?>
                <div class="row g-3">
                    <?php $__currentLoopData = $tasksSedangDikerjakan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 border-warning border-opacity-25">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0"><?php echo e($task->title); ?></h5>
                                        <span class="badge bg-warning text-dark"><?php echo e(ucfirst($task->status)); ?></span>
                                    </div>
                                    <?php if($task->description): ?>
                                        <p class="card-text text-muted small mb-2"><?php echo e(Str::limit($task->description, 100)); ?></p>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <?php if($task->user): ?>
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-user me-1"></i><?php echo e($task->user->name); ?>

                                                </span>
                                            <?php endif; ?>
                                            <?php if($task->deadline): ?>
                                                <span class="badge bg-<?php echo e(now()->greaterThan($task->deadline) ? 'danger' : 'secondary'); ?> ms-1">
                                                    <i class="far fa-calendar me-1"></i><?php echo e($task->deadline->format('d M')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="btn-group">
                                            <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-4x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">Belum ada tugas yang sedang dikerjakan</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="tab-pane fade" id="selesai" role="tabpanel">
            <?php if($tasksSelesai->count() > 0): ?>
                <div class="row g-3">
                    <?php $__currentLoopData = $tasksSelesai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 border-success border-opacity-25 bg-success bg-opacity-10">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-decoration-line-through opacity-75"><?php echo e($task->title); ?></h5>
                                        <span class="badge bg-success">Selesai</span>
                                    </div>
                                    <?php if($task->description): ?>
                                        <p class="card-text text-muted small mb-2"><?php echo e(Str::limit($task->description, 100)); ?></p>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            <?php if($task->user): ?>
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-user me-1"></i><?php echo e($task->user->name); ?>

                                                </span>
                                            <?php endif; ?>
                                            <?php if($task->deadline): ?>
                                                <span class="badge bg-success ms-1">
                                                    <i class="fas fa-check me-1"></i><?php echo e($task->deadline->format('d M Y')); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="btn-group">
                                            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">Belum ada tugas yang selesai</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\task_manager\resources\views/projects/show.blade.php ENDPATH**/ ?>