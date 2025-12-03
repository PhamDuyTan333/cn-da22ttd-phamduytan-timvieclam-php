<?php
$pageTitle = isset($pageTitle) ? $pageTitle : 'Trang chủ';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>Tìm Việc Làm Mơ Ước Của Bạn</h1>
            <p>Kết nối hàng nghìn ứng viên với nhà tuyển dụng hàng đầu</p>
            
            <div class="search-box">
                <form action="<?php echo BASE_URL; ?>timkiem" method="GET" class="search-form">
                    <div class="search-input">
                        <i class="fas fa-search"></i>
                        <input type="text" name="tukhoa" placeholder="Vị trí tuyển dụng, công ty..." class="form-control">
                    </div>
                    <div class="search-select">
                        <i class="fas fa-map-marker-alt"></i>
                        <select name="tinhthanh" class="form-control">
                            <option value="">Tất cả tỉnh/thành</option>
                            <?php if (!empty($tinhThanh)): ?>
                                <?php foreach ($tinhThanh as $tinh): ?>
                                    <option value="<?php echo $tinh['id']; ?>">
                                        <?php echo htmlspecialchars($tinh['tentinh']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </form>
            </div>
            
            <div class="popular-keywords">
                <span><i class="fas fa-fire"></i> Từ khóa phổ biến:</span>
                <?php if (!empty($tuKhoaPhoBien)): ?>
                    <?php foreach ($tuKhoaPhoBien as $tukhoa): ?>
                        <a href="<?php echo BASE_URL; ?>timkiem?tukhoa=<?php echo urlencode($tukhoa['tennganh']); ?>" 
                           class="keyword-chip">
                            <?php echo htmlspecialchars($tukhoa['tennganh']); ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>timkiem?tukhoa=PHP" class="keyword-chip">PHP</a>
                    <a href="<?php echo BASE_URL; ?>timkiem?tukhoa=JavaScript" class="keyword-chip">JavaScript</a>
                    <a href="<?php echo BASE_URL; ?>timkiem?tukhoa=Marketing" class="keyword-chip">Marketing</a>
                    <a href="<?php echo BASE_URL; ?>timkiem?tukhoa=Kế toán" class="keyword-chip">Kế toán</a>
                    <a href="<?php echo BASE_URL; ?>timkiem?tukhoa=Nhân sự" class="keyword-chip">Nhân sự</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-briefcase"></i>
                <h3 data-target="<?php echo ($stats['tongtin'] ?? 0); ?>">0</h3>
                <p>Việc làm đang tuyển</p>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-building"></i>
                <h3 data-target="<?php echo ($stats['tongnhatd'] ?? 0); ?>">0</h3>
                <p>Nhà tuyển dụng</p>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-users"></i>
                <h3 data-target="<?php echo ($stats['tongungvien'] ?? 0); ?>">0</h3>
                <p>Ứng viên</p>
            </div>
            <div class="stat-item" data-aos="fade-up" data-aos-delay="400">
                <i class="fas fa-file-alt"></i>
                <h3 data-target="<?php echo ($stats['tongdon'] ?? 0); ?>">0</h3>
                <p>Đơn ứng tuyển</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Jobs -->
<section class="jobs-section">
    <div class="container">
        <div class="section-header">
            <h2>Việc Làm Mới Nhất</h2>
            <a href="<?php echo BASE_URL; ?>timkiem" class="view-all">Xem tất cả <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="jobs-grid">
            <?php if (!empty($tinTuyenDung)): ?>
                <?php foreach ($tinTuyenDung as $tin): ?>
                    <div class="job-card">
                        <div class="job-card-header">
                            <?php if ($tin['logo']): ?>
                                <img src="<?php echo BASE_URL . 'uploads/logo/' . htmlspecialchars($tin['logo']); ?>" alt="<?php echo htmlspecialchars($tin['tencongty']); ?>">
                            <?php else: ?>
                                <div class="company-avatar"><?php echo strtoupper(substr($tin['tencongty'], 0, 1)); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="job-card-body">
                            <h3>
                                <a href="<?php echo BASE_URL; ?>tintuyendung/chitiet/<?php echo $tin['id']; ?>">
                                    <?php echo htmlspecialchars($tin['tieude']); ?>
                                </a>
                            </h3>
                            <p class="company-name"><?php echo htmlspecialchars($tin['tencongty']); ?></p>
                            <div class="job-info">
                                <span><i class="fas fa-money-bill-wave"></i> <?php echo htmlspecialchars($tin['tenmucluong']); ?></span>
                                <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($tin['tentinh']); ?></span>
                            </div>
                            <div class="job-tags">
                                <span class="badge badge-primary"><?php echo htmlspecialchars($tin['tennganh']); ?></span>
                            </div>
                        </div>
                        <div class="job-card-footer">
                            <span class="job-date"><i class="far fa-clock"></i> <?php echo date('d/m/Y', strtotime($tin['ngaydang'])); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Chưa có tin tuyển dụng nào</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2>Ngành Nghề Nổi Bật</h2>
        </div>
        
        <div class="categories-grid">
            <?php if (!empty($nganhNghe)): ?>
                <?php foreach ($nganhNghe as $nganh): ?>
                    <a href="<?php echo BASE_URL; ?>timkiem?nganh=<?php echo $nganh['id']; ?>" class="category-item">
                        <i class="fas fa-code"></i>
                        <h4><?php echo htmlspecialchars($nganh['tennganh']); ?></h4>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Bạn Là Nhà Tuyển Dụng?</h2>
            <p>Đăng tin tuyển dụng và tìm kiếm ứng viên phù hợp ngay hôm nay</p>
            <?php if (isset($_SESSION['nguoidung_id'])): ?>
                <?php if ($_SESSION['vaitro'] == 'ungvien'): ?>
                    <a href="<?php echo BASE_URL; ?>ungvien/yeucautuyendung" class="btn btn-primary btn-lg">Đăng Ký Tuyển Dụng</a>
                <?php elseif ($_SESSION['vaitro'] == 'tuyendung'): ?>
                    <a href="<?php echo BASE_URL; ?>nhatuyendung/dangtin" class="btn btn-primary btn-lg">Đăng Tin Tuyển Dụng</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>dangky" class="btn btn-primary btn-lg">Đăng Ký Ngay</a>
            <?php endif; ?>
        </div>
    </div>
</section>
