<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card profile-sidebar shadow-sm">
                <div class="card-body text-center">
                    <div class="user-avatar mb-3">
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="<?php echo BASE_URL . 'uploads/avatar/' . htmlspecialchars($user['avatar']); ?>" 
                                 alt="Avatar" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h5 class="mb-1"><?php echo htmlspecialchars($user['hoten']); ?></h5>
                    <p class="text-muted small"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>ungvien/hoso" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user me-2"></i> Hồ sơ của tôi
                    </a>
                    <a href="<?php echo BASE_URL; ?>ungvien/donungtuyen" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt me-2"></i> Đơn ứng tuyển
                    </a>
                    <?php if ($_SESSION['vaitro'] == 'ungvien'): ?>
                    <a href="<?php echo BASE_URL; ?>ungvien/yeucautuyendung" class="list-group-item list-group-item-action">
                        <i class="fas fa-building me-2"></i> Trở thành NTD
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo BASE_URL; ?>taikhoan/doimatkhau" class="list-group-item list-group-item-action">
                        <i class="fas fa-lock me-2"></i> Đổi mật khẩu
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-9">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i> Thông Tin Cá Nhân</h4>
                </div>
                <div class="card-body p-4">
                    <form action="<?php echo BASE_URL; ?>ungvien/capnhathoso" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="hoten" 
                                       value="<?php echo htmlspecialchars($user['hoten']); ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email</label>
                                <input type="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                <small class="text-muted">Email không thể thay đổi</small>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="sodienthoai" 
                                       value="<?php echo htmlspecialchars($user['sodienthoai']); ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày sinh</label>
                                <input type="date" class="form-control" name="ngaysinh" 
                                       value="<?php echo $user['ngaysinh'] ?? ''; ?>">
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Giới tính</label>
                                <select class="form-select" name="gioitinh">
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="nam" <?php echo (isset($user['gioitinh']) && $user['gioitinh'] == 'nam') ? 'selected' : ''; ?>>Nam</option>
                                    <option value="nu" <?php echo (isset($user['gioitinh']) && $user['gioitinh'] == 'nu') ? 'selected' : ''; ?>>Nữ</option>
                                    <option value="khac" <?php echo (isset($user['gioitinh']) && $user['gioitinh'] == 'khac') ? 'selected' : ''; ?>>Khác</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Trình độ</label>
                                <input type="text" class="form-control" name="trinhdo" 
                                       value="<?php echo htmlspecialchars($user['trinhdo'] ?? ''); ?>" 
                                       placeholder="VD: Đại học, Cao đẳng...">
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <label class="form-label fw-bold">Địa chỉ</label>
                            <textarea class="form-control" name="diachi" rows="3"><?php echo htmlspecialchars($user['diachi'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mt-3">
                            <label class="form-label fw-bold">Kinh nghiệm làm việc</label>
                            <textarea class="form-control" name="kinhnghiem" rows="4" 
                                      placeholder="Mô tả kinh nghiệm làm việc của bạn..."><?php echo htmlspecialchars($user['kinhnghiem'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mt-3">
                            <label class="form-label fw-bold">Kỹ năng</label>
                            <textarea class="form-control" name="kynang" rows="3" 
                                      placeholder="Liệt kê các kỹ năng của bạn..."><?php echo htmlspecialchars($user['kynang'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="mt-3">
                            <label class="form-label fw-bold">Mục tiêu nghề nghiệp</label>
                            <textarea class="form-control" name="muctieu" rows="3" 
                                      placeholder="Mục tiêu nghề nghiệp của bạn..."><?php echo htmlspecialchars($user['muctieucanhan'] ?? ''); ?></textarea>
                        </div>
                        
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ảnh đại diện</label>
                                <input type="file" class="form-control" name="avatar" accept="image/*">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Định dạng: JPG, PNG, GIF (Tối đa 5MB)
                                </small>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    CV của bạn
                                    <?php if (!empty($user['cv_file'])): ?>
                                        <a href="<?php echo BASE_URL . 'uploads/cv/' . htmlspecialchars($user['cv_file']); ?>" 
                                           target="_blank" rel="noopener noreferrer" class="badge bg-success ms-2">
                                            <i class="fas fa-file-pdf"></i> Xem CV hiện tại
                                        </a>
                                    <?php endif; ?>
                                </label>
                                <input type="file" class="form-control" name="cv" accept=".pdf,.doc,.docx">
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Định dạng: PDF, DOC, DOCX (Tối đa 5MB)
                                </small>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-save me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
    --light-color: #f8f9fa;
    --dark-color: #343a40;
    --border-color: #dee2e6;
    --text-color: #212529;
    --text-light: #6c757d;
}

.profile-sidebar {
    position: sticky;
    top: 80px;
    border: 1px solid var(--border-color);
}

.user-avatar {
    margin-bottom: 1rem;
}

.user-avatar img {
    border: 3px solid var(--primary-color);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    object-fit: cover;
}

.user-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.25rem;
}

.user-email {
    font-size: 0.875rem;
    color: var(--text-light);
}

.list-group-item {
    border-left: 3px solid transparent;
    transition: all 0.3s ease;
}

.list-group-item:hover {
    background-color: var(--light-color);
    border-left-color: var(--primary-color);
}

.list-group-item.active {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    border-left-color: var(--dark-color);
    font-weight: 600;
}

.list-group-item i {
    width: 20px;
    text-align: center;
    margin-right: 0.75rem;
}

.card-header {
    background-color: var(--light-color);
    border-bottom: 2px solid var(--primary-color);
}

.card-header h4 {
    color: var(--dark-color);
    font-weight: 600;
}

.form-label {
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.form-label.fw-bold {
    font-weight: 600;
}

.form-control, .form-select {
    border: 1px solid var(--border-color);
    border-radius: 6px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.alert {
    border-left: 4px solid;
    border-radius: 6px;
}

.alert-success {
    border-left-color: var(--success-color);
}

.alert-danger {
    border-left-color: var(--danger-color);
}

@media (max-width: 768px) {
    .profile-sidebar {
        position: static;
        margin-bottom: 1.5rem;
    }
    
    .user-avatar img {
        width: 80px !important;
        height: 80px !important;
    }
    
    .card-body {
        padding: 1rem !important;
    }
}
</style>
</style>
