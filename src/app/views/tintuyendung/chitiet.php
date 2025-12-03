<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="row">
        <!-- Nội dung chính -->
        <div class="col-md-8">
            <div class="job-detail-card">
                <!-- Header -->
                <div class="job-detail-header">
                    <div class="company-logo">
                        <?php if ($tin['logo']): ?>
                            <img src="<?php echo BASE_URL . 'uploads/logo/' . htmlspecialchars($tin['logo']); ?>" 
                                 alt="<?php echo htmlspecialchars($tin['tencongty']); ?>">
                        <?php else: ?>
                            <div class="company-avatar-large">
                                <?php echo strtoupper(substr($tin['tencongty'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="job-detail-title">
                        <h1><?php echo htmlspecialchars($tin['tieude']); ?></h1>
                        <p class="company-name">
                            <i class="fas fa-building"></i>
                            <?php echo htmlspecialchars($tin['tencongty']); ?>
                        </p>
                        <div class="job-meta">
                            <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($tin['tentinh']); ?></span>
                            <span><i class="fas fa-dollar-sign"></i> <?php echo htmlspecialchars($tin['tenmucluong']); ?></span>
                            <span><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($tin['tennganh']); ?></span>
                            <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($tin['tenloai']); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Thông tin chính -->
                <div class="job-detail-body">
                    <div class="info-section">
                        <h3><i class="fas fa-file-alt"></i> Mô tả công việc</h3>
                        <div class="content">
                            <?php echo nl2br(htmlspecialchars($tin['mota'])); ?>
                        </div>
                    </div>

                    <div class="info-section">
                        <h3><i class="fas fa-check-circle"></i> Yêu cầu ứng viên</h3>
                        <div class="content">
                            <?php echo nl2br(htmlspecialchars($tin['yeucau'])); ?>
                        </div>
                    </div>

                    <?php if ($tin['quyenloi']): ?>
                    <div class="info-section">
                        <h3><i class="fas fa-gift"></i> Quyền lợi</h3>
                        <div class="content">
                            <?php echo nl2br(htmlspecialchars($tin['quyenloi'])); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Thông tin thêm -->
                    <div class="info-section">
                        <h3><i class="fas fa-info-circle"></i> Thông tin thêm</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <strong>Số lượng:</strong>
                                <span><?php echo htmlspecialchars($tin['soluong']); ?> người</span>
                            </div>
                            <?php if (!empty($tin['diachilamviec'])): ?>
                            <div class="info-item">
                                <strong>Địa chỉ làm việc:</strong>
                                <span><?php echo htmlspecialchars($tin['diachilamviec']); ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($tin['gioitinh_yc'])): ?>
                            <div class="info-item">
                                <strong>Giới tính yêu cầu:</strong>
                                <span><?php echo htmlspecialchars($tin['gioitinh_yc']); ?></span>
                            </div>
                            <?php endif; ?>
                            <div class="info-item">
                                <strong>Hạn nộp:</strong>
                                <span><?php echo date('d/m/Y', strtotime($tin['ngayhethan'])); ?></span>
                            </div>
                            <div class="info-item">
                                <strong>Ngày đăng:</strong>
                                <span><?php echo date('d/m/Y', strtotime($tin['ngaydang'])); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tin tuyển dụng liên quan -->
            <?php if (!empty($tinLienQuan)): ?>
            <div class="related-jobs">
                <h3><i class="fas fa-briefcase"></i> Tin tuyển dụng liên quan</h3>
                <div class="jobs-grid">
                    <?php foreach ($tinLienQuan as $related): ?>
                        <?php if ($related['id'] != $tin['id']): ?>
                        <div class="job-card-small">
                            <h4>
                                <a href="<?php echo BASE_URL; ?>tintuyendung/chitiet/<?php echo $related['id']; ?>">
                                    <?php echo htmlspecialchars($related['tieude']); ?>
                                </a>
                            </h4>
                            <p><?php echo htmlspecialchars($related['tencongty']); ?></p>
                            <div class="job-tags">
                                <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($related['tentinh']); ?></span>
                                <span><i class="fas fa-dollar-sign"></i> <?php echo htmlspecialchars($related['tenmucluong']); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Nút ứng tuyển -->
            <?php if (isset($_SESSION['nguoidung_id']) && $_SESSION['vaitro'] == 'ungvien'): ?>
                <?php if ($daUngTuyen): ?>
                    <div class="apply-box">
                        <button class="btn btn-secondary btn-block" disabled>
                            <i class="fas fa-check-circle"></i> Đã ứng tuyển
                        </button>
                        <p style="text-align: center; margin-top: 10px; color: #666;">
                            Bạn đã nộp đơn ứng tuyển vào vị trí này
                        </p>
                    </div>
                <?php else: ?>
                    <div class="apply-box">
                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#applyModal">
                            <i class="fas fa-paper-plane"></i> Ứng tuyển ngay
                        </button>
                    </div>
                <?php endif; ?>
            <?php elseif (!isset($_SESSION['nguoidung_id'])): ?>
                <div class="apply-box">
                    <a href="<?php echo BASE_URL; ?>dangnhap" class="btn btn-primary btn-block">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập để ứng tuyển
                    </a>
                </div>
            <?php endif; ?>

            <!-- Thông tin công ty -->
            <div class="company-info-box">
                <h3><i class="fas fa-building"></i> Thông tin công ty</h3>
                <div class="company-details">
                    <?php if ($tin['logo']): ?>
                        <img src="<?php echo BASE_URL . 'uploads/logo/' . $tin['logo']; ?>" 
                             alt="<?php echo htmlspecialchars($tin['tencongty']); ?>"
                             style="width: 100%; max-width: 150px; margin-bottom: 15px;">
                    <?php endif; ?>
                    <h4><?php echo htmlspecialchars($tin['tencongty']); ?></h4>
                    <?php if ($tin['diachi_congty']): ?>
                        <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($tin['diachi_congty']); ?></p>
                    <?php endif; ?>
                    <?php if ($tin['website']): ?>
                        <p><i class="fas fa-globe"></i> <a href="<?php echo htmlspecialchars($tin['website']); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($tin['website']); ?></a></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Thống kê -->
            <div class="job-stats-box">
                <h3><i class="fas fa-chart-bar"></i> Thống kê</h3>
                <div class="stats">
                    <div class="stat-item">
                        <i class="fas fa-eye"></i>
                        <span><?php echo number_format($tin['luotxem']); ?> lượt xem</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <span><?php echo number_format($tin['sodon'] ?? 0); ?> ứng viên</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Còn <?php echo max(0, floor((strtotime($tin['ngayhethan']) - time()) / 86400)); ?> ngày</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ứng tuyển -->
<?php if (isset($_SESSION['nguoidung_id']) && $_SESSION['vaitro'] == 'ungvien' && !$daUngTuyen): ?>
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-paper-plane"></i> Ứng tuyển: <?php echo htmlspecialchars($tin['tieude']); ?></h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>tintuyendung/ungtuyen/<?php echo $tin['id']; ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cv">CV đính kèm <span class="required">*</span></label>
                        <input type="file" 
                               class="form-control" 
                               id="cv" 
                               name="cv" 
                               accept=".pdf,.doc,.docx"
                               required>
                        <small class="form-text text-muted">Chỉ chấp nhận file PDF, DOC, DOCX (tối đa 5MB)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="thuungtuyen">Thư ứng tuyển <span class="required">*</span></label>
                        <textarea class="form-control" 
                                  id="thuungtuyen" 
                                  name="thuungtuyen" 
                                  rows="6"
                                  placeholder="Giới thiệu bản thân và lý do ứng tuyển vào vị trí này..."
                                  required></textarea>
                        <small class="char-counter">0 ký tự</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Nộp đơn ứng tuyển
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Modal bootstrap simple
document.addEventListener('DOMContentLoaded', function() {
    const modalTriggers = document.querySelectorAll('[data-toggle="modal"]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const modal = document.querySelector(targetId);
            if (modal) {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }
        });
    });
    
    const closeButtons = document.querySelectorAll('.modal .close, [data-dismiss="modal"]');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        });
    });
    
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
            document.body.style.overflow = '';
        }
    });
});
</script>
<?php endif; ?>

