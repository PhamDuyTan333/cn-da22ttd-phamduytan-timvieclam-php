<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Chỉnh sửa tin tuyển dụng</h1>
        <p>Cập nhật thông tin tin tuyển dụng</p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo BASE_URL; ?>nhatuyendung/xulySuatin/<?php echo $tin['id']; ?>" id="formSuatin">
                        <?php echo csrf_field(); ?>
                        <!-- Tiêu đề -->
                        <div class="form-group">
                            <label for="tieude">Tiêu đề tin tuyển dụng <span class="required">*</span></label>
                            <input type="text" 
                                   class="form-control" 
                                   id="tieude" 
                                   name="tieude" 
                                   value="<?php echo htmlspecialchars($tin['tieude']); ?>"
                                   required
                                   maxlength="200">
                            <small class="char-counter"><?php echo strlen($tin['tieude']); ?>/200 ký tự</small>
                        </div>

                        <!-- Ngành nghề -->
                        <div class="form-group">
                            <label for="nganhnghe_id">Ngành nghề <span class="required">*</span></label>
                            <select class="form-control" id="nganhnghe_id" name="nganhnghe_id" required>
                                <option value="">-- Chọn ngành nghề --</option>
                                <?php foreach ($danhMuc['nganhnghe'] as $nn): ?>
                                    <option value="<?php echo $nn['id']; ?>" 
                                            <?php echo $tin['nganhnghe_id'] == $nn['id'] ? 'selected' : ''; ?>>
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
                                            <option value="<?php echo $ml['id']; ?>"
                                                    <?php echo $tin['mucluong_id'] == $ml['id'] ? 'selected' : ''; ?>>
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
                                            <option value="<?php echo $lcv['id']; ?>"
                                                    <?php echo $tin['loaicongviec_id'] == $lcv['id'] ? 'selected' : ''; ?>>
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
                                            <option value="<?php echo $tt['id']; ?>"
                                                    <?php echo $tin['tinhthanh_id'] == $tt['id'] ? 'selected' : ''; ?>>
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
                                           value="<?php echo $tin['soluong']; ?>"
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
                                   value="<?php echo htmlspecialchars($tin['diachilamviec'] ?? ''); ?>"
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
                                           value="<?php echo htmlspecialchars($tin['kinhnghiem'] ?? ''); ?>"
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
                                           value="<?php echo htmlspecialchars($tin['trinhdo'] ?? ''); ?>"
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
                                      required><?php echo htmlspecialchars($tin['mota']); ?></textarea>
                            <small class="char-counter"><?php echo strlen($tin['mota']); ?> ký tự</small>
                        </div>

                        <!-- Yêu cầu -->
                        <div class="form-group">
                            <label for="yeucau">Yêu cầu ứng viên <span class="required">*</span></label>
                            <textarea class="form-control" 
                                      id="yeucau" 
                                      name="yeucau" 
                                      rows="6"
                                      required><?php echo htmlspecialchars($tin['yeucau']); ?></textarea>
                            <small class="char-counter"><?php echo strlen($tin['yeucau']); ?> ký tự</small>
                        </div>

                        <!-- Quyền lợi -->
                        <div class="form-group">
                            <label for="quyenloi">Quyền lợi</label>
                            <textarea class="form-control" 
                                      id="quyenloi" 
                                      name="quyenloi" 
                                      rows="4"><?php echo htmlspecialchars($tin['quyenloi'] ?? ''); ?></textarea>
                            <small class="char-counter"><?php echo strlen($tin['quyenloi'] ?? ''); ?> ký tự</small>
                        </div>

                        <!-- Hạn nộp hồ sơ -->
                        <div class="form-group">
                            <label for="hannop">Hạn nộp hồ sơ <span class="required">*</span></label>
                            <input type="date" 
                                   class="form-control" 
                                   id="hannop" 
                                   name="hannop"
                                   value="<?php echo !empty($tin['ngayhethan']) ? date('Y-m-d', strtotime($tin['ngayhethan'])) : ''; ?>"
                                   min="<?php echo date('Y-m-d'); ?>"
                                   required>
                        </div>

                        <!-- Trạng thái -->
                        <div class="form-group">
                            <label for="trangthai">Trạng thái</label>
                            <select class="form-control" id="trangthai" name="trangthai">
                                <option value="dangmo" <?php echo $tin['trangthai'] == 'dangmo' ? 'selected' : ''; ?>>Đang mở</option>
                                <option value="an" <?php echo $tin['trangthai'] == 'an' ? 'selected' : ''; ?>>Ẩn</option>
                            </select>
                            <small class="form-text text-muted">
                                Chọn "Ẩn" nếu muốn tạm dừng nhận hồ sơ
                            </small>
                        </div>

                        <!-- Nút hành động -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                            <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachtin" class="btn btn-outline">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar thông tin -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Thông tin tin đăng</h3>
                </div>
                <div class="card-body">
                    <div class="info-list">
                        <div class="info-row">
                            <strong>Ngày đăng:</strong>
                            <span><?php echo date('d/m/Y H:i', strtotime($tin['ngaydang'])); ?></span>
                        </div>
                        <div class="info-row">
                            <strong>Lượt xem:</strong>
                            <span><?php echo number_format($tin['luotxem']); ?></span>
                        </div>
                        <div class="info-row">
                            <strong>Đơn ứng tuyển:</strong>
                            <span><?php echo number_format($tin['sodon'] ?? 0); ?></span>
                        </div>
                        <div class="info-row">
                            <strong>Trạng thái hiện tại:</strong>
                            <?php
                            $statusLabels = [
                                'choduyet' => '<span class="badge badge-warning">Chờ duyệt</span>',
                                'dangmo' => '<span class="badge badge-success">Đang mở</span>',
                                'an' => '<span class="badge badge-secondary">Đã ẩn</span>',
                                'hethan' => '<span class="badge badge-danger">Hết hạn</span>'
                            ];
                            echo $statusLabels[$tin['trangthai']] ?? $tin['trangthai'];
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 20px;">
                <div class="card-body">
                    <a href="<?php echo BASE_URL; ?>tintuyendung/chitiet/<?php echo $tin['id']; ?>" 
                       class="btn btn-outline btn-block"
                       target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-eye"></i> Xem tin đã đăng
                    </a>
                    <a href="<?php echo BASE_URL; ?>nhatuyendung/danhsachungvien/<?php echo $tin['id']; ?>" 
                       class="btn btn-primary btn-block"
                       style="margin-top: 10px;">
                        <i class="fas fa-users"></i> Xem danh sách ứng viên
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.badge {
    padding: 5px 10px;
    border-radius: 12px;
    font-size: 0.85rem;
    font-weight: 500;
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

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
