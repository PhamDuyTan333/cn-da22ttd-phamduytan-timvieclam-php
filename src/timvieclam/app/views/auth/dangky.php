<?php
$pageTitle = isset($pageTitle) ? $pageTitle : 'Đăng ký';
?>

<div class="auth-container">
    <div class="container">
        <div class="auth-box">
            <div class="auth-header">
                <h2>Đăng Ký Tài Khoản</h2>
                <p>Tạo tài khoản để bắt đầu tìm việc làm</p>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo BASE_URL; ?>dangky/xuly" method="POST" class="auth-form" data-validate>
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="hoten" class="form-label">
                        <i class="fas fa-user"></i> Họ và tên <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="hoten" 
                        name="hoten" 
                        placeholder="Nhập họ và tên"
                        value="<?php echo htmlspecialchars($_SESSION['old_input']['hoten'] ?? ''); ?>"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Email <span class="required">*</span>
                    </label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        placeholder="example@email.com"
                        value="<?php echo htmlspecialchars($_SESSION['old_input']['email'] ?? ''); ?>"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="sodienthoai" class="form-label">
                        <i class="fas fa-phone"></i> Số điện thoại <span class="required">*</span>
                    </label>
                    <input 
                        type="tel" 
                        class="form-control" 
                        id="sodienthoai" 
                        name="sodienthoai" 
                        placeholder="0123456789"
                        value="<?php echo htmlspecialchars($_SESSION['old_input']['sodienthoai'] ?? ''); ?>"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="matkhau" class="form-label">
                        <i class="fas fa-lock"></i> Mật khẩu <span class="required">*</span>
                    </label>
                    <div class="password-input">
                        <input 
                            type="password" 
                            autocomplete="new-password"
                            class="form-control" 
                            id="matkhau" 
                            name="matkhau" 
                            placeholder="Tối thiểu 6 ký tự"
                            required
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword('matkhau')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="xacnhanmatkhau" class="form-label">
                        <i class="fas fa-lock"></i> Xác nhận mật khẩu <span class="required">*</span>
                    </label>
                    <div class="password-input">
                        <input 
                            type="password" 
                            autocomplete="new-password"
                            class="form-control" 
                            id="xacnhanmatkhau" 
                            name="xacnhanmatkhau" 
                            placeholder="Nhập lại mật khẩu"
                            required
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword('xacnhanmatkhau')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" required>
                        Tôi đồng ý với <a href="<?php echo BASE_URL; ?>chinhsach/dieukhoansudung" target="_blank" rel="noopener noreferrer">Điều khoản sử dụng</a> và <a href="<?php echo BASE_URL; ?>chinhsach/chinhsachbaomat" target="_blank" rel="noopener noreferrer">Chính sách bảo mật</a>
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> Đăng ký
                </button>
            </form>
            
            <div class="auth-footer">
                <p>Đã có tài khoản? <a href="<?php echo BASE_URL; ?>dangnhap">Đăng nhập ngay</a></p>
            </div>
        </div>
    </div>
</div>

<?php unset($_SESSION['old_input']); ?>

<style>
.auth-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    padding: 2rem 0;
}

.auth-box {
    max-width: 500px;
    margin: 0 auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    padding: 2.5rem;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-header h2 {
    color: var(--dark-color);
    margin-bottom: 0.5rem;
}

.auth-header p {
    color: var(--text-light);
}

.auth-form .form-group {
    margin-bottom: 1.5rem;
}

.auth-form .form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}

.auth-form .form-label i {
    color: var(--primary-color);
    margin-right: 0.5rem;
}

.required {
    color: var(--danger-color);
}

.password-input {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-light);
    cursor: pointer;
    padding: 0.5rem;
}

.toggle-password:hover {
    color: var(--primary-color);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.checkbox-label input[type="checkbox"] {
    width: auto;
}

.checkbox-label a {
    color: var(--primary-color);
    text-decoration: underline;
    font-weight: 500;
}

.checkbox-label a:hover {
    color: var(--primary-dark);
    text-decoration: none;
}

.btn-block {
    width: 100%;
    padding: 0.875rem;
    font-size: 1.125rem;
}

.auth-footer {
    text-align: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
}

.auth-footer a {
    color: var(--primary-color);
    font-weight: 500;
}

@media (max-width: 576px) {
    .auth-box {
        padding: 1.5rem;
    }
}
</style>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
