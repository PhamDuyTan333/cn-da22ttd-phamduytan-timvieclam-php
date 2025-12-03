<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <!-- Header tìm kiếm -->
    <div class="search-header">
        <h1><i class="fas fa-search"></i> Tìm việc làm</h1>
        <p>Tìm thấy <strong><?php echo number_format($tongKetQua); ?></strong> công việc phù hợp</p>
    </div>
    
    <!-- Filter Toggle Button for Mobile -->
    <button class="filter-toggle" aria-label="Toggle Filter">
        <i class="fas fa-filter"></i>
    </button>

    <div class="row">
        <!-- Sidebar bộ lọc -->
        <div class="col-md-3">
            <div class="filter-sidebar">
                <h3><i class="fas fa-filter"></i> Bộ lọc tìm kiếm</h3>
                
                <form method="GET" action="<?php echo BASE_URL; ?>timkiem" id="filterForm">
                    <!-- Từ khóa -->
                    <div class="filter-group">
                        <label><i class="fas fa-search"></i> Từ khóa</label>
                        <input type="text" 
                               class="form-control" 
                               name="tukhoa" 
                               placeholder="Vị trí, công ty..."
                               value="<?php echo htmlspecialchars($filter['tukhoa']); ?>">
                    </div>

                    <!-- Ngành nghề -->
                    <div class="filter-group">
                        <label><i class="fas fa-briefcase"></i> Ngành nghề</label>
                        <select class="form-control" name="nganhnghe">
                            <option value="">Tất cả ngành nghề</option>
                            <?php foreach ($danhMuc['nganhnghe'] as $nn): ?>
                                <option value="<?php echo $nn['id']; ?>" 
                                        <?php echo $filter['nganhnghe'] == $nn['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($nn['tennganh']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tỉnh/Thành phố -->
                    <div class="filter-group">
                        <label><i class="fas fa-map-marker-alt"></i> Tỉnh/Thành phố</label>
                        <select class="form-control" name="tinhthanh">
                            <option value="">Tất cả địa điểm</option>
                            <?php foreach ($danhMuc['tinhthanh'] as $tt): ?>
                                <option value="<?php echo $tt['id']; ?>"
                                        <?php echo $filter['tinhthanh'] == $tt['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tt['tentinh']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Mức lương -->
                    <div class="filter-group">
                        <label><i class="fas fa-dollar-sign"></i> Mức lương</label>
                        <select class="form-control" name="mucluong">
                            <option value="">Tất cả mức lương</option>
                            <?php foreach ($danhMuc['mucluong'] as $ml): ?>
                                <option value="<?php echo $ml['id']; ?>"
                                        <?php echo $filter['mucluong'] == $ml['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ml['tenmucluong']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Loại công việc -->
                    <div class="filter-group">
                        <label><i class="fas fa-briefcase"></i> Loại công việc</label>
                        <select class="form-control" name="loaicongviec">
                            <option value="">Tất cả loại</option>
                            <?php foreach ($danhMuc['loaicongviec'] as $lcv): ?>
                                <option value="<?php echo $lcv['id']; ?>"
                                        <?php echo $filter['loaicongviec'] == $lcv['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($lcv['tenloai']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Nút hành động -->
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                    <a href="<?php echo BASE_URL; ?>timkiem" class="btn btn-outline btn-block">
                        <i class="fas fa-redo"></i> Xóa bộ lọc
                    </a>
                </form>
            </div>
        </div>

        <!-- Kết quả tìm kiếm -->
        <div class="col-md-9">
            <?php if (empty($ketQua)): ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <h3>Không tìm thấy kết quả phù hợp</h3>
                    <p>Vui lòng thử lại với từ khóa hoặc bộ lọc khác</p>
                    <a href="<?php echo BASE_URL; ?>timkiem" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Xóa bộ lọc
                    </a>
                </div>
            <?php else: ?>
                <div class="jobs-list">
                    <?php foreach ($ketQua as $tin): ?>
                    <div class="job-item">
                        <div class="job-item-logo">
                            <?php if ($tin['logo']): ?>
                                <img src="<?php echo BASE_URL . 'uploads/logo/' . htmlspecialchars($tin['logo']); ?>" 
                                     alt="<?php echo htmlspecialchars($tin['tencongty']); ?>">
                            <?php else: ?>
                                <div class="company-avatar">
                                    <?php echo strtoupper(substr($tin['tencongty'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="job-item-content">
                            <h3>
                                <a href="<?php echo BASE_URL; ?>tintuyendung/chitiet/<?php echo $tin['id']; ?>">
                                    <?php echo htmlspecialchars($tin['tieude']); ?>
                                </a>
                            </h3>
                            <p class="company-name">
                                <i class="fas fa-building"></i>
                                <?php echo htmlspecialchars($tin['tencongty']); ?>
                            </p>
                            <div class="job-item-meta">
                                <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($tin['tentinh']); ?></span>
                                <span><i class="fas fa-dollar-sign"></i> <?php echo htmlspecialchars($tin['tenmucluong']); ?></span>
                                <span><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($tin['tennganh']); ?></span>
                                <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($tin['tenloai']); ?></span>
                            </div>
                            <div class="job-item-footer">
                                <span class="job-date">
                                    <i class="fas fa-calendar"></i>
                                    Còn <?php echo max(0, floor((strtotime($tin['ngayhethan']) - time()) / 86400)); ?> ngày
                                </span>
                                <a href="<?php echo BASE_URL; ?>tintuyendung/chitiet/<?php echo $tin['id']; ?>" 
                                   class="btn btn-sm btn-primary">
                                    Xem chi tiết <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Phân trang -->
                <?php if (isset($pagination['total_pages']) && $pagination['total_pages'] > 1): ?>
                <div class="pagination">
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="<?php echo BASE_URL; ?>timkiem?<?php echo http_build_query(array_merge($filter, ['page' => $pagination['current_page'] - 1])); ?>" 
                           class="btn btn-outline">
                            <i class="fas fa-chevron-left"></i> Trước
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                        <?php if ($i == $pagination['current_page']): ?>
                            <span class="btn btn-primary"><?php echo $i; ?></span>
                        <?php elseif ($i == 1 || $i == $pagination['total_pages'] || abs($i - $pagination['current_page']) <= 2): ?>
                            <a href="<?php echo BASE_URL; ?>timkiem?<?php echo http_build_query(array_merge($filter, ['page' => $i])); ?>" 
                               class="btn btn-outline">
                                <?php echo $i; ?>
                            </a>
                        <?php elseif (abs($i - $pagination['current_page']) == 3): ?>
                            <span class="btn btn-outline disabled">...</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <a href="<?php echo BASE_URL; ?>timkiem?<?php echo http_build_query(array_merge($filter, ['page' => $pagination['current_page'] + 1])); ?>" 
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
.search-header {
    text-align: center;
    padding: 30px 0;
    margin-bottom: 30px;
    border-bottom: 2px solid #f0f0f0;
}

.search-header h1 {
    color: var(--primary-color);
    margin-bottom: 10px;
}

.filter-sidebar {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    position: sticky;
    top: 100px;
}

.filter-sidebar h3 {
    color: var(--primary-color);
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
    font-size: 1.2rem;
}

.filter-group {
    margin-bottom: 20px;
}

.filter-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    margin-bottom: 8px;
    color: #333;
}

.filter-group i {
    color: var(--primary-color);
    width: 18px;
}

.jobs-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.job-item {
    display: flex;
    gap: 20px;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.job-item:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}

.job-item-logo {
    flex-shrink: 0;
}

.job-item-logo img,
.company-avatar {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #e0e0e0;
}

.company-avatar {
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
}

.job-item-content {
    flex: 1;
}

.job-item-content h3 {
    margin: 0 0 8px 0;
    font-size: 1.3rem;
}

.job-item-content h3 a {
    color: var(--primary-color);
    text-decoration: none;
    transition: color 0.3s;
}

.job-item-content h3 a:hover {
    color: var(--secondary-color);
}

.company-name {
    color: #666;
    margin-bottom: 12px;
    font-size: 1rem;
}

.company-name i {
    margin-right: 5px;
}

.job-item-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
    color: #666;
    font-size: 0.9rem;
}

.job-item-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.job-item-meta i {
    color: var(--primary-color);
}

.job-item-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

.job-date {
    color: #999;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .job-item {
        flex-direction: column;
    }
    
    .job-item-logo img,
    .company-avatar {
        width: 60px;
        height: 60px;
    }
    
    .job-item-meta {
        flex-direction: column;
        gap: 8px;
    }
    
    .job-item-footer {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
