<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Website Tìm Việc Làm</title>
    <meta name="description" content="Tìm việc làm nhanh chóng và dễ dàng. Kết nối ứng viên với nhà tuyển dụng.">
    <meta name="keywords" content="tìm việc làm, tuyển dụng, việc làm, ứng viên, nhà tuyển dụng">
    
    <!-- User ID for Chatbot Session -->
    <?php if (isset($_SESSION['nguoidung_id'])): ?>
    <meta name="user-id" content="<?php echo $_SESSION['nguoidung_id']; ?>">
    <?php endif; ?>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css?v=<?php echo time(); ?>">
    <?php if (isset($_SESSION['vaitro']) && $_SESSION['vaitro'] == 'admin'): ?>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/admin.css?v=<?php echo time(); ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>images/logo-favicon.ico">
    
    <!-- Base URL for JavaScript -->
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="<?php echo BASE_URL; ?>">
                        <img src="<?php echo BASE_URL; ?>images/logo.png" alt="Tìm Việc Làm" style="height: 40px; filter: brightness(1.2) contrast(1.1);">
                        <span>Tìm Việc Làm</span>
                    </a>
                </div>
                
                <ul class="nav-menu">
                    <li><a href="<?php echo BASE_URL; ?>">Trang chủ</a></li>
                    <li><a href="<?php echo BASE_URL; ?>timkiem">Tìm việc làm</a></li>
                    
                    <?php if (isset($_SESSION['nguoidung_id'])): ?>
                        <?php if ($_SESSION['vaitro'] == 'admin'): ?>
                            <li><a href="<?php echo BASE_URL; ?>admin">Quản trị</a></li>
                        <?php elseif ($_SESSION['vaitro'] == 'tuyendung'): ?>
                            <li><a href="<?php echo BASE_URL; ?>nhatuyendung">Quản lý tin</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo BASE_URL; ?>ungvien/donungtuyen">Đơn ứng tuyển</a></li>
                        <?php endif; ?>
                        
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle">
                                <i class="fas fa-user-circle"></i>
                                <?php echo htmlspecialchars($_SESSION['hoten']); ?>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if ($_SESSION['vaitro'] == 'ungvien'): ?>
                                    <li><a href="<?php echo BASE_URL; ?>ungvien/hoso"><i class="fas fa-user"></i> Hồ sơ của tôi</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>ungvien/yeucautuyendung"><i class="fas fa-briefcase"></i> Trở thành NTD</a></li>
                                <?php elseif ($_SESSION['vaitro'] == 'tuyendung'): ?>
                                    <li><a href="<?php echo BASE_URL; ?>nhatuyendung"><i class="fas fa-tachometer-alt"></i> Trang quản lý</a></li>
                                <?php elseif ($_SESSION['vaitro'] == 'admin'): ?>
                                    <li><a href="<?php echo BASE_URL; ?>admin"><i class="fas fa-cog"></i> Trang quản trị</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a href="<?php echo BASE_URL; ?>taikhoan/doimatkhau"><i class="fas fa-key"></i> Đổi mật khẩu</a></li>
                                <li><a href="<?php echo BASE_URL; ?>dangxuat"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>dangnhap" class="btn-login">Đăng nhập</a></li>
                        <li><a href="<?php echo BASE_URL; ?>dangky" class="btn-register">Đăng ký</a></li>
                    <?php endif; ?>
                </ul>
                
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
