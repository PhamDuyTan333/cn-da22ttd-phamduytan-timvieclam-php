<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admin"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý tin tuyển dụng</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-2"><i class="fas fa-briefcase me-3 text-info"></i>Quản lý tin tuyển dụng</h1>
                <p class="text-muted mb-0">Danh sách tin tuyển dụng trong hệ thống</p>
            </div>
            <div>
                <span class="badge bg-info" style="font-size: 0.9rem; padding: 8px 15px;">
                    <i class="fas fa-layer-group me-2"></i>Tổng: <?php echo $pagination['total_records']; ?> tin
                </span>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo BASE_URL; ?>admin/tintuyendung" 
                   class="btn <?php echo !$filterTrangthai ? 'btn-primary' : 'btn-outline-primary'; ?> btn-sm">
                    <i class="fas fa-list me-2"></i>Tất cả
                </a>
                <a href="<?php echo BASE_URL; ?>admin/tintuyendung?trangthai=dangmo" 
                   class="btn <?php echo $filterTrangthai == 'dangmo' ? 'btn-success' : 'btn-outline-success'; ?> btn-sm">
                    <i class="fas fa-check-circle me-2"></i>Đang mở
                </a>
                <a href="<?php echo BASE_URL; ?>admin/tintuyendung?trangthai=choduyet" 
                   class="btn <?php echo $filterTrangthai == 'choduyet' ? 'btn-warning' : 'btn-outline-warning'; ?> btn-sm">
                    <i class="fas fa-clock me-2"></i>Chờ duyệt
                </a>
                <a href="<?php echo BASE_URL; ?>admin/tintuyendung?trangthai=an" 
                   class="btn <?php echo $filterTrangthai == 'an' ? 'btn-secondary' : 'btn-outline-secondary'; ?> btn-sm">
                    <i class="fas fa-eye-slash me-2"></i>Đã ẩn
                </a>
                <a href="<?php echo BASE_URL; ?>admin/tintuyendung?trangthai=hethan" 
                   class="btn <?php echo $filterTrangthai == 'hethan' ? 'btn-danger' : 'btn-outline-danger'; ?> btn-sm">
                    <i class="fas fa-calendar-times me-2"></i>Hết hạn
                </a>
            </div>
        </div>
    </div>

    <!-- Danh sách tin -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <?php if (empty($danhSach)): ?>
                <div class="empty-state py-5">
                    <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                    <p class="text-muted mb-0 fs-5">Không có tin tuyển dụng nào</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Tiêu đề</th>
                                <th>Tiêu đề</th>
                                <th>Công ty</th>
                                <th>Người đăng</th>
                                <th>Ngày đăng</th>
                                <th>Hạn nộp</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($danhSach as $tin): ?>
                            <tr>
                                <td><?php echo $tin['id']; ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>tintuyendung/chitiet/<?php echo $tin['id']; ?>" 
                                       target="_blank" rel="noopener noreferrer"
                                       style="font-weight: 500;">
                                        <?php echo htmlspecialchars($tin['tieude']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($tin['tencongty'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($tin['hoten']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($tin['ngaydang'])); ?></td>
                                <td>
                                    <?php 
                                    if (!empty($tin['ngayhethan'])) {
                                        echo date('d/m/Y', strtotime($tin['ngayhethan']));
                                    } else {
                                        echo 'N/A';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $trangthaiLabels = [
                                        'choduyet' => '<span class="badge badge-warning">Chờ duyệt</span>',
                                        'dangmo' => '<span class="badge badge-success">Đang mở</span>',
                                        'an' => '<span class="badge badge-secondary">Đã ẩn</span>',
                                        'hethan' => '<span class="badge badge-danger">Hết hạn</span>'
                                    ];
                                    echo $trangthaiLabels[$tin['trangthai']] ?? $tin['trangthai'];
                                    ?>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <?php if ($tin['trangthai'] == 'choduyet'): ?>
                                            <form method="POST" action="<?php echo BASE_URL; ?>admin/duyettin/<?php echo $tin['id']; ?>" style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="trangthai" value="dangmo">
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        onclick="return confirm('Duyệt tin này?')"
                                                        title="Duyệt tin">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if (in_array($tin['trangthai'], ['dangmo', 'choduyet'])): ?>
                                            <form method="POST" action="<?php echo BASE_URL; ?>admin/duyettin/<?php echo $tin['id']; ?>" style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="trangthai" value="an">
                                                <button type="submit" class="btn btn-sm btn-warning" 
                                                        onclick="return confirm('Ẩn tin này?')"
                                                        title="Ẩn tin">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($tin['trangthai'] == 'an'): ?>
                                            <form method="POST" action="<?php echo BASE_URL; ?>admin/duyettin/<?php echo $tin['id']; ?>" style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="trangthai" value="dangmo">
                                                <button type="submit" class="btn btn-sm btn-info" 
                                                        onclick="return confirm('Hiện tin này?')"
                                                        title="Hiện tin">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <a href="<?php echo BASE_URL; ?>admin/xoatin/<?php echo $tin['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Xóa tin này? Hành động không thể hoàn tác!')"
                                           title="Xóa tin">
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
                                <a href="?page=<?php echo $pagination['current_page'] - 1; ?><?php echo $filterTrangthai ? '&trangthai=' . htmlspecialchars($filterTrangthai) : ''; ?>" 
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
                                    <a href="?page=<?php echo $i; ?><?php echo $filterTrangthai ? '&trangthai=' . htmlspecialchars($filterTrangthai) : ''; ?>" 
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
                                <a href="?page=<?php echo $pagination['current_page'] + 1; ?><?php echo $filterTrangthai ? '&trangthai=' . htmlspecialchars($filterTrangthai) : ''; ?>" 
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
.filter-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

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
