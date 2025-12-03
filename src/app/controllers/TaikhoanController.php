<?php


require_once BASE_PATH . 'app/models/NguoiDungModel.php';

class TaikhoanController extends BaseController {
    
    private $nguoiDungModel;
    private $db;
    
    public function __construct() {
        parent::__construct();
        
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['nguoidung_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để truy cập trang này';
            $this->redirect('dangnhap');
        }
        
        $this->db = Database::getInstance()->getConnection();
        $this->nguoiDungModel = new NguoiDungModel();
    }

    public function index() {
        $userId = $_SESSION['nguoidung_id'];
        $vaitro = $_SESSION['vaitro'];
        
        // Lấy thông tin user
        $user = $this->nguoiDungModel->layThongTin($userId);
        
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy thông tin tài khoản';
            $this->redirect('');
            return;
        }
        
        // Lấy thông tin bổ sung theo vai trò
        $thongTinBoSung = null;
        if ($vaitro == 'ungvien') {
            // Lấy thông tin ứng viên
            $sql = "SELECT * FROM thongtinungvien WHERE nguoidung_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            $thongTinBoSung = $stmt->fetch(PDO::FETCH_ASSOC);
        } elseif ($vaitro == 'tuyendung') {
            // Lấy thông tin nhà tuyển dụng
            $sql = "SELECT * FROM thongtinnhatuyendung WHERE nguoidung_id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $userId);
            $stmt->execute();
            $thongTinBoSung = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        $data = [
            'pageTitle' => 'Cài đặt tài khoản',
            'user' => $user,
            'thongTinBoSung' => $thongTinBoSung
        ];
        
        $this->view('taikhoan/index', $data);
    }

    public function doimatkhau() {
        $userId = $_SESSION['nguoidung_id'];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // CSRF Protection
            verify_csrf();
            
            $matkhauCu = $this->sanitize($_POST['matkhau_cu'] ?? '');
            $matkhauMoi = $this->sanitize($_POST['matkhau_moi'] ?? '');
            $xacnhanMatkhau = $this->sanitize($_POST['xacnhan_matkhau'] ?? '');
            
            // Validate
            if (empty($matkhauCu) || empty($matkhauMoi) || empty($xacnhanMatkhau)) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                $this->redirect('taikhoan/doimatkhau');
                return;
            }
            
            if ($matkhauMoi !== $xacnhanMatkhau) {
                $_SESSION['error'] = 'Mật khẩu mới không khớp';
                $this->redirect('taikhoan/doimatkhau');
                return;
            }
            
            if (strlen($matkhauMoi) < 8) {
                $_SESSION['error'] = 'Mật khẩu mới phải có ít nhất 8 ký tự';
                $this->redirect('taikhoan/doimatkhau');
                return;
            }
            
            // Lấy thông tin user
            $user = $this->nguoiDungModel->layThongTin($userId);
            
            // Kiểm tra mật khẩu cũ
            if (!password_verify($matkhauCu, $user['matkhau'])) {
                $_SESSION['error'] = 'Mật khẩu cũ không đúng';
                $this->redirect('taikhoan/doimatkhau');
                return;
            }
            
