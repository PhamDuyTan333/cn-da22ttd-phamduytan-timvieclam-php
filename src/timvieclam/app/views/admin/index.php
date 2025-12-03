<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active"><i class="fas fa-home me-2"></i>Trang quản trị</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-2"><i class="fas fa-tachometer-alt me-3"></i>Dashboard</h1>
                <p class="text-muted mb-0">Tổng quan hệ thống và hoạt động</p>
            </div>
            <div>
                <span class="badge bg-primary" style="font-size: 0.9rem; padding: 8px 15px;">
                    <i class="fas fa-clock me-2"></i><?php echo date('d/m/Y H:i'); ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3 class="mb-1"><?php echo number_format($stats['tongungvien'] ?? 0); ?></h3>
                    <p class="mb-0">Ứng viên</p>
                    <small class="d-block mt-2 opacity-75">
                        <i class="fas fa-arrow-up me-1"></i>Đã đăng ký
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-info">
                    <h3 class="mb-1"><?php echo number_format($stats['tongnhatd'] ?? 0); ?></h3>
                    <p class="mb-0">Nhà tuyển dụng</p>
                    <small class="d-block mt-2 opacity-75">
                        <i class="fas fa-check-circle me-1"></i>Đã duyệt
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stat-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="stat-info">
                    <h3 class="mb-1"><?php echo number_format($stats['tindangmo'] ?? 0); ?></h3>
                    <p class="mb-0">Tin đang mở</p>
                    <small class="d-block mt-2 opacity-75">
                        <i class="fas fa-eye me-1"></i>Đang tuyển
                    </small>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="stat-card shadow-sm" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-info">
                    <h3 class="mb-1"><?php echo number_format($stats['tongdon'] ?? 0); ?></h3>
                    <p class="mb-0">Đơn ứng tuyển</p>
                    <small class="d-block mt-2 opacity-75">
                        <i class="fas fa-paper-plane me-1"></i>Đã nộp
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông báo chờ duyệt -->
    <?php if (($stats['choduyet'] ?? 0) > 0 || ($stats['tinchoduyet'] ?? 0) > 0): ?>
    <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading mb-2">
                    <strong>Có yêu cầu chờ duyệt!</strong>
                </h5>
                <div class="d-flex flex-wrap gap-3">
                    <?php if ($stats['choduyet'] > 0): ?>
                        <a href="<?php echo BASE_URL; ?>admin/yeucaunhatuyendung" class="alert-link text-decoration-none">
                            <span class="badge bg-dark me-2"><?php echo $stats['choduyet']; ?></span>
                            yêu cầu nhà tuyển dụng
                        </a>
                    <?php endif; ?>
                    <?php if ($stats['tinchoduyet'] > 0): ?>
                        <a href="<?php echo BASE_URL; ?>admin/tintuyendung?trangthai=choduyet" class="alert-link text-decoration-none">
                            <span class="badge bg-dark me-2"><?php echo $stats['tinchoduyet']; ?></span>
                            tin tuyển dụng
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Biểu đồ thống kê -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2 text-primary"></i>Biểu đồ người dùng đăng ký</h5>
                    <small class="text-muted">Thống kê 12 tháng gần nhất</small>
                </div>
                <div class="card-body">
                    <canvas id="chartThang" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Menu quản trị -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2 text-primary"></i>Menu quản trị</h5>
                    <small class="text-muted">Truy cập nhanh</small>
                </div>
                <div class="card-body p-3">
                    <div class="admin-menu">
                        <a href="<?php echo BASE_URL; ?>admin/nguoidung" class="admin-menu-item">
                            <div class="menu-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="flex-grow-1">Quản lý người dùng</span>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        <a href="<?php echo BASE_URL; ?>admin/yeucaunhatuyendung" class="admin-menu-item">
                            <div class="menu-icon bg-warning bg-opacity-10 text-warning">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <span class="flex-grow-1">Duyệt nhà tuyển dụng</span>
                            <?php if ($stats['choduyet'] > 0): ?>
                                <span class="badge bg-danger rounded-pill"><?php echo $stats['choduyet']; ?></span>
                            <?php else: ?>
                                <i class="fas fa-chevron-right text-muted"></i>
                            <?php endif; ?>
                        </a>
                        <a href="<?php echo BASE_URL; ?>admin/tintuyendung" class="admin-menu-item">
                            <div class="menu-icon bg-info bg-opacity-10 text-info">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <span class="flex-grow-1">Quản lý tin tuyển dụng</span>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        <a href="<?php echo BASE_URL; ?>admin/donungtuyen" class="admin-menu-item">
                            <div class="menu-icon bg-success bg-opacity-10 text-success">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <span class="flex-grow-1">Quản lý đơn ứng tuyển</span>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        <a href="<?php echo BASE_URL; ?>danhmuc" class="admin-menu-item">
                            <div class="menu-icon bg-secondary bg-opacity-10 text-secondary">
                                <i class="fas fa-list"></i>
                            </div>
                            <span class="flex-grow-1">Quản lý danh mục</span>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                        <a href="<?php echo BASE_URL; ?>admin/thongke" class="admin-menu-item">
                            <div class="menu-icon bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <span class="flex-grow-1">Thống kê chi tiết</span>
                            <i class="fas fa-chevron-right text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tin mới chờ duyệt -->
    <?php if (!empty($tinMoi)): ?>
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><i class="fas fa-clock me-2 text-warning"></i>Tin tuyển dụng mới chờ duyệt</h5>
                    <small class="text-muted">Yêu cầu xem xét và phê duyệt</small>
                </div>
                <span class="badge bg-warning text-dark"><?php echo count($tinMoi); ?> tin</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tiêu đề</th>
                            <th>Công ty</th>
                            <th>Người đăng</th>
                            <th>Ngày đăng</th>
                            <th class="text-center pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tinMoi as $tin): ?>
                        <tr>
                            <td>
                                <a href="<?php echo BASE_URL; ?>tintuyendung/chitiet/<?php echo $tin['id']; ?>" target="_blank" rel="noopener noreferrer">
                                    <?php echo htmlspecialchars($tin['tieude']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($tin['tencongty'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($tin['hoten']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($tin['ngaydang'])); ?></td>
                            <td>
                                <form method="POST" action="<?php echo BASE_URL; ?>admin/duyettin/<?php echo $tin['id']; ?>" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="trangthai" value="dangmo">
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Duyệt tin này?')">
                                        <i class="fas fa-check"></i> Duyệt
                                    </button>
                                </form>
                                <form method="POST" action="<?php echo BASE_URL; ?>admin/duyettin/<?php echo $tin['id']; ?>" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="trangthai" value="an">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Từ chối tin này?')">
                                        <i class="fas fa-times"></i> Từ chối
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Biểu đồ thống kê theo tháng
const ctx = document.getElementById('chartThang');
if (ctx) {
    const thangData = <?php echo json_encode($thongKeThang); ?>;
    
    const labels = thangData.map(item => {
        const [year, month] = item.thang.split('-');
        return `${month}/${year}`;
    });
    
    const data = thangData.map(item => item.soluong);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Người dùng đăng ký',
                data: data,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}
</script>

<style>
/* Breadcrumb */
.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item {
    font-size: 0.9rem;
    color: var(--text-light);
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

/* Stat Cards */
.stat-card {
    border-radius: 12px;
    padding: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.3;
    position: absolute;
    right: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
}

.stat-info {
    position: relative;
    z-index: 1;
}

.stat-info h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    line-height: 1;
}

.stat-info p {
    font-size: 1rem;
    margin: 0;
    opacity: 0.9;
    font-weight: 500;
}

.stat-info small {
    font-size: 0.85rem;
    opacity: 0.8;
}

/* Admin Menu */
.admin-menu {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.admin-menu-item {
    display: flex;
    align-items: center;
    padding: 12px;
    background: var(--light-color);
    border-radius: 8px;
    text-decoration: none;
    color: var(--text-color);
    transition: all 0.3s ease;
    border: 1px solid transparent;
    gap: 12px;
}

.admin-menu-item:hover {
    background: var(--primary-color);
    color: white;
    transform: translateX(5px);
    border-color: var(--primary-color);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.admin-menu-item:hover .menu-icon {
    background: rgba(255,255,255,0.2) !important;
    color: white !important;
}

.admin-menu-item:hover .text-muted {
    color: white !important;
}

.menu-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

/* Alert Improvements */
.alert {
    border-radius: 10px;
    border: none;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe5a0 100%);
    border-left: 4px solid #ffc107;
    color: #856404;
}

.alert-link {
    font-weight: 600;
    transition: all 0.3s ease;
}

.alert-link:hover {
    transform: translateX(5px);
    display: inline-block;
}

/* Card Improvements */
.card {
    border-radius: 12px;
    border: none;
    transition: box-shadow 0.3s ease;
}

.card:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
    padding: 1.25rem 1.5rem;
}

.card-header h5 {
    font-weight: 600;
    color: var(--dark-color);
}

/* Table Improvements */
.table {
    font-size: 0.95rem;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    color: var(--text-color);
    border-bottom: 2px solid var(--border-color);
}

.table tbody tr {
    transition: all 0.2s ease;
}

.table tbody tr:hover {
    background-color: var(--light-color);
    transform: scale(1.01);
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header h1 {
        font-size: 1.5rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .stat-info h3 {
        font-size: 2rem;
    }
    
    .admin-menu-item {
        padding: 10px;
    }
    
    .menu-icon {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
