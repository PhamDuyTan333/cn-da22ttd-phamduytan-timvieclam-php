<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admin"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý người dùng</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-2"><i class="fas fa-users me-3"></i>Quản lý người dùng</h1>
                <p class="text-muted mb-0">Danh sách tài khoản trong hệ thống</p>
            </div>
            <div>
                <span class="badge bg-primary" style="font-size: 0.9rem; padding: 8px 15px;">
                    <i class="fas fa-user-friends me-2"></i>Tổng: <?php echo isset($pagination['total_records']) ? $pagination['total_records'] : 0; ?> người dùng
                </span>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo BASE_URL; ?>admin/nguoidung" 
                   class="btn <?php echo !$filterVaitro ? 'btn-primary' : 'btn-outline-primary'; ?> btn-sm">
                    <i class="fas fa-users me-2"></i>Tất cả
                </a>
                <a href="<?php echo BASE_URL; ?>admin/nguoidung?vaitro=ungvien" 
                   class="btn <?php echo $filterVaitro == 'ungvien' ? 'btn-primary' : 'btn-outline-primary'; ?> btn-sm">
                    <i class="fas fa-user me-2"></i>Ứng viên
                </a>
                <a href="<?php echo BASE_URL; ?>admin/nguoidung?vaitro=tuyendung" 
                   class="btn <?php echo $filterVaitro == 'tuyendung' ? 'btn-primary' : 'btn-outline-primary'; ?> btn-sm">
                    <i class="fas fa-building me-2"></i>Nhà tuyển dụng
                </a>
                <a href="<?php echo BASE_URL; ?>admin/nguoidung?vaitro=choduyet" 
                   class="btn <?php echo $filterVaitro == 'choduyet' ? 'btn-warning' : 'btn-outline-warning'; ?> btn-sm">
                    <i class="fas fa-clock me-2"></i>Chờ duyệt
                </a>
                <a href="<?php echo BASE_URL; ?>admin/nguoidung?vaitro=admin" 
                   class="btn <?php echo $filterVaitro == 'admin' ? 'btn-danger' : 'btn-outline-danger'; ?> btn-sm">
                    <i class="fas fa-user-shield me-2"></i>Admin
                </a>
            </div>
        </div>
    </div>

    <!-- Danh sách người dùng -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <?php if (empty($danhSach)): ?>
                <div class="empty-state py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Không có người dùng nào</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th class="text-center pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danhSach as $user): ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?php echo $user['id']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <?php if ($user['avatar']): ?>
                                            <img src="<?php echo BASE_URL . 'uploads/avatar/' . htmlspecialchars($user['avatar']); ?>" 
                                                 alt="Avatar" 
                                                 class="rounded-circle"
                                                 style="width: 40px; height: 40px; object-fit: cover; border: 2px solid var(--border-color);">
                                        <?php else: ?>
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <?php echo strtoupper(substr($user['hoten'], 0, 1)); ?>
                                            </div>
                                        <?php endif; ?>
                                        <span class="fw-medium"><?php echo htmlspecialchars($user['hoten']); ?></span>
                                    </div>
                                </td>
                                <td class="text-muted">
                                    <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($user['email']); ?>
                                </td>
                                <td>
                                    <?php
                                    $vaitroLabels = [
                                        'admin' => '<span class="badge bg-danger"><i class="fas fa-shield-alt me-1"></i>Admin</span>',
                                        'ungvien' => '<span class="badge bg-primary"><i class="fas fa-user me-1"></i>Ứng viên</span>',
                                        'tuyendung' => '<span class="badge bg-success"><i class="fas fa-building me-1"></i>NTD</span>',
                                        'choduyet' => '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Chờ duyệt</span>'
                                    ];
                                    echo $vaitroLabels[$user['vaitro']] ?? $user['vaitro'];
                                    ?>
                                </td>
                                <td>
                                    <?php if ($user['trangthai'] == 'hoatdong'): ?>
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><i class="fas fa-ban me-1"></i>Đã khóa</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i><?php echo date('d/m/Y', strtotime($user['ngaytao'])); ?>
                                </td>
                                <td class="text-center pe-4">
                                    <?php if ($user['vaitro'] != 'admin'): ?>
                                        <form method="POST" action="<?php echo BASE_URL; ?>admin/khoataikhoan/<?php echo $user['id']; ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php if ($user['trangthai'] == 'hoatdong'): ?>
                                                <button type="submit" class="btn btn-sm btn-warning" 
                                                        onclick="return confirm('Khóa tài khoản này?')"
                                                        title="Khóa tài khoản">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        onclick="return confirm('Mở khóa tài khoản này?')"
                                                        title="Mở khóa">
                                                    <i class="fas fa-unlock"></i>
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <?php if (isset($pagination['total_pages']) && $pagination['total_pages'] > 1): ?>
                <nav aria-label="Phân trang" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <li class="page-item">
                                <a href="?page=<?php echo $pagination['current_page'] - 1; ?><?php echo $filterVaitro ? '&vaitro=' . $filterVaitro : ''; ?>" 
                                   class="page-link">
                                    <i class="fas fa-chevron-left"></i> <span class="d-none d-sm-inline">Trước</span>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-left"></i> <span class="d-none d-sm-inline">Trước</span></span>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                            <?php if ($i == $pagination['current_page']): ?>
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link"><?php echo $i; ?></span>
                                </li>
                            <?php elseif ($i == 1 || $i == $pagination['total_pages'] || abs($i - $pagination['current_page']) <= 2): ?>
                                <li class="page-item">
                                    <a href="?page=<?php echo $i; ?><?php echo $filterVaitro ? '&vaitro=' . $filterVaitro : ''; ?>" 
                                       class="page-link">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php elseif (abs($i - $pagination['current_page']) == 3): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <li class="page-item">
                                <a href="?page=<?php echo $pagination['current_page'] + 1; ?><?php echo $filterVaitro ? '&vaitro=' . $filterVaitro : ''; ?>" 
                                   class="page-link">
                                    <span class="d-none d-sm-inline">Sau</span> <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="page-item disabled">
                                <span class="page-link"><span class="d-none d-sm-inline">Sau</span> <i class="fas fa-chevron-right"></i></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Breadcrumb */
