<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="page-header">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard nhà tuyển dụng</h1>
        <p>Chào mừng, <?php echo htmlspecialchars($_SESSION['hoten']); ?></p>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="stats-grid">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="stat-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($stats['tongtin'] ?? 0); ?></h3>
                <p>Tổng tin đăng</p>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($stats['dangmo'] ?? 0); ?></h3>
                <p>Đang mở</p>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($stats['choduyet'] ?? 0); ?></h3>
                <p>Chờ duyệt</p>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($tongdon ?? 0); ?></h3>
                <p>Đơn ứng tuyển</p>
            </div>
        </div>
    </div>

    <!-- Hành động nhanh -->
    <div class="card" style="margin-top: 30px;">
        <div class="card-header">
            <h3><i class="fas fa-bolt"></i> Hành động nhanh</h3>
        </div>
        <div class="card-body">
            <div class="quick-actions">
                <a href="<?php echo BASE_URL; ?>nhatuyendung/dangtin" class="action-item">
                    <i class="fas fa-plus-circle"></i>
                    <span>Đăng tin mới</span>
                </a>
                <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachtin" class="action-item">
                    <i class="fas fa-list"></i>
                    <span>Quản lý tin</span>
                </a>
                <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachungvien" class="action-item">
                    <i class="fas fa-users"></i>
                    <span>Xem ứng viên</span>
                </a>
                <a href="<?php echo BASE_URL; ?>taikhoan" class="action-item">
                    <i class="fas fa-cog"></i>
                    <span>Cài đặt hồ sơ</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Tin tuyển dụng gần đây -->
    <?php if (!empty($tinGanDay)): ?>
    <div class="card" style="margin-top: 30px;">
        <div class="card-header">
            <h3><i class="fas fa-history"></i> Tin đăng gần đây</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tiêu đề</th>
                            <th>Ngày đăng</th>
                            <th>Hạn nộp</th>
                            <th>Trạng thái</th>
                            <th>Ứng viên</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tinGanDay as $tin): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($tin['tieude']); ?></strong>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($tin['ngaydang'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($tin['hannop'])); ?></td>
                            <td>
                                <?php
                                $statusLabels = [
                                    'choduyet' => '<span class="badge badge-warning">Chờ duyệt</span>',
                                    'dangmo' => '<span class="badge badge-success">Đang mở</span>',
                                    'an' => '<span class="badge badge-secondary">Đã ẩn</span>',
                                    'hethan' => '<span class="badge badge-danger">Hết hạn</span>'
                                ];
                                echo $statusLabels[$tin['trangthai']] ?? $tin['trangthai'];
                                ?>
                            </td>
                            <td>
                                <span class="badge badge-primary"><?php echo $tin['sodon']; ?> đơn</span>
                            </td>
                            <td>
                                <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachungvien/<?php echo $tin['id']; ?>" 
                                   class="btn btn-sm btn-primary"
                                   title="Xem ứng viên">
                                    <i class="fas fa-users"></i>
                                </a>
                                <a href="<?php echo BASE_URL; ?>nhatuyendung/suatin/<?php echo $tin['id']; ?>" 
                                   class="btn btn-sm btn-info"
                                   title="Sửa tin">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachtin" class="btn btn-outline">
                    Xem tất cả <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.action-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px;
    background: #f8f9fa;
    border-radius: 12px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s;
    text-align: center;
}

.action-item:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.action-item i {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.action-item span {
    font-weight: 500;
    font-size: 1rem;
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
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
