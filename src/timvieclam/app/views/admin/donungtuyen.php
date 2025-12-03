<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admin"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý đơn ứng tuyển</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-2"><i class="fas fa-file-alt me-3 text-success"></i>Quản lý đơn ứng tuyển</h1>
                <p class="text-muted mb-0">Danh sách tất cả đơn ứng tuyển</p>
            </div>
            <?php if (!empty($danhSach)): ?>
            <div>
                <span class="badge bg-success" style="font-size: 0.9rem; padding: 8px 15px;">
                    <i class="fas fa-paper-plane me-2"></i>Tổng: <?php echo $pagination['total_records']; ?> đơn
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Danh sách đơn -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <?php if (empty($danhSach)): ?>
                <div class="empty-state py-5">
                    <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                    <p class="text-muted mb-0 fs-5">Chưa có đơn ứng tuyển nào</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Ứng viên</th>
                                <th>Email</th>
                                <th>Tin tuyển dụng</th>
                                <th>Công ty</th>
                                <th>Ngày nộp</th>
                                <th>Trạng thái</th>
                                <th class="text-center pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danhSach as $don): ?>
                            <tr>
                                <td><?php echo $don['id']; ?></td>
                                <td><?php echo htmlspecialchars($don['tenungvien']); ?></td>
                                <td><?php echo htmlspecialchars($don['email']); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>tintuyendung/chitiet/<?php echo $don['tintuyendung_id']; ?>" 
                                       target="_blank" rel="noopener noreferrer"
                                       style="font-weight: 500;">
                                        <?php echo htmlspecialchars($don['tieude']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($don['tencongty'] ?? 'N/A'); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($don['ngaynop'])); ?></td>
                                <td>
                                    <?php
                                    $statusLabels = [
                                        'moi' => '<span class="badge badge-info">Mới</span>',
                                        'dangxem' => '<span class="badge badge-primary">Đang xem</span>',
                                        'phongvan' => '<span class="badge badge-warning">Mời PV</span>',
                                        'nhanviec' => '<span class="badge badge-success">Nhận việc</span>',
                                        'tuchoi' => '<span class="badge badge-danger">Từ chối</span>'
                                    ];
                                    echo $statusLabels[$don['trangthai']] ?? $don['trangthai'];
                                    ?>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <?php if (!empty($don['cv'])): ?>
                                            <a href="<?php echo BASE_URL . 'uploads/cv/' . htmlspecialchars($don['cv']); ?>" 
                                               class="btn btn-sm btn-primary" 
                                               target="_blank" rel="noopener noreferrer"
                                               title="Xem CV">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="<?php echo BASE_URL; ?>admin/xoadon/<?php echo $don['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Xóa đơn này? Hành động không thể hoàn tác!')"
                                           title="Xóa đơn">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Phân trang -->
                <?php if ($pagination['total_pages'] > 1): ?>
                <nav aria-label="Phân trang" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <li class="page-item">
                                <a href="?page=<?php echo $pagination['current_page'] - 1; ?>" class="page-link">
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
                                    <a href="?page=<?php echo $i; ?>" class="page-link">
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
                                <a href="?page=<?php echo $pagination['current_page'] + 1; ?>" class="page-link">
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
.badge {
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 500;
}

.badge-success {
    background: #28a745;
    color: white;
}

.badge-warning {
    background: #ffc107;
    color: #333;
}

.badge-danger {
    background: #dc3545;
    color: white;
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
