<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-edit"></i> Chỉnh sửa yêu cầu trở thành nhà tuyển dụng</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>ungvien/suayeucau" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        
                        <div class="form-section">
                            <h5 class="section-title"><i class="fas fa-building"></i> Thông tin công ty</h5>
                            
                            <div class="form-group">
                                <label for="tencongty">Tên công ty <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tencongty" name="tencongty" 
                                       value="<?php echo htmlspecialchars($old['tencongty'] ?? $thongTinNhaTuyenDung['tencongty'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="masothue">Mã số thuế <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="masothue" name="masothue"
                                           value="<?php echo htmlspecialchars($old['masothue'] ?? $thongTinNhaTuyenDung['masothue'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="email_congty">Email công ty <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email_congty" name="email_congty"
                                           value="<?php echo htmlspecialchars($old['email_congty'] ?? $thongTinNhaTuyenDung['email_congty'] ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="diachi_congty">Địa chỉ công ty <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="diachi_congty" name="diachi_congty"
                                       value="<?php echo htmlspecialchars($old['diachi_congty'] ?? $thongTinNhaTuyenDung['diachi_congty'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="website">Website</label>
                                    <input type="url" class="form-control" id="website" name="website"
                                           value="<?php echo htmlspecialchars($old['website'] ?? $thongTinNhaTuyenDung['website'] ?? ''); ?>"
                                           placeholder="https://example.com">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="quymo">Quy mô công ty</label>
                                    <select class="form-control" id="quymo" name="quymo">
                                        <option value="">-- Chọn quy mô --</option>
                                        <?php
                                        $quymoList = ['1-10 nhân viên', '11-50 nhân viên', '51-200 nhân viên', '201-500 nhân viên', 'Trên 500 nhân viên'];
                                        $currentQuymo = $old['quymo'] ?? $thongTinNhaTuyenDung['quymo'] ?? '';
                                        foreach ($quymoList as $qm) {
                                            $selected = ($qm == $currentQuymo) ? 'selected' : '';
                                            echo "<option value=\"$qm\" $selected>$qm</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="linhvuc">Lĩnh vực hoạt động</label>
                                <input type="text" class="form-control" id="linhvuc" name="linhvuc"
                                       value="<?php echo htmlspecialchars($old['linhvuc'] ?? $thongTinNhaTuyenDung['linhvuc'] ?? ''); ?>"
                                       placeholder="VD: Công nghệ thông tin, Giáo dục,...">
                            </div>
                            
                            <div class="form-group">
                                <label for="mota">Mô tả công ty</label>
                                <textarea class="form-control" id="mota" name="mota" rows="4"
                                          placeholder="Giới thiệu về công ty của bạn..."><?php echo htmlspecialchars($old['mota'] ?? $thongTinNhaTuyenDung['mota'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="logo">Logo công ty</label>
                                <?php if (!empty($thongTinNhaTuyenDung['logo'])): ?>
                                    <div class="mb-2">
                                        <img src="<?php echo BASE_URL . 'public/uploads/logo/' . htmlspecialchars($thongTinNhaTuyenDung['logo']); ?>" 
                                             alt="Logo" style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                        <p class="text-muted small">Logo hiện tại</p>
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control-file" id="logo" name="logo" accept="image/*">
                                <small class="form-text text-muted">Chọn file mới nếu muốn thay đổi logo. Định dạng: JPG, PNG, GIF. Tối đa 5MB.</small>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h5 class="section-title"><i class="fas fa-clipboard-list"></i> Lý do yêu cầu</h5>
                            
                            <div class="form-group">
                                <label for="lydoyeucau">Lý do muốn trở thành nhà tuyển dụng <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="lydoyeucau" name="lydoyeucau" rows="4" required
                                          placeholder="Vui lòng cho biết lý do bạn muốn trở thành nhà tuyển dụng..."><?php echo htmlspecialchars($old['lydoyeucau'] ?? $thongTinNhaTuyenDung['lydoyeucau'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                            <a href="<?php echo BASE_URL; ?>taikhoan/choduyet" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