<style>
.job-detail-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    overflow: hidden;
}

.job-detail-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    padding: 40px;
    display: flex;
    gap: 30px;
}

.company-logo img,
.company-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    background: white;
    object-fit: contain;
    padding: 10px;
}

.company-avatar-large {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: bold;
    color: var(--primary-color);
}

.job-detail-title h1 {
    color: white;
    margin: 0 0 10px 0;
    font-size: 2rem;
}

.job-detail-title .company-name {
    font-size: 1.2rem;
    margin-bottom: 15px;
    opacity: 0.9;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
}

.job-meta span {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.2);
    padding: 8px 15px;
    border-radius: 20px;
}

.job-detail-body {
    padding: 40px;
}

.info-section {
    margin-bottom: 35px;
    padding-bottom: 35px;
    border-bottom: 2px solid #f0f0f0;
}

.info-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.info-section h3 {
    color: var(--primary-color);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.4rem;
}

.info-section .content {
    line-height: 1.8;
    color: #666;
    white-space: pre-wrap;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.apply-box,
.company-info-box,
.job-stats-box {
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.apply-box .btn {
    font-size: 1.1rem;
    padding: 15px;
}

.company-info-box h3,
.job-stats-box h3 {
    color: var(--primary-color);
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.company-details h4 {
    margin: 15px 0;
    color: var(--primary-color);
}

.company-details p {
    margin: 8px 0;
    color: #666;
}

.stats {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-item i {
    color: var(--primary-color);
    font-size: 1.2rem;
}

.related-jobs {
    margin-top: 30px;
}

.related-jobs h3 {
    color: var(--primary-color);
    margin-bottom: 20px;
}

.jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
}

.job-card-small {
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.job-card-small:hover {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}

.job-card-small h4 {
    margin: 0 0 8px 0;
    font-size: 1rem;
}

.job-card-small h4 a {
    color: var(--primary-color);
    text-decoration: none;
}

.job-card-small p {
    color: #666;
    margin: 0 0 10px 0;
    font-size: 0.9rem;
}

.job-tags {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    font-size: 0.85rem;
    color: #999;
}

.modal {
    display: none;
    position: fixed;
    z-index: 10000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    overflow: auto;
}

.modal-dialog {
    margin: 50px auto;
    max-width: 600px;
}

.modal-content {
    background: white;
    border-radius: 12px;
    overflow: hidden;
}

.modal-header {
    background: var(--primary-color);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.3rem;
}

.modal-header .close {
    background: none;
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
}

.modal-body {
    padding: 30px;
}

.modal-footer {
    padding: 20px 30px;
    background: #f8f9fa;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

@media (max-width: 768px) {
    .job-detail-header {
        flex-direction: column;
        text-align: center;
    }
    
    .job-meta {
        flex-direction: column;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
