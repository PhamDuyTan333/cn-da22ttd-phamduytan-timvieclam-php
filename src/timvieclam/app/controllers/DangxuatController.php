<?php



class DangxuatController extends BaseController {
    
    public function index() {
        // Xóa tất cả session
        session_unset();
        session_destroy();
        
        // Xóa cookie
        if (isset($_COOKIE['user_email'])) {
            setcookie('user_email', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'samesite' => 'Strict'
            ]);
        }
        
        // Trả về HTML với script để xóa chatbot session
        echo '<!DOCTYPE html>
<html>
<head>
    <title>Đang đăng xuất...</title>
    <script>
        // Xóa tất cả chatbot sessions trong localStorage
        const keys = Object.keys(localStorage);
        keys.forEach(key => {
            if (key.startsWith("chatbot_session_")) {
                localStorage.removeItem(key);
            }
        });
        
        // Redirect về trang chủ
        window.location.href = "' . BASE_URL . '";
    </script>
</head>
<body>
    <p>Đang đăng xuất...</p>
</body>
</html>';
        exit;
    }
}
?>
