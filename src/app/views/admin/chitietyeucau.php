<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admin"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admin/yeucaunhatuyendung">Yêu cầu NTD</a></li>
            <li class="breadcrumb-item active">Chi tiết</li>
        </ol>
    </nav>

    <!-- Thông báo -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-2">
                    <i class="fas fa-file-alt me-3 text-info"></i>Chi tiết yêu cầu trở thành nhà tuyển dụng
                </h1>
                <p class="text-muted mb-0">Xem xét và phê duyệt yêu cầu</p>
            </div>
            <div>
                <a href="<?php echo BASE_URL; ?>admin/yeucaunhatuyendung" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Cột trái: Thông tin người dùng -->
        <div class="col-lg-4">
            <!-- Thông tin người dùng -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin người dùng</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <?php if (!empty($thongTin['avatar'])): ?>
                            <img src="<?php echo BASE_URL . 'uploads/avatar/' . htmlspecialchars($thongTin['avatar']); ?>" 
                                 alt="Avatar" 
                                 class="rounded-circle mb-3"
                                 style="width: 120px; height: 120px; object-fit: cover; border: 3px solid var(--border-color);">
                        <?php else: ?>
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold mb-3"
                                 style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 3rem;">
                                <?php echo strtoupper(substr($thongTin['hoten'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <h5 class="mb-1"><?php echo htmlspecialchars($thongTin['hoten']); ?></h5>
                        <span class="badge bg-warning text-dark">Chờ duyệt</span>
                    </div>

                    <div class="info-group">
                        <div class="info-item mb-3">
                            <label class="text-muted small mb-1"><i class="fas fa-id-card me-1"></i> User ID</label>
                            <div class="fw-medium"><?php echo $thongTin['id']; ?></div>
                        </div>

                        <div class="info-item mb-3">
                            <label class="text-muted small mb-1"><i class="fas fa-envelope me-1"></i> Email</label>
                            <div class="fw-medium"><?php echo htmlspecialchars($thongTin['email']); ?></div>
                        </div>

                        <div class="info-item mb-3">
                            <label class="text-muted small mb-1"><i class="fas fa-phone me-1"></i> Số điện thoại</label>
                            <div class="fw-medium"><?php echo htmlspecialchars($thongTin['sodienthoai']); ?></div>
                        </div>

                        <div class="info-item mb-3">
                            <label class="text-muted small mb-1"><i class="fas fa-map-marker-alt me-1"></i> Địa chỉ cá nhân</label>
                            <div class="fw-medium"><?php echo htmlspecialchars($thongTin['diachi'] ?? 'Chưa cập nhật'); ?></div>
                        </div>

                        <div class="info-item mb-3">
                            <label class="text-muted small mb-1"><i class="fas fa-calendar-plus me-1"></i> Ngày đăng ký</label>
                            <div class="fw-medium"><?php echo date('d/m/Y H:i', strtotime($thongTin['ngaytao'])); ?></div>
                        </div>

                        <div class="info-item">
                            <label class="text-muted small mb-1"><i class="fas fa-clock me-1"></i> Ngày gửi yêu cầu</label>
                            <div class="fw-medium"><?php echo date('d/m/Y H:i', strtotime($thongTin['ngaygui'])); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thao tác -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Thao tác</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>admin/duyetyeucau/<?php echo $thongTin['id']; ?>" class="mb-3">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="hanhdong" value="duyet">
                        <button type="submit" class="btn btn-success w-100" 
                                onclick="return confirm('✓ Duyệt yêu cầu này?\n\nNgười dùng sẽ được chuyển thành Nhà tuyển dụng và có thể đăng tin tuyển dụng.')">
                            <i class="fas fa-check-circle me-2"></i>Phê duyệt yêu cầu
                        </button>
                    </form>

                    <form method="POST" action="<?php echo BASE_URL; ?>admin/duyetyeucau/<?php echo $thongTin['id']; ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="hanhdong" value="tuchoi">
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('✗ Từ chối yêu cầu này?\n\nTài khoản sẽ trở lại vai trò Ứng viên và thông tin công ty sẽ bị xóa.')">
                            <i class="fas fa-times-circle me-2"></i>Từ chối yêu cầu
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cột phải: Thông tin công ty -->
        <div class="col-lg-8">
            <!-- Thông tin công ty -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Thông tin công ty</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($thongTin['logo'])): ?>
                        <div class="text-center mb-4">
                            <img src="<?php echo BASE_URL . 'uploads/logo/' . htmlspecialchars($thongTin['logo']); ?>" 
                                 alt="Logo" 
                                 class="border rounded p-2 bg-white"
                                 style="max-width: 200px; max-height: 100px; object-fit: contain;">
                        </div>
                    <?php endif; ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small mb-1"><i class="fas fa-building me-1"></i> Tên công ty</label>
                            <div class="fw-medium fs-5 text-primary"><?php echo htmlspecialchars($thongTin['tencongty']); ?></div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1"><i class="fas fa-file-invoice me-1"></i> Mã số thuế</label>
                            <div class="fw-medium">
                                <?php if (!empty($thongTin['masothue'])): ?>
                                    <span class="badge bg-secondary fs-6"><?php echo htmlspecialchars($thongTin['masothue']); ?></span>
                                <?php else: ?>
                                    <span class="text-muted">Chưa cung cấp</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="text-muted small mb-1"><i class="fas fa-map-marker-alt me-1"></i> Địa chỉ công ty</label>
                            <div class="fw-medium"><?php echo htmlspecialchars($thongTin['diachi_congty'] ?? 'Chưa cập nhật'); ?></div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1"><i class="fas fa-envelope me-1"></i> Email công ty</label>
                            <div class="fw-medium"><?php echo htmlspecialchars($thongTin['email_congty'] ?? 'Chưa cập nhật'); ?></div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1"><i class="fas fa-globe me-1"></i> Website</label>
                            <div class="fw-medium">
                                <?php if (!empty($thongTin['website'])): ?>
                                    <a href="<?php echo htmlspecialchars($thongTin['website']); ?>" target="_blank" class="text-primary">
                                        <?php echo htmlspecialchars($thongTin['website']); ?> <i class="fas fa-external-link-alt fa-sm"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">Chưa cập nhật</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1"><i class="fas fa-users me-1"></i> Quy mô</label>
                            <div class="fw-medium">
                                <span class="badge bg-info"><?php echo htmlspecialchars($thongTin['quymo'] ?? 'Chưa xác định'); ?></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="text-muted small mb-1"><i class="fas fa-industry me-1"></i> Lĩnh vực</label>
                            <div class="fw-medium"><?php echo htmlspecialchars($thongTin['linhvuc'] ?? 'Chưa cập nhật'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mô tả công ty -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-align-left me-2"></i>Mô tả công ty</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($thongTin['mota'])): ?>
                        <p class="mb-0" style="white-space: pre-wrap;"><?php echo htmlspecialchars($thongTin['mota']); ?></p>
                    <?php else: ?>
                        <p class="text-muted mb-0"><i>Chưa có mô tả</i></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Lý do yêu cầu -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-comment-dots me-2"></i>Lý do yêu cầu trở thành nhà tuyển dụng</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($thongTin['lydoyeucau'])): ?>
                        <p class="mb-0" style="white-space: pre-wrap;"><?php echo htmlspecialchars($thongTin['lydoyeucau']); ?></p>
                    <?php else: ?>
                        <p class="text-muted mb-0"><i>Không có lý do cụ thể</i></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-item {
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.card-header {
    font-weight: 600;
}

.page-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
