<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-users"></i> Danh sách ứng viên</h1>
                <?php if (isset($tinTuyenDung) && $tinTuyenDung): ?>
                    <p><?php echo htmlspecialchars($tinTuyenDung['tieude']); ?></p>
                <?php else: ?>
                    <p>Tất cả ứng viên đã ứng tuyển vào các tin tuyển dụng của bạn</p>
                <?php endif; ?>
            </div>
            <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachtin" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <?php if (isset($thongKe)): ?>
    <!-- Thống kê -->
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-card" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($thongKe['tong'] ?? 0); ?></h3>
                <p>Tổng đơn</p>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
            <div class="stat-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($thongKe['dangxem'] ?? 0); ?></h3>
                <p>Đang xem</p>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);">
            <div class="stat-icon">
                <i class="fas fa-handshake"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($thongKe['phongvan'] ?? 0); ?></h3>
                <p>Mời PV</p>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($thongKe['nhanviec'] ?? 0); ?></h3>
                <p>Nhận việc</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bộ lọc -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-body">
            <div class="filter-group">
                <a href="?trangthai=" 
                   class="btn <?php echo !$filterTrangthai ? 'btn-primary' : 'btn-outline'; ?>">
                    <i class="fas fa-list"></i> Tất cả
                </a>
                <a href="?trangthai=moi" 
                   class="btn <?php echo $filterTrangthai == 'moi' ? 'btn-primary' : 'btn-outline'; ?>">
                    <i class="fas fa-star"></i> Mới
                </a>
                <a href="?trangthai=dangxem" 
                   class="btn <?php echo $filterTrangthai == 'dangxem' ? 'btn-primary' : 'btn-outline'; ?>">
                    <i class="fas fa-eye"></i> Đang xem
                </a>
                <a href="?trangthai=phongvan" 
                   class="btn <?php echo $filterTrangthai == 'phongvan' ? 'btn-primary' : 'btn-outline'; ?>">
                    <i class="fas fa-handshake"></i> Phỏng vấn
                </a>
                <a href="?trangthai=nhanviec" 
                   class="btn <?php echo $filterTrangthai == 'nhanviec' ? 'btn-primary' : 'btn-outline'; ?>">
                    <i class="fas fa-check-circle"></i> Nhận việc
                </a>
                <a href="?trangthai=tuchoi" 
                   class="btn <?php echo $filterTrangthai == 'tuchoi' ? 'btn-primary' : 'btn-outline'; ?>">
                    <i class="fas fa-times-circle"></i> Từ chối
                </a>
            </div>
        </div>
    </div>

    <!-- Danh sách ứng viên -->
    <div class="card">
        <div class="card-body">
            <?php if (empty($danhSach)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Chưa có ứng viên nào ứng tuyển</p>
                </div>
            <?php else: ?>
                <div class="candidate-list">
                    <?php foreach ($danhSach as $don): ?>
                    <div class="candidate-card">
                        <div class="candidate-header">
                            <div class="candidate-avatar">
                                <?php if ($don['avatar']): ?>
                                    <img src="<?php echo BASE_URL . 'uploads/avatar/' . htmlspecialchars($don['avatar']); ?>" 
                                         alt="Avatar">
                                <?php else: ?>
                                    <div class="avatar-placeholder">
                                        <?php echo strtoupper(substr($don['hoten'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="candidate-info">
                                <h3><?php echo htmlspecialchars($don['hoten']); ?></h3>
                                <div class="candidate-meta">
                                    <?php if (!isset($tinTuyenDung) || !$tinTuyenDung): ?>
                                        <span><i class="fas fa-briefcase"></i> Ứng tuyển: <?php echo htmlspecialchars($don['tieude']); ?></span>
                                    <?php endif; ?>
                                    <span><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($don['email']); ?></span>
                                    <span><i class="fas fa-phone"></i> <?php echo htmlspecialchars($don['sodienthoai']); ?></span>
                                    <span><i class="fas fa-calendar"></i> Nộp: <?php echo date('d/m/Y H:i', strtotime($don['ngaynop'])); ?></span>
                                </div>
                            </div>
                            <div class="candidate-status">
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

                        <?php if (!empty($don['thubat'])): ?>
                        <div class="candidate-letter">
                            <strong><i class="fas fa-envelope-open-text"></i> Thư bát:</strong>
                            <p><?php echo nl2br(htmlspecialchars($don['thubat'])); ?></p>
                        </div>
                        <?php endif; ?>

                        <div class="candidate-actions">
                            <a href="<?php echo BASE_URL; ?>nhatuyendung/chitietungvien/<?php echo $don['id']; ?>" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>

                            <?php if (!empty($don['cv'])): ?>
                                <a href="<?php echo BASE_URL . 'uploads/cv/' . htmlspecialchars($don['cv']); ?>" 
                                   class="btn btn-sm btn-info"
                                   target="_blank" rel="noopener noreferrer">
                                    <i class="fas fa-file-pdf"></i> Xem CV
                                </a>
                            <?php endif; ?>

                            <!-- Cập nhật trạng thái -->
                            <?php if ($don['trangthai'] != 'nhanviec'): ?>
                                <div class="dropdown" style="display: inline-block;">
                                    <button class="btn btn-sm btn-outline dropdown-toggle" type="button" data-toggle="dropdown">
                                        <i class="fas fa-edit"></i> Đổi trạng thái
                                    </button>
                                    <div class="dropdown-menu">
                                        <?php if ($don['trangthai'] != 'dangxem'): ?>
                                            <a class="dropdown-item status-update-btn" href="#" 
                                               data-don-id="<?php echo htmlspecialchars($don['id']); ?>" 
                                               data-status="dangxem">
                                                <i class="fas fa-eye"></i> Đang xem
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($don['trangthai'] != 'phongvan'): ?>
                                            <a class="dropdown-item status-update-btn" href="#" 
                                               data-don-id="<?php echo htmlspecialchars($don['id']); ?>" 
                                               data-status="phongvan">
                                                <i class="fas fa-handshake"></i> Mời phỏng vấn
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($don['trangthai'] != 'nhanviec'): ?>
                                            <a class="dropdown-item status-update-btn" href="#" 
                                               data-don-id="<?php echo htmlspecialchars($don['id']); ?>" 
                                               data-status="nhanviec">
                                                <i class="fas fa-check-circle"></i> Nhận việc
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($don['trangthai'] != 'tuchoi'): ?>
                                            <a class="dropdown-item text-danger status-update-btn" href="#" 
                                               data-don-id="<?php echo htmlspecialchars($don['id']); ?>" 
                                               data-status="tuchoi">
                                                <i class="fas fa-times-circle"></i> Từ chối
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Event delegation for status update
document.addEventListener('DOMContentLoaded', function() {
    // Status update buttons
    document.querySelectorAll('.status-update-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const donId = this.getAttribute('data-don-id');
            const trangthai = this.getAttribute('data-status');
            updateStatus(donId, trangthai);
        });
    });
});

function updateStatus(donId, trangthai) {
    if (confirm('Bạn có chắc muốn cập nhật trạng thái?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>nhatuyendung/capnhattrangthai/' + encodeURIComponent(donId);
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'trangthai';
        input.value = trangthai;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
    return false;
}

// Bootstrap dropdown toggle
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const menu = this.nextElementSibling;
            menu.classList.toggle('show');
        });
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-toggle')) {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            dropdowns.forEach(menu => menu.classList.remove('show'));
        }
    });
});
</script>

