<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Task Manager'); ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f5f7 !important;
            color: #2d3748;
        }
        .navbar {
            background: linear-gradient(135deg, #1e293b, #0f172a) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .btn {
            font-weight: 600;
            letter-spacing: -0.2px;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .alert {
            border: none;
            border-radius: 10px;
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4 py-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo e(route('dashboard')); ?>">
                <i class="bi bi-layers-half me-2 text-info"></i>Task & Project Manager
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if(auth()->guard()->check()): ?>
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?php echo e(request()->routeIs('dashboard') ? 'text-info' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                                <i class="bi bi-grid-1x2-fill me-1 small"></i> Dasbor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?php echo e(request()->routeIs('projects.*') ? 'text-info' : ''); ?>" href="<?php echo e(route('projects.index')); ?>">
                                <i class="bi bi-folder-fill me-1 small"></i> Daftar Proyek
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-medium <?php echo e(request()->routeIs('tasks.*') ? 'text-info' : ''); ?>" href="<?php echo e(route('tasks.index')); ?>">
                                <i class="bi bi-list-task me-1 small"></i> Daftar Tugas
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>

                <ul class="navbar-nav ms-auto">
                    <?php if(auth()->guard()->guest()): ?>
                        <?php if(Route::has('login')): ?>
                            <li class="nav-item"><a class="nav-link fw-medium" href="<?php echo e(route('login')); ?>">Login</a></li>
                        <?php endif; ?>
                        <?php if(Route::has('register')): ?>
                            <li class="nav-item"><a class="nav-link fw-medium" href="<?php echo e(route('register')); ?>">Register</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <li class="nav-item me-2">
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-sm btn-warning rounded-3 px-3">
                                    <i class="bi bi-shield-lock me-1"></i> Admin Panel
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-danger rounded-3 px-3">
                                    <i class="bi bi-box-arrow-right me-1"></i> Keluar (<?php echo e(Auth::user()->name); ?>)
                                </button>
                            </form>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mb-5">
        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong>Berhasil!</strong> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Error!</strong> <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <strong>Perhatian!</strong> <?php echo e(session('warning')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
            <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Info:</strong> <?php echo e(session('info')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\laragon\www\task_manager\resources\views/layouts/app.blade.php ENDPATH**/ ?>