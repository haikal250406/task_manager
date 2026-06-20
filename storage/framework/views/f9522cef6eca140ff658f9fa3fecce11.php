<?php $__env->startSection('title', 'Buat Proyek Baru'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Card dengan shadow dan rounded corners -->
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-gradient-primary text-white py-4 border-0 rounded-top-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle me-3">
                            <i class="fas fa-folder-plus fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Buat Proyek Baru</h4>
                            <small class="opacity-75">Lengkapi informasi proyek di bawah ini</small>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-5">
                    <form action="<?php echo e(route('projects.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Nama Proyek -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                <i class="fas fa-tag me-2 text-primary"></i>Nama Proyek
                                <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                class="form-control form-control-lg <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                value="<?php echo e(old('name')); ?>" 
                                placeholder="Contoh: Aplikasi Task Manager" 
                                required
                                autofocus>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Masukkan nama proyek yang jelas dan deskriptif
                            </small>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="fas fa-align-left me-2 text-primary"></i>Deskripsi
                            </label>
                            <textarea 
                                name="description" 
                                id="description" 
                                rows="4" 
                                class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                placeholder="Jelaskan detail proyek, tujuan, dan ruang lingkup..."><?php echo e(old('description')); ?></textarea>
                            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-lightbulb me-1 text-warning"></i>
                                Deskripsi yang baik akan membantu tim memahami proyek
                            </small>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">
                                <i class="fas fa-chart-line me-2 text-primary"></i>Status Proyek
                                <span class="text-danger">*</span>
                            </label>
                            <select 
                                name="status" 
                                id="status" 
                                class="form-select form-select-lg <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                required>
                                <option value="">-- Pilih Status --</option>
                                <option value="active" <?php echo e(old('status') == 'active' ? 'selected' : ''); ?>>
                                    🟢 Aktif - Proyek sedang berjalan
                                </option>
                                <option value="on_hold" <?php echo e(old('status') == 'on_hold' ? 'selected' : ''); ?>>
                                    🟡 Ditangguhkan - Sementara pause
                                </option>
                                <option value="completed" <?php echo e(old('status') == 'completed' ? 'selected' : ''); ?>>
                                    🔵 Selesai - Proyek sudah selesai
                                </option>
                                <option value="cancelled" <?php echo e(old('status') == 'cancelled' ? 'selected' : ''); ?>>
                                    🔴 Dibatalkan - Proyek dibatalkan
                                </option>
                            </select>
                            <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-info border-0 rounded-3 mb-4">
                            <div class="d-flex">
                                <i class="fas fa-info-circle fa-lg me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading fw-bold mb-1">Informasi Penting</h6>
                                    <p class="mb-0 small">
                                        Setelah proyek dibuat, Anda dapat menambahkan tugas, 
                                        mengelola tim, dan melacak progres proyek.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 flex-grow-1">
                                <i class="fas fa-save me-2"></i>Simpan Proyek
                            </button>
                            <a href="<?php echo e(route('projects.index')); ?>" class="btn btn-outline-secondary btn-lg px-4 py-3">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="mt-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-lightbulb text-warning me-2"></i>Tips Membuat Proyek
                        </h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Beri nama yang jelas:</strong> Gunakan nama yang mudah dipahami
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Deskripsi detail:</strong> Jelaskan tujuan dan scope proyek
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Pilih status yang tepat:</strong> Sesuaikan dengan kondisi proyek
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }
    .card {
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\task_manager\resources\views/projects/create.blade.php ENDPATH**/ ?>