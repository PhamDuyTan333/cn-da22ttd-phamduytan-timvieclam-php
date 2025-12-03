<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1><i class="fas fa-user"></i> Hồ sơ ứng viên</h1>
            <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachungvien/<?php echo $don['tintuyendung_id']; ?>" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin ứng viên -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user-circle"></i> Thông tin cá nhân</h3>
                </div>
                <div class="card-body">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <?php if (!empty($don['avatar'])): ?>
                                <img src="<?php echo BASE_URL . 'uploads/avatar/' . htmlspecialchars($don['avatar']); ?>" 
                                     alt="Avatar">
                            <?php else: ?>
                                <div class="avatar-placeholder">
                                    <?php echo strtoupper(substr($don['hoten'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="profile-info">
                            <h2><?php echo htmlspecialchars($don['hoten']); ?></h2>
                            <div class="profile-meta">
                                <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($don['email']); ?></span>
                                <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($don['sodienthoai']); ?></span>
                                <?php if (!empty($don['gioitinh'])): ?>
                                    <span><i class="fas fa-venus-mars"></i> <?php echo $don['gioitinh'] == 'nam' ? 'Nam' : 'Nữ'; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($don['ngaysinh'])): ?>
                                    <span><i class="fas fa-birthday-cake"></i> <?php echo date('d/m/Y', strtotime($don['ngaysinh'])); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($don['diachi'])): ?>
                    <div class="info-section">
                        <h4><i class="fas fa-map-marker-alt"></i> Địa chỉ</h4>
                        <p><?php echo htmlspecialchars($don['diachi']); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($don['trinhdo']) || !empty($don['kinhnghiem']) || !empty($don['kynang']) || !empty($don['muctieucanhan'])): ?>
                        <?php if (!empty($don['trinhdo'])): ?>
                        <div class="info-section">
                            <h4><i class="fas fa-graduation-cap"></i> Trình độ</h4>
                            <p><?php echo nl2br(htmlspecialchars($don['trinhdo'])); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($don['kinhnghiem'])): ?>
                        <div class="info-section">
                            <h4><i class="fas fa-briefcase"></i> Kinh nghiệm làm việc</h4>
                            <p><?php echo nl2br(htmlspecialchars($don['kinhnghiem'])); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($don['kynang'])): ?>
                        <div class="info-section">
                            <h4><i class="fas fa-star"></i> Kỹ năng</h4>
                            <p><?php echo nl2br(htmlspecialchars($don['kynang'])); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($don['muctieucanhan'])): ?>
                        <div class="info-section">
                            <h4><i class="fas fa-bullseye"></i> Mục tiêu nghề nghiệp</h4>
                            <p><?php echo nl2br(htmlspecialchars($don['muctieucanhan'])); ?></p>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Thư bật -->
            <?php if (!empty($don['thubat'])): ?>
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3><i class="fas fa-envelope-open-text"></i> Thư bật</h3>
                </div>
                <div class="card-body">
                    <div class="cover-letter">
                        <?php echo nl2br(htmlspecialchars($don['thubat'])); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Thông tin đơn ứng tuyển -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Thông tin đơn</h3>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-row">
                            <strong>Vị trí:</strong>
                            <span><?php echo htmlspecialchars($don['tieude']); ?></span>
                        </div>
                        <div class="info-row">
                            <strong>Ngày nộp:</strong>
                            <span><?php echo date('d/m/Y H:i', strtotime($don['ngaynop'])); ?></span>
                        </div>
                        <div class="info-row">
                            <strong>Trạng thái:</strong>
                            <?php
                            $statusLabels = [
                                'moi' => '<span class="badge badge-info">Mới</span>',
                                'dangxem' => '<span class="badge badge-primary">Đang xem</span>',
                                'phongvan' => '<span class="badge badge-warning">Phỏng vấn</span>',
                                'nhanviec' => '<span class="badge badge-success">Nhận việc</span>',
                                'tuchoi' => '<span class="badge badge-danger">Từ chối</span>'
                            ];
                            echo $statusLabels[$don['trangthai']] ?? $don['trangthai'];
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CV -->
            <?php if (!empty($don['cv'])): ?>
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3><i class="fas fa-file-pdf"></i> CV đính kèm</h3>
                </div>
                <div class="card-body">
                    <a href="<?php echo BASE_URL . 'uploads/cv/' . htmlspecialchars($don['cv']); ?>" 
                       class="btn btn-primary btn-block"
                       target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-download"></i> Tải xuống CV
                    </a>
                    <a href="<?php echo BASE_URL . 'uploads/cv/' . htmlspecialchars($don['cv']); ?>" 
                       class="btn btn-outline btn-block"
                       target="_blank" rel="noopener noreferrer"
                       style="margin-top: 10px;">
                        <i class="fas fa-eye"></i> Xem trực tuyến
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- Cập nhật trạng thái -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3><i class="fas fa-edit"></i> Cập nhật trạng thái</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>nhatuyendung/capnhattrangthai/<?php echo $don['id']; ?>">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="trangthai">Trạng thái mới</label>
                            <select class="form-control" id="trangthai" name="trangthai">
                                <option value="moi" <?php echo $don['trangthai'] == 'moi' ? 'selected' : ''; ?>>
                                    Mới
                                </option>
                                <option value="dangxem" <?php echo $don['trangthai'] == 'dangxem' ? 'selected' : ''; ?>>
                                    Đang xem
                                </option>
                                <option value="phongvan" <?php echo $don['trangthai'] == 'phongvan' ? 'selected' : ''; ?>>
                                    Phỏng vấn
                                </option>
                                <option value="nhanviec" <?php echo $don['trangthai'] == 'nhanviec' ? 'selected' : ''; ?>>
                                    Nhận việc
                                </option>
                                <option value="tuchoi" <?php echo $don['trangthai'] == 'tuchoi' ? 'selected' : ''; ?>>
                                    Từ chối
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> Cập nhật
                        </button>
                    </form>
                </div>
            </div>

            <!-- Liên hệ nhanh -->
            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3><i class="fas fa-phone"></i> Liên hệ nhanh</h3>
                </div>
                <div class="card-body">
                    <a href="tel:<?php echo htmlspecialchars($ungvien['sodienthoai']); ?>" class="btn btn-success btn-block">
                        <i class="fas fa-phone-alt"></i> Gọi điện
                    </a>
                    <a href="mailto:<?php echo htmlspecialchars($ungvien['email']); ?>" class="btn btn-info btn-block" style="margin-top: 10px;">
                        <i class="fas fa-envelope"></i> Gửi email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-header {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 2px solid #f0f0f0;
}