<style>
.filter-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.candidate-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.candidate-card {
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 20px;
    background: white;
    transition: all 0.3s;
}

.candidate-card:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.candidate-header {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.candidate-avatar {
    flex-shrink: 0;
}

.candidate-avatar img,
.avatar-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-placeholder {
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
}

.candidate-info {
    flex: 1;
}

.candidate-info h3 {
    margin: 0 0 10px 0;
    color: var(--primary-color);
    font-size: 1.2rem;
}

.candidate-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    color: #666;
    font-size: 0.9rem;
}

.candidate-meta i {
    margin-right: 5px;
    color: var(--primary-color);
}

.candidate-status {
    flex-shrink: 0;
}

.candidate-letter {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 15px;
}

.candidate-letter p {
    margin: 10px 0 0 0;
    color: #666;
    line-height: 1.6;
}

.candidate-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.dropdown {
    position: relative;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    min-width: 180px;
    z-index: 1000;
    margin-top: 5px;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-item {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    transition: all 0.2s;
}

.dropdown-item:hover {
    background: #f8f9fa;
}

.dropdown-item i {
    width: 20px;
    margin-right: 8px;
}

.dropdown-item.text-danger {
    color: #dc3545;
}

.badge {
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 500;
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
    .candidate-header {
        flex-direction: column;
    }
    
    .candidate-meta {
        flex-direction: column;
        gap: 8px;
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
