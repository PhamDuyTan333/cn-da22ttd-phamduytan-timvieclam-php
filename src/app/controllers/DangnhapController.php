<?php


require_once BASE_PATH . 'app/models/NguoiDungModel.php';

class DangnhapController extends BaseController {
    
    private $nguoiDungModel;
    
    public function __construct() {
        parent::__construct();
        $this->nguoiDungModel = new NguoiDungModel();
    }

    public function index() {
        // Nếu đã đăng nhập thì chuyển về trang chủ
        if (isset($_SESSION['nguoidung_id'])) {
            $this->redirect('');
        }
        
        $data = [
            'pageTitle' => 'Đăng nhập'
        ];
        
        $this->view('auth/dangnhap', $data);
    }

    public function xuly() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dangnhap');
        }
        
        // CSRF Protection
        verify_csrf();
        
        // Rate limiting - chống brute force
        if (!RateLimit::check('login', 5, 300)) {
            $blockedTime = RateLimit::getBlockedTime('login');
            $minutes = ceil($blockedTime / 60);
            $_SESSION['error'] = "Quá nhiều lần đăng nhập thất bại. Vui lòng thử lại sau {$minutes} phút.";
            $this->redirect('dangnhap');
            return;
        }
        
        $email = $this->sanitize($_POST['email'] ?? '');
        $matkhau = $_POST['matkhau'] ?? '';
        $ghinho = isset($_POST['ghinho']);
        
        // Validate
        if (empty($email) || empty($matkhau)) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin';
            $this->redirect('dangnhap');
            return;
        }
        
        // Kiểm tra đăng nhập
        $user = $this->nguoiDungModel->dangNhap($email, $matkhau);
        
        if ($user) {
            // Reset rate limit khi đăng nhập thành công
            RateLimit::reset('login');
            // Kiểm tra tài khoản có bị khóa không
            if ($user['trangthai'] == 'khoa') {
                $_SESSION['error'] = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.';
                $this->redirect('dangnhap');
            }
            
            // Lưu thông tin vào session
            $_SESSION['nguoidung_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['hoten'] = $user['hoten'];
            $_SESSION['vaitro'] = $user['vaitro'];
            $_SESSION['avatar'] = $user['avatar'];
            $_SESSION['xacminh'] = $user['xacminh'];
            
            // Ghi nhớ đăng nhập
            if ($ghinho) {
                setcookie('user_email', $email, [
                    'expires' => time() + (86400 * 30),
                    'path' => '/',
                    'httponly' => true,
                    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                    'samesite' => 'Strict'
                ]);
            }
            
            $_SESSION['success'] = 'Đăng nhập thành công!';
            
            // Chuyển hướng theo vai trò
            switch ($user['vaitro']) {
                case 'admin':
                    $this->redirect('admin');
                    break;
                case 'tuyendung':
                    $this->redirect('nhatuyendung');
                    break;
                case 'choduyet':
                    $_SESSION['info'] = 'Yêu cầu trở thành nhà tuyển dụng của bạn đang chờ phê duyệt';
                    $this->redirect('');
                    break;
                default:
                    $this->redirect('');
            }
        } else {
            // Đăng nhập thất bại - hiển thị số lần còn lại
            $remaining = RateLimit::getRemainingAttempts('login', 5);
            if ($remaining > 0) {
                $_SESSION['error'] = "Email hoặc mật khẩu không đúng. Còn {$remaining} lần thử.";
            } else {
                $_SESSION['error'] = 'Email hoặc mật khẩu không đúng';
            }
            $this->redirect('dangnhap');
        }
    }
}
?>
