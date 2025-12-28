<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="page-header">
        <h1><i class="fas fa-file-alt"></i> Đơn ứng tuyển của tôi</h1>
        <p>Quản lý và theo dõi trạng thái các đơn ứng tuyển</p>
    </div>

    <!-- Thống kê -->
    <div class="stats-grid">
        <?php
        $tongDon = count($danhSachDon);
        $donMoi = 0;
        $dangXem = 0;
        $phongVan = 0;
        $nhanViec = 0;
        $tuChoi = 0;
        
        foreach ($danhSachDon as $don) {
            switch ($don['trangthai']) {
                case 'moi':
                    $donMoi++;
                    break;
                case 'dangxem':
                    $dangXem++;
                    break;
                case 'phongvan':
                    $phongVan++;
                    break;
                case 'nhanviec':
                    $nhanViec++;
                    break;
                case 'tuchoi':
                    $tuChoi++;
                    break;
            }
        }
        ?>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $tongDon; ?></h3>
                <p>Tổng đơn</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $donMoi; ?></h3>
                <p>Đơn mới</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="fas fa-eye"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $dangXem; ?></h3>
                <p>Đang xem</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $nhanViec; ?></h3>
                <p>Nhận việc</p>
            </div>
        </div>
    </div>

    <!-- Danh sách đơn -->
    <div class="applications-section">
        <?php if (empty($danhSachDon)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>Chưa có đơn ứng tuyển nào</h3>
                <p>Hãy tìm kiếm công việc phù hợp và bắt đầu ứng tuyển nhé!</p>
                <a href="<?php echo BASE_URL; ?>timkiem" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tìm việc ngay
                </a>
            </div>
        <?php else: ?>
            <div class="applications-list">
                <?php foreach ($danhSachDon as $don): ?>
                    <div class="application-card">
                        <div class="application-header">
                            <div class="company-logo">
                                <?php if ($don['logo']): ?>
                                    <img src="<?php echo BASE_URL . 'uploads/logo/' . htmlspecialchars($don['logo']); ?>" 
                                         alt="<?php echo htmlspecialchars($don['tencongty']); ?>">
                                <?php else: ?>
                                    <div class="company-avatar">
                                        <?php echo strtoupper(substr($don['tencongty'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="application-info">
                                <h3>
                                    <a href="<?php echo BASE_URL; ?>tintuyendung/chitiet/<?php echo $don['tintuyendung_id']; ?>">
                                        <?php echo htmlspecialchars($don['tieude']); ?>
                                    </a>
                                </h3>
                                <p class="company-name"><?php echo htmlspecialchars($don['tencongty']); ?></p>
                                <div class="job-meta">
                                    <span><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($don['tennganh']); ?></span>
                                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($don['tentinh']); ?></span>
                                    <span><i class="fas fa-dollar-sign"></i> <?php echo htmlspecialchars($don['tenmucluong']); ?></span>
                                </div>
                            </div>
                            <div class="application-status">
                                <?php
                                $statusClass = '';
                                $statusText = '';
                                $statusIcon = '';
                                
                                switch ($don['trangthai']) {
                                    case 'moi':
                                        $statusClass = 'status-new';
                                        $statusText = 'Mới';
                                        $statusIcon = 'fa-clock';
                                        break;
                                    case 'dangxem':
                                        $statusClass = 'status-processing';
                                        $statusText = 'Đang xem';
                                        $statusIcon = 'fa-eye';
                                        break;
                                    case 'phongvan':
                                        $statusClass = 'status-interview';
                                        $statusText = 'Phỏng vấn';
                                        $statusIcon = 'fa-users';
                                        break;
                                    case 'nhanviec':
                                        $statusClass = 'status-accepted';
                                        $statusText = 'Nhận việc';
                                        $statusIcon = 'fa-check-circle';
                                        break;
                                    case 'tuchoi':
                                        $statusClass = 'status-rejected';
                                        $statusText = 'Từ chối';
                                        $statusIcon = 'fa-times-circle';
                                        break;
                                    default:
                                        $statusClass = 'status-new';
                                        $statusText = 'Mới';
                                        $statusIcon = 'fa-clock';
                                        break;
                                }
                                ?>
                                <span class="status-badge <?php echo $statusClass; ?>">
                                    <i class="fas <?php echo $statusIcon; ?>"></i>
                                    <?php echo $statusText; ?>
                                </span>
                                <small class="apply-date">
                                    Nộp: <?php echo date('d/m/Y H:i', strtotime($don['ngaynop'])); ?>
                                </small>
                            </div>
                        </div>
                        
                        <div class="application-body">
                            <div class="application-letter">
                                <h4><i class="fas fa-envelope"></i> Thư ứng tuyển</h4>
                                <p><?php echo nl2br(htmlspecialchars($don['thuungtuyen'])); ?></p>
                            </div>
                            <div class="application-cv">
                                <?php if ($don['cv_file']): ?>
                                    <a href="<?php echo BASE_URL . 'uploads/cv/' . htmlspecialchars($don['cv_file']); ?>" 
                                       class="btn btn-outline btn-sm"
                                       target="_blank" rel="noopener noreferrer">
                                        <i class="fas fa-file-pdf"></i> Xem CV
                                    </a>
                                    <?php if ($don['trangthai'] === 'moi'): ?>
                                        <button type="button" class="btn btn-primary btn-sm" 
                                                onclick="openChangeCVModal(<?php echo $don['id']; ?>, '<?php echo htmlspecialchars($don['cv_file'], ENT_QUOTES); ?>')">
                                            <i class="fas fa-sync-alt"></i> Thay đổi CV
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($don['ghichu']): ?>
                        <div class="application-note">
                            <i class="fas fa-sticky-note"></i>
                            <strong>Ghi chú từ nhà tuyển dụng:</strong>
                            <p><?php echo nl2br(htmlspecialchars($don['ghichu'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-header {
    text-align: center;
    margin-bottom: 40px;
}

.page-header h1 {
    color: var(--primary-color);
    margin-bottom: 10px;
}

.page-header p {
    color: #666;
    font-size: 1.1rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    transition: all 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 25px rgba(0,0,0,0.12);
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
}

.stat-info h3 {
    margin: 0;
    font-size: 2rem;
    color: var(--primary-color);
}

.stat-info p {
    margin: 5px 0 0 0;
    color: #666;
}

.applications-section {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state i {
    font-size: 5rem;
    color: #ddd;
    margin-bottom: 20px;
}

.empty-state h3 {
    color: #666;
    margin-bottom: 10px;
}

.empty-state p {
    color: #999;
    margin-bottom: 25px;
}

.applications-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.application-card {
    border: 2px solid #f0f0f0;
    border-radius: 12px;
    padding: 25px;
    transition: all 0.3s;
}

.application-card:hover {
    border-color: var(--primary-color);
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.application-header {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.company-logo img,
.company-avatar {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    object-fit: contain;
    background: #f8f9fa;
    padding: 10px;
}

.company-avatar {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary-color);
}

.application-info {
    flex: 1;
}

.application-info h3 {
    margin: 0 0 8px 0;
}

.application-info h3 a {
    color: var(--primary-color);
    text-decoration: none;
}

.application-info h3 a:hover {
    text-decoration: underline;
}

.company-name {
    color: #666;
    margin-bottom: 10px;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    font-size: 0.9rem;
    color: #999;
}

.application-status {
    text-align: right;
}

.status-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 500;
    margin-bottom: 8px;
}

.status-new {
    background: #fff3cd;
    color: #856404;
}

.status-processing {
    background: #d1ecf1;
    color: #0c5460;
}

.status-interview {
    background: #cfe2ff;
    color: #084298;
}

.status-accepted {
    background: #d1e7dd;
    color: #0f5132;
}

.status-rejected {
    background: #f8d7da;
    color: #842029;
}

.apply-date {
    display: block;
    color: #999;
    font-size: 0.85rem;
}

.application-body {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 20px;
    align-items: start;
}

.application-letter h4 {
    color: var(--primary-color);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.application-letter p {
    color: #666;
    line-height: 1.6;
    max-height: 100px;
    overflow: hidden;
}

.application-note {
    margin-top: 20px;
    padding: 15px;
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    border-radius: 8px;
}

.application-note strong {
    display: block;
    margin-bottom: 8px;
    color: #856404;
}

.application-note p {
    color: #666;
    margin: 0;
}

@media (max-width: 768px) {
    .application-header {
        flex-direction: column;
        text-align: center;
    }
    
    .application-status {
        text-align: center;
    }
    
    .application-body {
        grid-template-columns: 1fr;
    }
    
    .job-meta {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

<!-- Modal Thay đổi CV -->
<div id="changeCVModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3><i class="fas fa-sync-alt"></i> Thay đổi CV</h3>
            <button type="button" class="close-modal" onclick="closeChangeCVModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="changeCVForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="don_id" id="change_don_id">
            
            <div class="modal-body">
                <div class="form-group">
                    <label>CV hiện tại:</label>
                    <div id="current_cv_name" style="padding: 10px; background: #f8f9fa; border-radius: 5px; margin-bottom: 15px;">
                        <i class="fas fa-file-pdf"></i> <span id="cv_filename"></span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="new_cv">Chọn CV mới: <span style="color: red;">*</span></label>
                    <input type="file" class="form-control" id="new_cv" name="new_cv" accept=".pdf,.doc,.docx" required>
                    <small class="form-text">Chấp nhận file PDF, DOC, DOCX (Tối đa 5MB)</small>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    CV cũ sẽ được thay thế bằng CV mới. Hành động này không thể hoàn tác.
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeChangeCVModal()">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check"></i> Xác nhận thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openChangeCVModal(donId, cvFile) {
    document.getElementById('change_don_id').value = donId;
    document.getElementById('cv_filename').textContent = cvFile;
    document.getElementById('changeCVModal').style.display = 'flex';
}

function closeChangeCVModal() {
    document.getElementById('changeCVModal').style.display = 'none';
    document.getElementById('changeCVForm').reset();
}

// Đóng modal khi click bên ngoài
document.getElementById('changeCVModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeChangeCVModal();
    }
});

// Xử lý form submit
document.getElementById('changeCVForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const fileInput = document.getElementById('new_cv');
    const file = fileInput.files[0];
    
    // Kiểm tra file
    if (!file) {
        alert('Vui lòng chọn file CV');
        return;
    }
    
    // Kiểm tra kích thước (5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('File quá lớn. Vui lòng chọn file nhỏ hơn 5MB');
        return;
    }
    
    // Kiểm tra định dạng
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!allowedTypes.includes(file.type)) {
        alert('Vui lòng chọn file PDF, DOC hoặc DOCX');
        return;
    }
    
    // Submit form
    fetch('<?php echo BASE_URL; ?>ungvien/thaydoicv', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Thay đổi CV thành công!');
            location.reload();
        } else {
            alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra. Vui lòng thử lại.');
    });
});
</script>

<style>
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 600px;
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    color: var(--primary-color);
}

.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #666;
}

.close-modal:hover {
    color: #000;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.alert-info {
    background-color: #d1ecf1;
    border: 1px solid #bee5eb;
    color: #0c5460;
    padding: 12px;
    border-radius: 5px;
    margin-top: 15px;
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
