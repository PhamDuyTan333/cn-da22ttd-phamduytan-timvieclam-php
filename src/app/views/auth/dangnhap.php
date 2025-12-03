<?php
$pageTitle = isset($pageTitle) ? $pageTitle : 'Đăng nhập';
?>

<div class="auth-container">
    <div class="container">
        <div class="auth-box">
            <div class="auth-header">
                <h2>Đăng Nhập</h2>
                <p>Chào mừng bạn trở lại!</p>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo htmlspecialchars($_SESSION['success']); 
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo BASE_URL; ?>dangnhap/xuly" method="POST" class="auth-form" data-validate>
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i> Email
                    </label>
                    <input 
                        type="email" 
                        class="form-control" 
                        id="email" 
                        name="email" 
                        placeholder="example@email.com"
                        value="<?php echo isset($_COOKIE['user_email']) ? htmlspecialchars($_COOKIE['user_email']) : ''; ?>"
                        required
                        autofocus
                    >
                </div>
                
                <div class="form-group">
                    <label for="matkhau" class="form-label">
                        <i class="fas fa-lock"></i> Mật khẩu
                    </label>
                    <div class="password-input">
                        <input 
                            type="password" 
                            autocomplete="current-password" 
                            class="form-control" 
                            id="matkhau" 
                            name="matkhau" 
                            placeholder="Nhập mật khẩu"
                            required
                        >
                        <button type="button" class="toggle-password" onclick="togglePassword('matkhau')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="ghinho" value="1">
                        Ghi nhớ đăng nhập
                    </label>
                </div>
                
                <div class="forgot-password-note">
                    <i class="fas fa-info-circle"></i>
                    <span>Quên mật khẩu? Vui lòng liên hệ Admin để được hỗ trợ</span>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                </button>
            </form>
            
            <div class="auth-footer">
                <p>Chưa có tài khoản? <a href="<?php echo BASE_URL; ?>dangky">Đăng ký ngay</a></p>
            </div>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    padding: 2rem 0;
}

.auth-box {
    max-width: 450px;
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

.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
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

.forgot-password-note {
    background: #f0f9ff;
    border-left: 3px solid var(--info-color);
    padding: 0.75rem 1rem;
    margin-bottom: 1.5rem;
    border-radius: 6px;
    font-size: 0.875rem;
    color: var(--text-color);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.forgot-password-note i {
    color: var(--info-color);
    font-size: 1rem;
}

.forgot-link {
    color: var(--primary-color);
    font-size: 0.875rem;
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
    
    .form-options {
        flex-direction: column;
        gap: 0.75rem;
        align-items: flex-start;
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
