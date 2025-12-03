<?php require_once BASE_PATH . 'app/views/layouts/header.php'; ?>

<div class="container" style="margin-top: 100px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-key"></i> Đổi mật khẩu</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo htmlspecialchars($_SESSION['error']); 
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?php echo BASE_URL; ?>taikhoan/doimatkhau">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label>Mật khẩu cũ <span class="text-danger">*</span></label>
                            <input type="password" name="matkhau_cu" class="form-control" autocomplete="current-password" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" name="matkhau_moi" class="form-control" autocomplete="new-password" required minlength="8">
                            <small class="form-text text-muted">Tối thiểu 6 ký tự</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" name="xacnhan_matkhau" class="form-control" autocomplete="new-password" required>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Đổi mật khẩu
                            </button>
                            <a href="<?php echo BASE_URL; ?>taikhoan" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0 !important;
    padding: 20px;
}

.card-header h3 {
    margin: 0;
    font-size: 24px;
}

.card-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    font-weight: 600;
    color: #333;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px 15px;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>

<?php require_once BASE_PATH . 'app/views/layouts/footer.php'; ?>
