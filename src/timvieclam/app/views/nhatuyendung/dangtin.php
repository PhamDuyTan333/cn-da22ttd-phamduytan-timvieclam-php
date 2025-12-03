<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> Đăng tin tuyển dụng</h1>
        <p>Tạo tin tuyển dụng mới để tìm ứng viên phù hợp</p>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <?php 
            echo htmlspecialchars($_SESSION['error']); 
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <?php 
            echo htmlspecialchars($_SESSION['success']); 
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>nhatuyendung/xulyDangtin" id="formDangtin">
                        <?php echo csrf_field(); ?>
                        <!-- Tiêu đề -->
                        <div class="form-group">
                            <label for="tieude">Tiêu đề tin tuyển dụng <span class="required">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="tieude" 
                                   name="tieude" 
                                   placeholder="VD: Tuyển Lập Trình Viên PHP"
                                   required
                                   maxlength="200">
                            <small class="char-counter">0/200 ký tự</small>
                        </div>

                        <!-- Ngành nghề -->
                        <div class="form-group">
                            <label for="nganhnghe_id">Ngành nghề <span class="required">*</span></label>
                            <select class="form-control" id="nganhnghe_id" name="nganhnghe_id" required>
                                <option value="">-- Chọn ngành nghề --</option>
                                <?php foreach ($danhMuc['nganhnghe'] as $nn): ?>
                                    <option value="<?php echo $nn['id']; ?>">
                                        <?php echo htmlspecialchars($nn['tennganh']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <!-- Mức lương -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mucluong_id">Mức lương <span class="required">*</span></label>
                                    <select class="form-control" id="mucluong_id" name="mucluong_id" required>
                                        <option value="">-- Chọn mức lương --</option>
                                        <?php foreach ($danhMuc['mucluong'] as $ml): ?>
                                            <option value="<?php echo $ml['id']; ?>">
                                                <?php echo htmlspecialchars($ml['tenmucluong']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Loại công việc -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loaicongviec_id">Loại công việc <span class="required">*</span></label>
                                    <select class="form-control" id="loaicongviec_id" name="loaicongviec_id" required>
                                        <option value="">-- Chọn loại công việc --</option>
                                        <?php foreach ($danhMuc['loaicongviec'] as $lcv): ?>
                                            <option value="<?php echo $lcv['id']; ?>">
                                                <?php echo htmlspecialchars($lcv['tenloai']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Tỉnh/Thành phố -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tinhthanh_id">Địa điểm làm việc <span class="required">*</span></label>
                                    <select class="form-control" id="tinhthanh_id" name="tinhthanh_id" required>
                                        <option value="">-- Chọn tỉnh/thành phố --</option>
                                        <?php foreach ($danhMuc['tinhthanh'] as $tt): ?>
                                            <option value="<?php echo $tt['id']; ?>">
                                                <?php echo htmlspecialchars($tt['tentinh']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Số lượng tuyển -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="soluong">Số lượng tuyển <span class="required">*</span></label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="soluong" 
                                           name="soluong" 
                                           placeholder="VD: 2"
                                           min="1"
                                           max="999"
                                           required>
                                </div>
                            </div>
                        </div>

                        <!-- Địa chỉ cụ thể -->
                        <div class="form-group">
                            <label for="diachilamviec">Địa chỉ làm việc cụ thể</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="diachilamviec" 
                                   name="diachilamviec" 
                                   placeholder="VD: Tầng 5, Tòa nhà ABC, 123 Đường XYZ"
                                   maxlength="255">
                        </div>

                        <div class="row">
                            <!-- Kinh nghiệm -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kinhnghiem">Yêu cầu kinh nghiệm</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="kinhnghiem" 
                                           name="kinhnghiem" 
                                           placeholder="VD: 2 năm, Không yêu cầu"
                                           maxlength="100">
                                </div>
                            </div>

                            <!-- Trình độ -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="trinhdo">Yêu cầu trình độ</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="trinhdo" 
                                           name="trinhdo" 
                                           placeholder="VD: Đại học, Cao đẳng"
                                           maxlength="100">
                                </div>
                            </div>
                        </div>

                        <!-- Mô tả công việc -->
                        <div class="form-group">
                            <label for="mota">Mô tả công việc <span class="required">*</span></label>
                            <textarea class="form-control" 
                                      id="mota" 
                                      name="mota" 
                                      rows="6"
                                      placeholder="Mô tả chi tiết về công việc, nhiệm vụ..."
                                      required></textarea>
                            <small class="char-counter">0 ký tự</small>
                        </div>

                        <!-- Yêu cầu -->
                        <div class="form-group">
                            <label for="yeucau">Yêu cầu ứng viên <span class="required">*</span></label>
                            <textarea class="form-control" 
                                      id="yeucau" 
                                      name="yeucau" 
                                      rows="6"
                                      placeholder="Các yêu cầu về kỹ năng, kiến thức, kinh nghiệm..."
                                      required></textarea>
                            <small class="char-counter">0 ký tự</small>
                        </div>

                        <!-- Quyền lợi -->
                        <div class="form-group">
                            <label for="quyenloi">Quyền lợi</label>
                            <textarea class="form-control" 
                                      id="quyenloi" 
                                      name="quyenloi" 
                                      rows="4"
                                      placeholder="Các quyền lợi, phúc lợi cho ứng viên..."></textarea>
                            <small class="char-counter">0 ký tự</small>
                        </div>

                        <!-- Hạn nộp hồ sơ -->
                        <div class="form-group">
                            <label for="hannop">Hạn nộp hồ sơ <span class="required">*</span></label>
                            <input type="date" 
                                   class="form-control" 
                                   id="hannop" 
                                   name="hannop"
                                   min="<?php echo date('Y-m-d'); ?>"
                                   required>
                        </div>

                        <!-- Nút hành động -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Đăng tin tuyển dụng
                            </button>
                            <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachtin" class="btn btn-outline">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar hướng dẫn -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Hướng dẫn</h3>
                </div>
                <div class="card-body">
                    <div class="tips">
                        <div class="tip-item">
                            <i class="fas fa-lightbulb"></i>
                            <p><strong>Tiêu đề hấp dẫn:</strong> Viết tiêu đề rõ ràng, ngắn gọn về vị trí tuyển dụng</p>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-lightbulb"></i>
                            <p><strong>Mô tả chi tiết:</strong> Cung cấp đầy đủ thông tin về công việc và yêu cầu</p>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-lightbulb"></i>
                            <p><strong>Quyền lợi rõ ràng:</strong> Nêu rõ các phúc lợi để thu hút ứng viên</p>
                        </div>
                        <div class="tip-item">
                            <i class="fas fa-lightbulb"></i>
                            <p><strong>Hạn nộp hợp lý:</strong> Đặt thời hạn phù hợp để nhận đủ hồ sơ</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 20px;">
                <div class="card-header">
                    <h3><i class="fas fa-exclamation-triangle"></i> Lưu ý</h3>
                </div>
                <div class="card-body">
                    <ul class="note-list">
                        <li>Tin đăng sẽ được admin duyệt trước khi hiển thị</li>
                        <li>Thông tin phải chính xác và trung thực</li>
                        <li>Không đăng tin spam hoặc nội dung vi phạm</li>
                        <li>Tin sẽ tự động hết hạn sau ngày đã chọn</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tips {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.tip-item {
    display: flex;
    gap: 10px;
}

.tip-item i {
    color: #ffc107;
    font-size: 1.2rem;
    margin-top: 3px;
}

.tip-item p {
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.6;
}

.note-list {
    padding-left: 20px;
    margin: 0;
}

.note-list li {
    margin-bottom: 10px;
    font-size: 0.9rem;
    color: #666;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
