<?php


require_once BASE_PATH . 'app/models/NguoiDungModel.php';
require_once BASE_PATH . 'app/models/ThongTinUngVienModel.php';

class DangkyController extends BaseController {
    
    private $nguoiDungModel;
    private $thongTinUngVienModel;
    
    public function __construct() {
        parent::__construct();
        $this->nguoiDungModel = new NguoiDungModel();
        $this->thongTinUngVienModel = new ThongTinUngVienModel();
    }

    public function index() {
        // Nếu đã đăng nhập thì chuyển về trang chủ
        if (isset($_SESSION['nguoidung_id'])) {
            $this->redirect('');
        }
        
        $data = [
            'pageTitle' => 'Đăng ký tài khoản'
        ];
        
        $this->view('auth/dangky', $data);
    }

    public function xuly() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('dangky');
        }
        
        // CSRF Protection
        verify_csrf();
        
        // Lấy dữ liệu từ form
        $hoten = $this->sanitize($_POST['hoten'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $sodienthoai = $this->sanitize($_POST['sodienthoai'] ?? '');
        $matkhau = $_POST['matkhau'] ?? '';
        $xacnhanmatkhau = $_POST['xacnhanmatkhau'] ?? '';
        
        // Validate
        $errors = [];
        
        if (empty($hoten)) {
            $errors[] = 'Vui lòng nhập họ tên';
        }
        
        if (empty($email)) {
            $errors[] = 'Vui lòng nhập email';
        } elseif (!$this->validateEmail($email)) {
            $errors[] = 'Email không hợp lệ';
        } elseif ($this->nguoiDungModel->kiemTraEmail($email)) {
            $errors[] = 'Email đã được sử dụng';
        }
        
        if (empty($sodienthoai)) {
            $errors[] = 'Vui lòng nhập số điện thoại';
        } elseif (!preg_match('/^[0-9]{10,11}$/', $sodienthoai)) {
            $errors[] = 'Số điện thoại không hợp lệ (10-11 số)';
        }
        
        if (empty($matkhau)) {
            $errors[] = 'Vui lòng nhập mật khẩu';
        } elseif (strlen($matkhau) < 8) {
            $errors[] = 'Mật khẩu phải có ít nhất 8 ký tự';
        } elseif (!preg_match('/[A-Z]/', $matkhau)) {
            $errors[] = 'Mật khẩu phải có ít nhất 1 chữ hoa';
        } elseif (!preg_match('/[a-z]/', $matkhau)) {
            $errors[] = 'Mật khẩu phải có ít nhất 1 chữ thường';
        } elseif (!preg_match('/[0-9]/', $matkhau)) {
            $errors[] = 'Mật khẩu phải có ít nhất 1 chữ số';
        }
        
        if ($matkhau !== $xacnhanmatkhau) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }
        
        // Nếu có lỗi
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['old_input'] = $_POST;
            $this->redirect('dangky');
        }
        
        // Tạo tài khoản
        $data = [
            'email' => $email,
            'matkhau' => $matkhau,
            'hoten' => $hoten,
            'sodienthoai' => $sodienthoai,
            'vaitro' => 'ungvien' // Mặc định là ứng viên
        ];
        
        $userId = $this->nguoiDungModel->taoTaiKhoan($data);
        
        if ($userId) {
            $this->thongTinUngVienModel->taoThongTinMacDinh($userId);
            
            $_SESSION['success'] = 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.';
            $this->redirect('dangnhap');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
            $this->redirect('dangky');
        }
    }
}
?>
