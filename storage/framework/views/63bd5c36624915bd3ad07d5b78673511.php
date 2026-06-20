

<?php $__env->startSection('title', 'Dashboard Admin'); ?>
<?php $__env->startSection('page-title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>
<!-- Statistics Cards dengan Warna -->
<div class="row g-4 mb-5">
    <!-- Card 1: Total Tugas - Biru -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card card-blue-1 rounded-4 p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="mb-1 fw-medium small text-uppercase opacity-75">TOTAL TUGAS</p>
                    <h2 class="fw-bold mb-0 display-6"><?php echo e($stats['totalTasks']); ?></h2>
                </div>
                <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                    <i class="fas fa-tasks fa-lg"></i>
                </div>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="badge bg-white bg-opacity-50 text-dark small px-2 py-1">
                    <i class="fas fa-check me-1"></i>Selesai: <?php echo e($stats['completedTasks']); ?>

                </span>
                <span class="badge bg-white bg-opacity-50 text-dark small px-2 py-1">
                    <i class="fas fa-clock me-1"></i>Pending: <?php echo e($stats['totalTasks'] - $stats['completedTasks']); ?>

                </span>
            </div>
            <div class="progress rounded-pill bg-white bg-opacity-25" style="height: 6px;">
                <div class="progress-bar bg-white" role="progressbar" 
                     style="width: <?php echo e($stats['totalTasks'] > 0 ? ($stats['completedTasks'] / $stats['totalTasks']) * 100 : 0); ?>%"></div>
            </div>
        </div>
    </div>

    <!-- Card 2: Progres - Hijau -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card card-green-1 rounded-4 p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="mb-1 fw-medium small text-uppercase opacity-75">PROGRES KESELURUHAN</p>
                    <h2 class="fw-bold mb-0 display-6"><?php echo e($stats['progressPercentage']); ?>%</h2>
                </div>
                <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                    <i class="fas fa-chart-line fa-lg"></i>
                </div>
            </div>
            <div class="progress rounded-pill bg-white bg-opacity-25 mb-2" style="height: 8px;">
                <div class="progress-bar bg-white rounded-pill" role="progressbar" 
                     style="width: <?php echo e($stats['progressPercentage']); ?>%;"></div>
            </div>
            <small class="opacity-75 d-block">
                <?php echo e($stats['completedTasks']); ?> dari <?php echo e($stats['totalTasks']); ?> tugas selesai
            </small>
        </div>
    </div>

    <!-- Card 3: Terlambat - Merah -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card card-red-1 rounded-4 p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="mb-1 fw-medium small text-uppercase opacity-75">TUGAS TERLAMBAT</p>
                    <h2 class="fw-bold mb-0 display-6"><?php echo e($stats['overdueTasks']); ?></h2>
                </div>
                <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                </div>
            </div>
            <?php if($stats['overdueTasks'] > 0): ?>
                <span class="badge bg-warning text-dark fw-medium">
                    <i class="fas fa-exclamation-circle me-1"></i>Perlu perhatian
                </span>
            <?php else: ?>
                <span class="badge bg-success text-white fw-medium">
                    <i class="fas fa-check-circle me-1"></i>Semua on-track
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Card 4: Proyek - Ungu -->
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stat-card card-purple-1 rounded-4 p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <p class="mb-1 fw-medium small text-uppercase opacity-75">PROYEK AKTIF</p>
                    <h2 class="fw-bold mb-0 display-6"><?php echo e($stats['activeProjects']); ?></h2>
                </div>
                <div class="bg-white bg-opacity-25 p-3 rounded-circle">
                    <i class="fas fa-project-diagram fa-lg"></i>
                </div>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="badge bg-white bg-opacity-50 text-dark small px-2 py-1">
                    <i class="fas fa-check me-1"></i>Selesai: <?php echo e($stats['completedProjects']); ?>

                </span>
                <span class="badge bg-white bg-opacity-50 text-dark small px-2 py-1">
                    <i class="fas fa-pause me-1"></i>On Hold: <?php echo e($stats['onHoldProjects']); ?>

                </span>
            </div>
            <small class="opacity-75 d-block">
                Total: <?php echo e($stats['totalProjects']); ?> proyek
            </small>
        </div>
    </div>
</div>

<!-- Detailed Statistics & Recent Activities -->
<div class="row g-4 mb-4">
    <!-- Recent Activities -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Aktivitas Terbaru</h5>
                <a href="<?php echo e(route('admin.activity-logs.index')); ?>" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <?php if($recentActivities->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Aksi</th>
                                    <th>Deskripsi</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php if($activity->user): ?>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo e($activity->user->getAvatarUrl()); ?>" 
                                                         alt="<?php echo e($activity->user->name); ?>" 
                                                         class="rounded-circle me-2" width="32" height="32">
                                                    <small class="fw-semibold"><?php echo e($activity->user->name); ?></small>
                                                </div>
                                            <?php else: ?>
                                                <small class="text-muted">System</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo e($activity->getActionBadgeClass()); ?> px-2 py-1">
                                                <?php echo e(ucfirst($activity->action)); ?>

                                            </span>
                                        </td>
                                        <td><small><?php echo e(Str::limit($activity->description, 50)); ?></small></td>
                                        <td><small class="text-muted"><?php echo e($activity->created_at->diffForHumans()); ?></small></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted mb-0">Belum ada aktivitas tercatat</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- User & Task Statistics -->
    <div class="col-lg-4">
        <!-- User Statistics -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Statistik User</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small">Total User</span>
                        <strong><?php echo e($userStats['total']); ?></strong>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small">Admin</span>
                        <strong><?php echo e($userStats['admins']); ?></strong>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-danger" style="width: <?php echo e($userStats['total'] > 0 ? ($userStats['admins'] / $userStats['total']) * 100 : 0); ?>%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small">User Biasa</span>
                        <strong><?php echo e($userStats['users']); ?></strong>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" style="width: <?php echo e($userStats['total'] > 0 ? ($userStats['users'] / $userStats['total']) * 100 : 0); ?>%"></div>
                    </div>
                </div>
                <div class="alert alert-info mb-0 small">
                    <i class="fas fa-user-plus me-1"></i>
                    <strong><?php echo e($userStats['new_this_month']); ?></strong> user baru bulan ini
                </div>
            </div>
        </div>

        <!-- Task by Priority -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0"><i class="fas fa-flag me-2 text-warning"></i>Tugas by Prioritas</h5>
            </div>
            <div class="card-body">
                <?php $__currentLoopData = $tasksByPriority; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $colorClass = match(strtolower($priority)) {
                            'critical' => 'bg-danger',
                            'high' => 'bg-warning',
                            'medium' => 'bg-info',
                            'low' => 'bg-success',
                            default => 'bg-secondary',
                        };
                        $percentage = $stats['totalTasks'] > 0 ? ($count / $stats['totalTasks']) * 100 : 0;
                    ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small fw-semibold"><?php echo e($priority); ?></span>
                            <strong><?php echo e($count); ?></strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar <?php echo e($colorClass); ?>" style="width: <?php echo e($percentage); ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Tasks & Projects -->
<div class="row g-4">
    <!-- Recent Tasks -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-tasks me-2 text-primary"></i>Tugas Terbaru</h5>
                <a href="<?php echo e(route('admin.tasks.index')); ?>" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body">
                <?php if($recentTasks->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $recentTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold"><?php echo e($task->title); ?></h6>
                                        <small class="text-muted">
                                            <i class="fas fa-project-diagram me-1"></i><?php echo e($task->project->name ?? 'Tanpa Proyek'); ?>

                                            <?php if($task->user): ?>
                                                • <i class="fas fa-user me-1"></i><?php echo e($task->user->name); ?>

                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="ms-2">
                                        <span class="badge <?php echo e($task->getStatusBadgeClass()); ?> mb-1">
                                            <?php echo e($task->getStatusText()); ?>

                                        </span>
                                        <?php if($task->deadline): ?>
                                            <div class="small <?php echo e($task->isOverdue() ? 'text-danger fw-bold' : 'text-muted'); ?>">
                                                <i class="far fa-calendar me-1"></i><?php echo e($task->deadline->format('d M')); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-tasks fa-3x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted mb-0">Belum ada tugas</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Upcoming Deadlines -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0"><i class="fas fa-clock me-2 text-warning"></i>Deadline Terdekat (7 Hari)</h5>
            </div>
            <div class="card-body">
                <?php if($upcomingDeadlines->count() > 0): ?>
                    <div class="list-group list-group-flush">
                        <?php $__currentLoopData = $upcomingDeadlines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $daysLeft = now()->diffInDays($task->deadline, false);
                                $urgencyClass = $daysLeft <= 1 ? 'danger' : ($daysLeft <= 3 ? 'warning' : 'info');
                            ?>
                            <div class="list-group-item px-0 border-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-semibold"><?php echo e($task->title); ?></h6>
                                        <small class="text-muted">
                                            <i class="fas fa-project-diagram me-1"></i><?php echo e($task->project->name ?? 'Tanpa Proyek'); ?>

                                        </small>
                                    </div>
                                    <div class="ms-2 text-end">
                                        <span class="badge bg-<?php echo e($urgencyClass); ?> mb-1">
                                            <?php if($daysLeft == 0): ?>
                                                Hari Ini!
                                            <?php elseif($daysLeft == 1): ?>
                                                Besok
                                            <?php else: ?>
                                                <?php echo e($daysLeft); ?> hari lagi
                                            <?php endif; ?>
                                        </span>
                                        <div class="small text-muted">
                                            <?php echo e($task->deadline->format('d M Y')); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-check fa-3x text-success opacity-25 mb-3"></i>
                        <p class="text-muted mb-0">Tidak ada deadline dalam 7 hari ke depan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .card-blue-1 {
        background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
        color: #1E3A8A;
    }
    .card-green-1 {
        background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
        color: #064E3B;
    }
    .card-red-1 {
        background: linear-gradient(135deg, #FECACA 0%, #FCA5A5 100%);
        color: #7F1D1D;
    }
    .card-purple-1 {
        background: linear-gradient(135deg, #E9D5FF 0%, #D8B4FE 100%);
        color: #581C87;
    }
    .stat-card {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    .stat-card h2, .stat-card p, .stat-card small {
        color: inherit;
    }
    .list-group-item:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\task_manager\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>