.profile-avatar img,
.avatar-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-placeholder {
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: bold;
}

.profile-info h2 {
    margin: 0 0 15px 0;
    color: var(--primary-color);
    font-size: 1.8rem;
}

.profile-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    color: #666;
}

.profile-meta span {
    display: flex;
    align-items: center;
    gap: 8px;
}

.profile-meta i {
    width: 20px;
    color: var(--primary-color);
}

.info-section {
    margin-bottom: 25px;
    padding-bottom: 25px;
    border-bottom: 1px solid #f0f0f0;
}

.info-section:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.info-section h4 {
    color: var(--primary-color);
    margin-bottom: 15px;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-section p {
    margin: 0;
    color: #666;
    line-height: 1.8;
    white-space: pre-wrap;
}

.cover-letter {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    line-height: 1.8;
    color: #666;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-row {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.badge {
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-block;
}

.badge-info {
    background: #17a2b8;
    color: white;
}

.badge-primary {
    background: #007bff;
    color: white;
}

.badge-warning {
    background: #ffc107;
    color: #333;
}

.badge-success {
    background: #28a745;
    color: white;
}

.badge-danger {
    background: #dc3545;
    color: white;
}

@media (max-width: 768px) {
    .profile-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .profile-meta {
        align-items: center;
    }
}
</style>

<script>
function updateStatus(donId, status) {
    if (!confirm('Bạn có chắc chắn muốn đổi trạng thái đơn này?')) {
        return;
    }
    
    // Tạo form và submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo BASE_URL; ?>nhatuyendung/capnhattrangthai/' + donId;
    
    const inputStatus = document.createElement('input');
    inputStatus.type = 'hidden';
    inputStatus.name = 'trangthai';
    inputStatus.value = status;
    
    form.appendChild(inputStatus);
    document.body.appendChild(form);
    form.submit();
}
</script>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
