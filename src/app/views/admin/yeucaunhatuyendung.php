<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admin"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active">Yêu cầu nhà tuyển dụng</li>
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
                <h1 class="mb-2"><i class="fas fa-user-check me-3 text-warning"></i>Yêu cầu trở thành nhà tuyển dụng</h1>
                <p class="text-muted mb-0">Danh sách yêu cầu chờ duyệt</p>
            </div>
            <?php if (!empty($danhSach)): ?>
            <div>
                <span class="badge bg-warning text-dark" style="font-size: 0.9rem; padding: 8px 15px;">
                    <i class="fas fa-clock me-2"></i><?php echo count($danhSach); ?> yêu cầu
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Danh sách yêu cầu -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <?php if (empty($danhSach)): ?>
                <div class="empty-state py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <p class="text-muted mb-0 fs-5">Không có yêu cầu nào chờ duyệt</p>
                    <small class="text-muted">Tất cả yêu cầu đã được xử lý</small>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Người dùng</th>
                                <th>Email</th>
                                <th>Tên công ty</th>
                                <th>Mã số thuế</th>
                                <th>Địa chỉ</th>
                                <th>Ngày gửi</th>
                                <th class="text-center pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danhSach as $item): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($item['avatar'])): ?>
                                            <img src="<?php echo BASE_URL . 'uploads/avatar/' . htmlspecialchars($item['avatar']); ?>" 
                                                 alt="Avatar" 
                                                 class="rounded-circle"
                                                 style="width: 45px; height: 45px; object-fit: cover; border: 2px solid var(--border-color);">
                                        <?php else: ?>
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                                 style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <?php echo strtoupper(substr($item['hoten'], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-medium"><?php echo htmlspecialchars($item['hoten']); ?></div>
                                            <small class="text-muted">ID: <?php echo $item['id']; ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">
                                    <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($item['email']); ?>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if (!empty($item['logo'])): ?>
                                            <img src="<?php echo BASE_URL . 'uploads/logo/' . htmlspecialchars($item['logo']); ?>" 
                                                 alt="Logo" 
                                                 class="rounded"
                                                 style="width: 50px; height: 35px; object-fit: contain; border: 1px solid var(--border-color); padding: 3px; background: white;">
                                        <?php endif; ?>
                                        <div>
                                            <div class="fw-medium"><?php echo htmlspecialchars($item['tencongty']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($item['masothue'])): ?>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($item['masothue']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?php echo !empty($item['diachi_congty']) ? htmlspecialchars($item['diachi_congty']) : 'N/A'; ?>
                                </td>
                                <td class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($item['ngaygui'])); ?>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                                        <a href="<?php echo BASE_URL; ?>admin/chitietyeucau/<?php echo $item['id']; ?>" 
                                           class="btn btn-sm btn-info text-white"
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye me-1"></i>Chi tiết
                                        </a>
                                        <form method="POST" action="<?php echo BASE_URL; ?>admin/duyetyeucau/<?php echo $item['id']; ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="hanhdong" value="duyet">
                                            <button type="submit" class="btn btn-sm btn-success" 
                                                    onclick="return confirm('✓ Duyệt yêu cầu này?\n\nNgười dùng sẽ được chuyển thành Nhà tuyển dụng.')"
                                                    title="Phê duyệt">
                                                <i class="fas fa-check me-1"></i>Duyệt
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo BASE_URL; ?>admin/duyetyeucau/<?php echo $item['id']; ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="hanhdong" value="tuchoi">
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('✗ Từ chối yêu cầu này?\n\nTài khoản sẽ trở lại vai trò Ứng viên.')"
                                                    title="Từ chối">
                                                <i class="fas fa-times me-1"></i>Từ chối
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
