<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admin"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>danhmuc">Danh mục</a></li>
            <li class="breadcrumb-item active">Cấu hình hệ thống</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-2"><i class="fas fa-sliders-h me-3 text-warning"></i>Cấu hình Hệ thống</h1>
                <p class="text-muted mb-0">Quản lý các thông số cấu hình hệ thống</p>
            </div>
            <div>
                <a href="<?php echo BASE_URL; ?>danhmuc" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>
    
    <form method="POST" action="<?php echo BASE_URL; ?>danhmuc/capnhatcauhinh">
        <?php echo csrf_field(); ?>
        <!-- CV Configuration -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h3 class="mb-0 text-primary">
                    <i class="fas fa-file-pdf me-2"></i>Cấu hình CV
                </h3>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-hdd me-2 text-muted"></i>Giới hạn dung lượng CV (MB):
                        </label>
                        <input type="number" name="max_cv_size" class="form-control" 
                               value="<?php echo $config['max_cv_size']; ?>" required min="1" max="50">
                        <small class="form-text text-muted">Mặc định: 5MB</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-file-alt me-2 text-muted"></i>Định dạng CV cho phép:
                        </label>
                        <input type="text" name="allowed_cv_types" class="form-control" 
                               value="<?php echo htmlspecialchars($config['allowed_cv_types']); ?>" required>
                        <small class="form-text text-muted">Ví dụ: pdf,doc,docx</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Job Posting Configuration -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h3 class="mb-0 text-success">
                    <i class="fas fa-briefcase me-2"></i>Cấu hình Tin tuyển dụng
                </h3>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar-alt me-2 text-muted"></i>Thời hạn tin (ngày):
                        </label>
                        <input type="number" name="tin_expire_days" class="form-control" 
                               value="<?php echo $config['tin_expire_days']; ?>" required min="1" max="365">
                        <small class="form-text text-muted">Mặc định: 30 ngày</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-layer-group me-2 text-muted"></i>Số tin tối đa/nhà tuyển dụng:
                        </label>
                        <input type="number" name="max_tin_per_employer" class="form-control" 
                               value="<?php echo $config['max_tin_per_employer']; ?>" required min="1" max="1000">
                        <small class="form-text text-muted">Số lượng tin tuyển dụng tối đa một NTD có thể đăng</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="text-end">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save me-2"></i>Lưu cấu hình
            </button>
        </div>
    </form>
</div>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
