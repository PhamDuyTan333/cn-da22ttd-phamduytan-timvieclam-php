<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>admin"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active">Quản lý Danh mục</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="page-header mb-4">
        <div>
            <h1 class="mb-2"><i class="fas fa-list-ul me-3 text-primary"></i>Quản lý Danh mục Hệ thống</h1>
            <p class="text-muted mb-0">Quản lý các danh mục và cấu hình hệ thống</p>
        </div>
    </div>
    
    <!-- Category Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-industry fa-3x text-primary"></i>
                    </div>
                    <h2 class="mb-2 fw-bold text-primary"><?php echo $stats['nganhnghe']; ?></h2>
                    <p class="text-muted mb-3">Ngành nghề</p>
                    <a href="<?php echo BASE_URL; ?>danhmuc/nganhnghe" class="btn btn-primary btn-sm">
                        <i class="fas fa-cog me-2"></i>Quản lý
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-dollar-sign fa-3x text-success"></i>
                    </div>
                    <h2 class="mb-2 fw-bold text-success"><?php echo $stats['mucluong']; ?></h2>
                    <p class="text-muted mb-3">Mức lương</p>
                    <a href="<?php echo BASE_URL; ?>danhmuc/mucluong" class="btn btn-success btn-sm">
                        <i class="fas fa-cog me-2"></i>Quản lý
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-briefcase fa-3x text-info"></i>
                    </div>
                    <h2 class="mb-2 fw-bold text-info"><?php echo $stats['loaicv']; ?></h2>
                    <p class="text-muted mb-3">Loại công việc</p>
                    <a href="<?php echo BASE_URL; ?>danhmuc/loaicongviec" class="btn btn-info btn-sm text-white">
                        <i class="fas fa-cog me-2"></i>Quản lý
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-map-marker-alt fa-3x text-danger"></i>
                    </div>
                    <h2 class="mb-2 fw-bold text-danger"><?php echo $stats['tinhthanh']; ?></h2>
                    <p class="text-muted mb-3">Tỉnh/Thành phố</p>
                    <a href="<?php echo BASE_URL; ?>danhmuc/tinhthanh" class="btn btn-danger btn-sm">
                        <i class="fas fa-cog me-2"></i>Quản lý
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- System Config Section -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
                <div class="me-3">
                    <i class="fas fa-sliders-h fa-2x text-warning"></i>
                </div>
                <div>
                    <h3 class="mb-1">Cấu hình Hệ thống</h3>
                    <p class="text-muted mb-0">Quản lý giới hạn dung lượng CV, thời hạn tin tuyển dụng</p>
                </div>
            </div>
            <a href="<?php echo BASE_URL; ?>danhmuc/cauhinh" class="btn btn-warning">
                <i class="fas fa-cog me-2"></i>Cấu hình
            </a>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>

