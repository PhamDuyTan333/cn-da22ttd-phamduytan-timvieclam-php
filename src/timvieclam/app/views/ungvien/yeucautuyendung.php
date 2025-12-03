<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="row">
        <!-- Form yêu cầu -->
        <div class="col-md-8">
            <div class="form-card">
                <div class="form-header">
                    <h1><i class="fas fa-building"></i> Trở thành nhà tuyển dụng</h1>
                    <p>Điền thông tin công ty để đăng ký trở thành nhà tuyển dụng</p>
                </div>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php 
                        echo htmlspecialchars($_SESSION['error']); 
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo BASE_URL; ?>ungvien/guiyeucau" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <h3><i class="fas fa-info-circle"></i> Thông tin công ty</h3>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tencongty">Tên công ty <span class="required">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="tencongty" 
                                   name="tencongty" 
                                   placeholder="Công ty TNHH ABC"
                                   required>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="masothue">Mã số thuế</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="masothue" 
                                   name="masothue" 
                                   placeholder="0123456789">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="emailcongty">Email công ty <span class="required">*</span></label>
                            <input type="email" 
                                   class="form-control" 
                                   id="emailcongty" 
                                   name="emailcongty" 
                                   placeholder="contact@company.com"
                                   required>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="website">Website</label>
                            <input type="url" 
                                   class="form-control" 
                                   id="website" 
                                   name="website" 
                                   placeholder="https://company.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="diachi_congty">Địa chỉ công ty</label>
                        <input type="text" 
                               class="form-control" 
                               id="diachi_congty" 
                               name="diachi_congty" 
                               placeholder="123 Đường ABC, Quận XYZ, TP. HCM">
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="quymo">Quy mô</label>
                            <select class="form-control" id="quymo" name="quymo">
                                <option value="">Chọn quy mô</option>
                                <option value="1-50">1-50 nhân viên</option>
                                <option value="51-200">51-200 nhân viên</option>
                                <option value="201-500">201-500 nhân viên</option>
                                <option value="501-1000">501-1000 nhân viên</option>
                                <option value="1000+">Trên 1000 nhân viên</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-md-8">
                            <label for="linhvuc">Lĩnh vực</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="linhvuc" 
                                   name="linhvuc" 
                                   placeholder="Công nghệ thông tin, Kinh doanh, Tài chính,...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="logo">Logo công ty</label>
                        <input type="file" 
                               class="form-control-file" 
                               id="logo" 
                               name="logo" 
                               accept="image/*">
                        <small class="form-text text-muted">Chỉ chấp nhận file ảnh JPG, PNG (tối đa 5MB)</small>
                    </div>

                    <div class="form-group">
                        <label for="mota">Mô tả công ty</label>
                        <textarea class="form-control" 
                                  id="mota" 
                                  name="mota" 
                                  rows="5"
                                  placeholder="Giới thiệu về công ty, lĩnh vực hoạt động, sản phẩm/dịch vụ..."></textarea>
                    </div>

                    <h3 style="margin-top: 30px;"><i class="fas fa-comment-alt"></i> Lý do yêu cầu</h3>
                    
                    <div class="form-group">
                        <label for="lydoyeucau">Lý do muốn trở thành nhà tuyển dụng <span class="required">*</span></label>
                        <textarea class="form-control" 
                                  id="lydoyeucau" 
                                  name="lydoyeucau" 
                                  rows="4"
                                  placeholder="Vui lòng nêu rõ lý do và mục đích sử dụng hệ thống tuyển dụng..."
                                  required></textarea>
                        <small class="char-counter">0 ký tự</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> Gửi yêu cầu
                        </button>
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-outline btn-lg">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar hướng dẫn -->
        <div class="col-md-4">
            <div class="info-box">
                <h3><i class="fas fa-info-circle"></i> Hướng dẫn</h3>
                <div class="info-content">
                    <h4>Quy trình phê duyệt</h4>
                    <ol>
                        <li>Điền đầy đủ thông tin công ty</li>
                        <li>Gửi yêu cầu trở thành nhà tuyển dụng</li>
                        <li>Chờ quản trị viên xem xét (1-2 ngày làm việc)</li>
                        <li>Nhận thông báo qua email khi được phê duyệt</li>
                        <li>Bắt đầu đăng tin tuyển dụng</li>
                    </ol>
                    
                    <h4>Lưu ý</h4>
                    <ul>
                        <li>Thông tin công ty phải chính xác và đầy đủ</li>
                        <li>Email công ty phải là email chính thức</li>
                        <li>Logo công ty nên rõ ràng, chuyên nghiệp</li>
                        <li>Mô tả công ty chi tiết giúp thu hút ứng viên</li>
                    </ul>
                </div>
            </div>

            <div class="benefits-box">
                <h3><i class="fas fa-star"></i> Quyền lợi</h3>
                <div class="benefits-list">
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Đăng tin tuyển dụng không giới hạn</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Quản lý ứng viên dễ dàng</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Xem hồ sơ ứng viên chi tiết</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Thống kê hiệu quả tuyển dụng</span>
                    </div>
                    <div class="benefit-item">
                        <i class="fas fa-check-circle"></i>
                        <span>Hỗ trợ 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Đếm ký tự
document.addEventListener('DOMContentLoaded', function() {
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const counter = textarea.nextElementSibling;
        if (counter && counter.classList.contains('char-counter')) {
            textarea.addEventListener('input', function() {
                counter.textContent = this.value.length + ' ký tự';
            });
        }
    });
});
</script>

<style>
.form-card {
    background: white;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    margin-bottom: 30px;
}

.form-header {
    text-align: center;
    margin-bottom: 35px;
    padding-bottom: 25px;
    border-bottom: 2px solid #f0f0f0;
}

.form-header h1 {
    color: var(--primary-color);
    margin-bottom: 10px;
}

.form-header p {
    color: #666;
    font-size: 1.1rem;
}

.form-card h3 {
    color: var(--primary-color);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 0;
}

.form-row .form-group {
    flex: 1;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: block;
}

.required {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 3px rgba(87, 13, 248, 0.1);
}

.form-control-file {
    width: 100%;
    padding: 10px;
    border: 2px dashed #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.form-control-file:hover {
    border-color: var(--primary-color);
    background: #f8f9fa;
}

.form-text {
    display: block;
    margin-top: 5px;
    color: #999;
    font-size: 0.9rem;
}

.char-counter {
    display: block;
    text-align: right;
    margin-top: 5px;
    color: #999;
    font-size: 0.85rem;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 35px;
    padding-top: 25px;
    border-top: 2px solid #f0f0f0;
}

.info-box,
.benefits-box {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.info-box h3,
.benefits-box h3 {
    color: var(--primary-color);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-content h4 {
    color: #333;
    margin: 20px 0 10px 0;
    font-size: 1rem;
}

.info-content h4:first-child {
    margin-top: 0;
}

.info-content ol,
.info-content ul {
    padding-left: 20px;
    color: #666;
    line-height: 1.8;
}

.info-content ol li,
.info-content ul li {
    margin-bottom: 8px;
}

.benefits-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
}

.benefit-item i {
    color: #28a745;
    font-size: 1.2rem;
}

.benefit-item span {
    color: #666;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-danger {
    background: #f8d7da;
    color: #842029;
    border: 1px solid #f5c2c7;
}

@media (max-width: 768px) {
    .form-card {
        padding: 25px 20px;
    }
    
    .form-row {
        flex-direction: column;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