            // Cập nhật mật khẩu mới
            $matkhauHash = password_hash($matkhauMoi, PASSWORD_DEFAULT);
            $sql = "UPDATE nguoidung SET matkhau = :matkhau WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':matkhau', $matkhauHash);
            $stmt->bindParam(':id', $userId);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Đổi mật khẩu thành công';
                $this->redirect('taikhoan');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
                $this->redirect('taikhoan/doimatkhau');
            }
            return;
        }
        
        require_once BASE_PATH . 'app/views/taikhoan/doimatkhau.php';
    }

    public function capnhat() {
        $userId = $_SESSION['nguoidung_id'];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // CSRF Protection
            verify_csrf();
            
            $hoten = $this->sanitize(trim($_POST['hoten'] ?? ''));
            $sodienthoai = $this->sanitize(trim($_POST['sodienthoai'] ?? ''));
            $diachi = $this->sanitize(trim($_POST['diachi'] ?? ''));
            
            // Validate
            if (empty($hoten)) {
                $_SESSION['error'] = 'Vui lòng nhập họ tên';
                $this->redirect('taikhoan');
                return;
            }
            
            // Upload avatar nếu có
            $avatar = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $result = $this->uploadFile(
                    $_FILES['avatar'],
                    'public/uploads/avatar/',
                    ['image/jpeg', 'image/png', 'image/gif'],
                    2097152 // 2MB
                );
                
                if ($result['success']) {
                    $avatar = $result['filename'];
                } else {
                    $_SESSION['error'] = $result['error'];
                    $this->redirect('taikhoan');
                    return;
                }
            }
            
            // Cập nhật database
            if ($avatar) {
                $sql = "UPDATE nguoidung SET hoten = :hoten, sodienthoai = :sodienthoai, 
                        diachi = :diachi, avatar = :avatar WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':avatar', $avatar);
            } else {
                $sql = "UPDATE nguoidung SET hoten = :hoten, sodienthoai = :sodienthoai, 
                        diachi = :diachi WHERE id = :id";
                $stmt = $this->db->prepare($sql);
            }
            
            $stmt->bindParam(':hoten', $hoten);
            $stmt->bindParam(':sodienthoai', $sodienthoai);
            $stmt->bindParam(':diachi', $diachi);
            $stmt->bindParam(':id', $userId);
            
            if ($stmt->execute()) {
                // Cập nhật session
                $_SESSION['hoten'] = $hoten;
                if ($avatar) {
                    $_SESSION['avatar'] = $avatar;
                }
                
                $_SESSION['success'] = 'Cập nhật thông tin thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
            
            $this->redirect('taikhoan');
        }
    }

    public function capnhatcongty() {
        $userId = $_SESSION['nguoidung_id'];
        $vaitro = $_SESSION['vaitro'];
        
        if ($vaitro != 'tuyendung') {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện chức năng này';
            $this->redirect('taikhoan');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // CSRF Protection
            verify_csrf();
            
            $tencongty = $this->sanitize(trim($_POST['tencongty'] ?? ''));
            $masothue = $this->sanitize(trim($_POST['masothue'] ?? ''));
            $diachi_congty = $this->sanitize(trim($_POST['diachi_congty'] ?? ''));
            $website = $this->sanitize(trim($_POST['website'] ?? ''));
            $email_congty = $this->sanitize(trim($_POST['email_congty'] ?? ''));
            $quymo = $this->sanitize($_POST['quymo'] ?? '');
            $linhvuc = $this->sanitize(trim($_POST['linhvuc'] ?? ''));
            $mota = $this->sanitize(trim($_POST['mota'] ?? ''));
            
            // Validate
            if (empty($tencongty)) {
                $_SESSION['error'] = 'Vui lòng nhập tên công ty';
                $this->redirect('taikhoan');
                return;
            }
            
            // Upload logo nếu có
            $logo = null;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                $result = $this->uploadFile(
                    $_FILES['logo'],
                    'public/uploads/logo/',
                    ['image/jpeg', 'image/png'],
                    5242880 // 5MB
                );
                
                if ($result['success']) {
                    $logo = $result['filename'];
                } else {
                    $_SESSION['error'] = $result['error'];
                    $this->redirect('taikhoan');
                    return;
                }
            }
            
            // Kiểm tra xem đã có thông tin công ty chưa
            $sqlCheck = "SELECT id FROM thongtinnhatuyendung WHERE nguoidung_id = :id";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->bindParam(':id', $userId);
            $stmtCheck->execute();
            $exists = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if ($exists) {
                // Update
                if ($logo) {
                    $sql = "UPDATE thongtinnhatuyendung SET 
                            tencongty = :tencongty, masothue = :masothue, 
                            diachi_congty = :diachi_congty, website = :website, 
                            email_congty = :email_congty, quymo = :quymo, 
                            linhvuc = :linhvuc, mota = :mota, logo = :logo 
                            WHERE nguoidung_id = :id";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':logo', $logo);
                } else {
                    $sql = "UPDATE thongtinnhatuyendung SET 
                            tencongty = :tencongty, masothue = :masothue, 
                            diachi_congty = :diachi_congty, website = :website, 
                            email_congty = :email_congty, quymo = :quymo, 
                            linhvuc = :linhvuc, mota = :mota 
                            WHERE nguoidung_id = :id";
                    $stmt = $this->db->prepare($sql);
                }
            } else {
                // Insert
                $sql = "INSERT INTO thongtinnhatuyendung 
                        (nguoidung_id, tencongty, masothue, diachi_congty, website, 
                         email_congty, quymo, linhvuc, mota" . ($logo ? ", logo" : "") . ") 
                        VALUES (:id, :tencongty, :masothue, :diachi_congty, :website, 
                                :email_congty, :quymo, :linhvuc, :mota" . ($logo ? ", :logo" : "") . ")";
                $stmt = $this->db->prepare($sql);
                if ($logo) {
                    $stmt->bindParam(':logo', $logo);
                }
            }
            
            $stmt->bindParam(':id', $userId);
            $stmt->bindParam(':tencongty', $tencongty);
            $stmt->bindParam(':masothue', $masothue);
            $stmt->bindParam(':diachi_congty', $diachi_congty);
            $stmt->bindParam(':website', $website);
            $stmt->bindParam(':email_congty', $email_congty);
            $stmt->bindParam(':quymo', $quymo);
            $stmt->bindParam(':linhvuc', $linhvuc);
            $stmt->bindParam(':mota', $mota);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Cập nhật thông tin công ty thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
            
            $this->redirect('taikhoan');
        }
    }
}
