<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-list"></i> Danh sách tin tuyển dụng</h1>
                <p>Quản lý tin tuyển dụng của bạn</p>
            </div>
            <a href="<?php echo BASE_URL; ?>nhatuyendung/dangtin" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Đăng tin mới
            </a>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-body">
            <div class="filter-group">
                <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachtin" 
                   class="btn <?php echo !$filterTrangthai ? 'btn-primary' : 'btn-outline'; ?>">
                    <i class="fas fa-list"></i> Tất cả
                </a>
                <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachtin?trangthai=dangmo" 
                   class="btn <?php echo $filterTrangthai == 'dangmo' ? 'btn-primary' : 'btn-outline'; ?>">
                    <i class="fas fa-check-circle"></i> Đang mở
                </a>
                <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachtin?trangthai=choduyet" 
                   class="btn <?php echo $filterTrangthai == 'choduyet' ? 'btn-primary' : 'btn-outline'; ?>">
                    <i class="fas fa-clock"></i> Chờ duyệt
                </a>
                <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachtin?trangthai=an" 
                   class="btn <?php echo $filterTrangthai == 'an' ? 'btn-primary' : 'btn-outline'; ?>">
                    <i class="fas fa-eye-slash"></i> Đã ẩn
                </a>
                <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachtin?trangthai=hethan" 
                   class="btn <?php echo $filterTrangthai == 'hethan' ? 'btn-primary' : 'btn-outline'; ?>">
                    <i class="fas fa-calendar-times"></i> Hết hạn
                </a>
            </div>
        </div>
    </div>

    <!-- Danh sách tin -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($danhSach)): ?>
                <div class="empty-state">
                    <i class="fas fa-briefcase"></i>
                    <p>Bạn chưa đăng tin tuyển dụng nào</p>
                    <a href="<?php echo BASE_URL; ?>nhatuyendung/dangtin" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Đăng tin ngay
                    </a>
                </div>
            <?php else: ?>
                <div class="job-list">
                    <?php foreach ($danhSach as $tin): ?>
                    <div class="job-card">
                        <div class="job-header">
                            <h3><?php echo htmlspecialchars($tin['tieude']); ?></h3>
                            <?php
                            $statusLabels = [
                                'choduyet' => '<span class="badge badge-warning">Chờ duyệt</span>',
                                'dangmo' => '<span class="badge badge-success">Đang mở</span>',
                                'an' => '<span class="badge badge-secondary">Đã ẩn</span>',
                                'hethan' => '<span class="badge badge-danger">Hết hạn</span>'
                            ];
                            echo $statusLabels[$tin['trangthai']] ?? $tin['trangthai'];
                            ?>
                        </div>
                        
                        <div class="job-info">
                            <div class="info-item">
                                <i class="fas fa-calendar"></i>
                                <span>Đăng: <?php echo date('d/m/Y', strtotime($tin['ngaydang'])); ?></span>
                            </div>
                            <?php if (!empty($tin['hannop'])): ?>
                            <div class="info-item">
                                <i class="fas fa-calendar-times"></i>
                                <span>Hạn: <?php echo date('d/m/Y', strtotime($tin['hannop'])); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="info-item">
                                <i class="fas fa-eye"></i>
                                <span><?php echo number_format($tin['luotxem'] ?? 0); ?> lượt xem</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-file-alt"></i>
                                <span><?php echo number_format($tin['sodon']); ?> đơn ứng tuyển</span>
                            </div>
                        </div>
                        
                        <div class="job-actions">
                            <a href="<?php echo BASE_URL; ?>tintuyendung/chitiet/<?php echo $tin['id']; ?>" 
                               class="btn btn-sm btn-outline"
                               target="_blank" rel="noopener noreferrer"
                               title="Xem tin">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachungvien/<?php echo $tin['id']; ?>" 
                               class="btn btn-sm btn-primary"
                               title="Danh sách ứng viên">
                                <i class="fas fa-users"></i> Ứng viên (<?php echo $tin['sodon']; ?>)
                            </a>
                            <a href="<?php echo BASE_URL; ?>nhatuyendung/suatin/<?php echo $tin['id']; ?>" 
                               class="btn btn-sm btn-info"
                               title="Sửa tin">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <a href="<?php echo BASE_URL; ?>nhatuyendung/xoatin/<?php echo $tin['id']; ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa tin này?')"
                               title="Xóa tin">
                                <i class="fas fa-trash"></i> Xóa
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Phân trang -->
                <?php if ($pagination['total_pages'] > 1): ?>
                <div class="pagination">
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="?page=<?php echo $pagination['current_page'] - 1; ?><?php echo $filterTrangthai ? '&trangthai=' . htmlspecialchars($filterTrangthai) : ''; ?>" 
                           class="btn btn-outline">
                            <i class="fas fa-chevron-left"></i> Trước
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <?php if ($i == $pagination['current_page']): ?>
                            <span class="btn btn-primary"><?php echo $i; ?></span>
                        <?php elseif ($i == 1 || $i == $pagination['total_pages'] || abs($i - $pagination['current_page']) <= 2): ?>
                            <a href="?page=<?php echo $i; ?><?php echo $filterTrangthai ? '&trangthai=' . htmlspecialchars($filterTrangthai) : ''; ?>" 
                               class="btn btn-outline">
                                <?php echo $i; ?>
                            </a>
                        <?php elseif (abs($i - $pagination['current_page']) == 3): ?>
                            <span class="btn btn-outline disabled">...</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <a href="?page=<?php echo $pagination['current_page'] + 1; ?><?php echo $filterTrangthai ? '&trangthai=' . htmlspecialchars($filterTrangthai) : ''; ?>" 
                           class="btn btn-outline">
                            Sau <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
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

.job-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.job-card {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    background: white;
    transition: all 0.3s;
}

.job-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.job-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 15px;
    gap: 15px;
}

.job-header h3 {
    margin: 0;
    color: var(--primary-color);
    font-size: 1.3rem;
}

.job-info {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 15px;
    padding: 15px 0;
    border-top: 1px solid #f0f0f0;
    border-bottom: 1px solid #f0f0f0;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
}

.info-item i {
    color: var(--primary-color);
}

.job-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.badge {
    padding: 5px 12px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 500;
    white-space: nowrap;
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

.badge-secondary {
    background: #6c757d;
    color: white;
}

@media (max-width: 768px) {
    .job-header {
        flex-direction: column;
    }
    
    .job-info {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