.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item a {
    color: var(--text-light);
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover {
    color: var(--primary-color);
}

.breadcrumb-item.active {
    color: var(--text-color);
    font-weight: 500;
}

/* Page Header */
.page-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark-color);
}

/* Filter Buttons */
.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover,
.btn-outline-warning:hover,
.btn-outline-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state i {
    display: block;
    margin-bottom: 1rem;
}

/* Table Improvements */
.table {
    font-size: 0.95rem;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    color: var(--text-color);
    border-bottom: 2px solid var(--border-color);
    padding: 1rem 0.75rem;
}

.table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(99, 102, 241, 0.05);
    transform: scale(1.001);
}

.badge {
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 500;
}

.badge-primary {
    background: #007bff;
    color: white;
}

.badge-success {
    background: #28a745;
    color: white;
}

.badge-danger {
    background: #dc3545;
    color: white;
}

.badge-warning {
    background: #ffc107;
    color: #333;
}

/* Pagination Styles */
.pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 6px;
    gap: 5px;
}

.page-item:first-child .page-link {
    border-top-left-radius: 6px;
    border-bottom-left-radius: 6px;
}

.page-item:last-child .page-link {
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
}

.page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: 0;
    line-height: 1.25;
    color: #007bff;
    background-color: #fff;
    border: 1px solid #dee2e6;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.page-link:hover {
    z-index: 2;
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #dee2e6;
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.page-link:focus {
    z-index: 3;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
    font-weight: 600;
    box-shadow: 0 2px 5px rgba(0,123,255,0.3);
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    cursor: not-allowed;
    background-color: #fff;
    border-color: #dee2e6;
    opacity: 0.6;
}

@media (max-width: 576px) {
    .page-link {
        padding: 0.4rem 0.6rem;
        font-size: 0.875rem;
    }
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
