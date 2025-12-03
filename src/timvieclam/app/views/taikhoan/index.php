<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <!-- Header -->
    <div class="page-header" style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 600; color: #2d3748; margin-bottom: 8px;">
            <i class="fas fa-user-cog" style="color: #667eea;"></i> Cài đặt tài khoản
        </h1>
        <p style="color: #718096; font-size: 14px; margin: 0;">Quản lý thông tin cá nhân và cài đặt</p>
    </div>

    <!-- Tab wrapper với giới hạn chiều rộng -->
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Tabs nằm trên -->
        <ul class="nav nav-tabs" role="tablist">
            <?php if ($_SESSION['vaitro'] != 'tuyendung'): ?>
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#thongtin">
                    <i class="fas fa-user"></i> Thông tin cá nhân
                </a>
            </li>
            <?php endif; ?>
            <?php if ($_SESSION['vaitro'] == 'tuyendung'): ?>
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#congty">
                    <i class="fa-regular fa-building"></i> Thông tin công ty
                </a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#matkhau">
                    <i class="fas fa-key"></i> Đổi mật khẩu
                </a>
            </li>
        </ul>

        <!-- Nội dung tabs -->
        <div class="card">
            <div class="card-body" style="padding: 30px;">
            <div class="tab-content">
                <!-- Tab thông tin cá nhân -->
                <?php if ($_SESSION['vaitro'] != 'tuyendung'): ?>
                <div id="thongtin" class="tab-pane fade show active">
                <?php else: ?>
                <div id="thongtin" class="tab-pane fade">
                <?php endif; ?>
                    <form method="POST" action="<?php echo BASE_URL; ?>taikhoan/capnhat" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Họ tên <span class="text-danger">*</span></label>
                                <input type="text" name="hoten" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['hoten']); ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <input type="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                <small class="form-text text-muted">Email không thể thay đổi</small>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Số điện thoại</label>
                                <input type="tel" name="sodienthoai" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['sodienthoai'] ?? ''); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Vai trò</label>
                                <input type="text" class="form-control" 
                                       value="<?php 
                                       $roles = [
                                           'admin' => 'Quản trị viên',
                                           'tuyendung' => 'Nhà tuyển dụng',
                                           'ungvien' => 'Ứng viên'
                                       ];
                                       echo $roles[$user['vaitro']] ?? $user['vaitro'];
                                       ?>" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <textarea name="diachi" class="form-control" rows="2"><?php echo htmlspecialchars($user['diachi'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Avatar</label>
                            <div style="margin-bottom: 10px;">
                                <?php if (!empty($user['avatar'])): ?>
                                    <img src="<?php echo BASE_URL; ?>uploads/avatar/<?php echo htmlspecialchars($user['avatar']); ?>" 
                                         alt="Avatar" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                                <?php else: ?>
                                    <div style="width: 100px; height: 100px; background: #ddd; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Chấp nhận: JPG, PNG, GIF. Tối đa 1MB</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                            <a href="<?php echo BASE_URL; ?>nhatuyendung" class="btn btn-outline">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Tab thông tin công ty (chỉ cho nhà tuyển dụng) -->
                <?php if ($_SESSION['vaitro'] == 'tuyendung'): ?>
                <div id="congty" class="tab-pane fade show active">
                    <form method="POST" action="<?php echo BASE_URL; ?>taikhoan/capnhatcongty" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Tên công ty <span class="text-danger">*</span></label>
                                <input type="text" name="tencongty" class="form-control" 
                                       value="<?php echo htmlspecialchars($thongTinBoSung['tencongty'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Mã số thuế</label>
                                <input type="text" name="masothue" class="form-control" 
                                       value="<?php echo htmlspecialchars($thongTinBoSung['masothue'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Địa chỉ công ty</label>
                            <textarea name="diachi_congty" class="form-control" rows="2"><?php echo htmlspecialchars($thongTinBoSung['diachi_congty'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Website</label>
                                <input type="url" name="website" class="form-control" 
                                       value="<?php echo htmlspecialchars($thongTinBoSung['website'] ?? ''); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email công ty</label>
                                <input type="email" name="email_congty" class="form-control" 
                                       value="<?php echo htmlspecialchars($thongTinBoSung['email_congty'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Quy mô</label>
                                <select name="quymo" class="form-control">
                                    <option value="">-- Chọn quy mô --</option>
                                    <option value="1-10 nhân viên" <?php echo ($thongTinBoSung['quymo'] ?? '') == '1-10 nhân viên' ? 'selected' : ''; ?>>1-10 nhân viên</option>
                                    <option value="10-50 nhân viên" <?php echo ($thongTinBoSung['quymo'] ?? '') == '10-50 nhân viên' ? 'selected' : ''; ?>>10-50 nhân viên</option>
                                    <option value="50-100 nhân viên" <?php echo ($thongTinBoSung['quymo'] ?? '') == '50-100 nhân viên' ? 'selected' : ''; ?>>50-100 nhân viên</option>
                                    <option value="100-200 nhân viên" <?php echo ($thongTinBoSung['quymo'] ?? '') == '100-200 nhân viên' ? 'selected' : ''; ?>>100-200 nhân viên</option>
                                    <option value="200-500 nhân viên" <?php echo ($thongTinBoSung['quymo'] ?? '') == '200-500 nhân viên' ? 'selected' : ''; ?>>200-500 nhân viên</option>
                                    <option value="Trên 500 nhân viên" <?php echo ($thongTinBoSung['quymo'] ?? '') == 'Trên 500 nhân viên' ? 'selected' : ''; ?>>Trên 500 nhân viên</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Lĩnh vực</label>
                                <input type="text" name="linhvuc" class="form-control" 
                                       value="<?php echo htmlspecialchars($thongTinBoSung['linhvuc'] ?? ''); ?>" 
                                       placeholder="VD: Công nghệ thông tin, Thương mại điện tử">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Mô tả công ty</label>
                            <textarea name="mota" class="form-control" rows="4"><?php echo htmlspecialchars($thongTinBoSung['mota'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Logo công ty</label>
                            <div style="margin-bottom: 10px;">
                                <?php if (!empty($thongTinBoSung['logo'])): ?>
                                    <img src="<?php echo BASE_URL; ?>uploads/logo/<?php echo htmlspecialchars($thongTinBoSung['logo']); ?>" 
                                         alt="Logo" style="max-width: 200px; max-height: 100px;">
                                <?php endif; ?>
                            </div>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Chấp nhận: JPG, PNG. Tối đa 2MB</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thông tin công ty
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <!-- Tab đổi mật khẩu -->
                <div id="matkhau" class="tab-pane fade">
                    <form method="POST" action="<?php echo BASE_URL; ?>taikhoan/doimatkhau">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label>Mật khẩu hiện tại <span class="text-danger">*</span></label>
                            <input type="password" name="matkhau_cu" class="form-control" autocomplete="current-password" required>
                        </div>

                        <div class="form-group">
                            <label>Mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" name="matkhau_moi" class="form-control" 
                                   autocomplete="new-password" 
                                   minlength="8" required>
                            <small class="form-text text-muted">Tối thiểu 8 ký tự</small>
                        </div>

                        <div class="form-group">
                            <label>Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" name="xacnhan_matkhau" class="form-control" 
                                   autocomplete="new-password" 
                                   minlength="8" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Tab wrapper */
.nav-tabs {
    border-bottom: none;
    gap: 5px;
    background: transparent;
    margin-bottom: 0 !important;
    display: flex;
    flex-wrap: wrap;
}

.nav-tabs .nav-item {
    margin-right: 0;
}

.nav-tabs .nav-link {
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 12px 24px;
    font-weight: 500;
    transition: all 0.2s ease;
    text-decoration: none !important;
    border-radius: 8px 8px 0 0;
    background: #f8fafc;
    border-bottom: none;
    white-space: nowrap;
}

.nav-tabs .nav-link i {
    text-decoration: none !important;
    margin-right: 8px;
    font-size: 14px;
}

.nav-tabs .nav-link:hover {
    color: #667eea;
    background-color: #fff;
    border-color: #e2e8f0;
    text-decoration: none !important;
    transform: translateY(-2px);
}

.nav-tabs .nav-link.active {
    color: #667eea;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-bottom: 1px solid #fff;
    font-weight: 600;
    text-decoration: none !important;
    position: relative;
    z-index: 1;
}

.nav-tabs .nav-link:focus,
.nav-tabs .nav-link:active,
.nav-tabs .nav-link:visited {
    text-decoration: none !important;
}

.nav-tabs a {
    text-decoration: none !important;
}

/* Card styling */
.card {
    border: 1px solid #e2e8f0;
    border-radius: 0 8px 8px 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    margin-top: -1px;
    background: #fff;
}

.tab-content {
    padding: 0;
}

/* Form styling */
.form-group label {
    font-weight: 500;
    color: #2d3748;
    font-size: 14px;
    margin-bottom: 8px;
}

.form-control {
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 10px 14px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 10px 24px;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-outline {
    border: 1px solid #e2e8f0;
    color: #64748b;
    padding: 10px 24px;
    border-radius: 6px;
    background: #fff;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-outline:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    text-decoration: none;
}

/* Responsive */
@media (max-width: 768px) {
    .nav-tabs {
        gap: 3px;
    }
    
    .nav-tabs .nav-link {
        padding: 10px 16px;
        font-size: 13px;
    }
    
    .card-body {
        padding: 20px !important;
    }
}

/* Tab panes - Quan trọng để hiển thị/ẩn */
.tab-pane {
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.tab-pane.active {
    display: block;
    opacity: 1;
}

.tab-pane.show {
    opacity: 1;
}
</style>

<script>
// Tab switching với animation
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching
    const tabs = document.querySelectorAll('[data-toggle="tab"]');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            
            // Remove active from all panes
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Add active to clicked tab
            this.classList.add('active');
            
            // Show corresponding pane
            const targetId = this.getAttribute('href');
            const targetPane = document.querySelector(targetId);
            
            if (targetPane) {
                // Small delay for smooth transition
                setTimeout(() => {
                    targetPane.classList.add('active');
                    setTimeout(() => {
                        targetPane.classList.add('show');
                    }, 10);
                }, 10);
            }
        });
    });
    
    // Form validation for password match
    const formDoiMatKhau = document.querySelector('form[action*="doimatkhau"]');
    if (formDoiMatKhau) {
        formDoiMatKhau.addEventListener('submit', function(e) {
            const matkhauMoi = this.querySelector('[name="matkhau_moi"]');
            const xacnhan = this.querySelector('[name="xacnhan_matkhau"]');
            
            if (matkhauMoi && xacnhan && matkhauMoi.value !== xacnhan.value) {
                e.preventDefault();
                alert('Mật khẩu mới không khớp!');
                xacnhan.focus();
            }
        });
    }
});
</script>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
