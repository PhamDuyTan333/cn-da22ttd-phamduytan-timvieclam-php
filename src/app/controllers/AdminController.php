<?php


require_once BASE_PATH . 'app/models/NguoiDungModel.php';
require_once BASE_PATH . 'app/models/TinTuyenDungModel.php';
require_once BASE_PATH . 'app/models/DonUngTuyenModel.php';
require_once BASE_PATH . 'app/models/AdminModel.php';

class AdminController extends BaseController {
    
    private $nguoiDungModel;
    private $tinModel;
    private $donModel;
    private $adminModel;
    
    public function __construct() {
        parent::__construct();
        $this->checkRole(['admin']);
        $this->nguoiDungModel = new NguoiDungModel();
        $this->tinModel = new TinTuyenDungModel();
        $this->donModel = new DonUngTuyenModel();
        $this->adminModel = new AdminModel();
    }
    
    public function index() {
        $stats = $this->adminModel->layThongKeTongQuan();
        $thongKeThang = $this->adminModel->layThongKeTheoThang();
        $tinMoi = $this->adminModel->layTinMoiChoDuyet(5);
        
        $data = [
            'pageTitle' => 'Trang quản trị',
            'stats' => $stats,
            'thongKeThang' => $thongKeThang,
            'tinMoi' => $tinMoi
        ];
        
        $this->view('admin/index', $data);
    }

    public function nguoidung() {
        $vaitro = $_GET['vaitro'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $recordsPerPage = 20;
        $offset = ($page - 1) * $recordsPerPage;
        
        // Đếm
        $total = $this->nguoiDungModel->demNguoiDung($vaitro);
        
        // Lấy danh sách
        $danhSach = $this->nguoiDungModel->layDanhSach($vaitro, $recordsPerPage, $offset);
        
        // Phân trang
        $pagination = $this->paginate($total, $page, $recordsPerPage);
        
        $data = [
            'pageTitle' => 'Quản lý người dùng',
            'danhSach' => $danhSach,
            'pagination' => $pagination,
            'filterVaitro' => $vaitro
        ];
        
        $this->view('admin/nguoidung', $data);
    }

    public function khoataikhoan($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/nguoidung');
        }
        
        // CSRF Protection
        verify_csrf();
        
        $user = $this->nguoiDungModel->layThongTin($id);
        
        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại';
            $this->redirect('admin/nguoidung');
        }
        
        // Không cho phép khóa admin
        if ($user['vaitro'] == 'admin') {
            $_SESSION['error'] = 'Không thể khóa tài khoản admin';
            $this->redirect('admin/nguoidung');
        }
        
        $trangthaiMoi = $user['trangthai'] == 'hoatdong' ? 'khoa' : 'hoatdong';
        
        if ($this->nguoiDungModel->capNhatTrangThai($id, $trangthaiMoi)) {
            $_SESSION['success'] = $trangthaiMoi == 'khoa' ? 'Khóa tài khoản thành công' : 'Mở khóa tài khoản thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        
        $this->redirect('admin/nguoidung');
    }

    public function yeucaunhatuyendung() {
        $danhSach = $this->adminModel->layYeuCauNhaTuyenDung();
        
        $data = [
            'pageTitle' => 'Yêu cầu trở thành nhà tuyển dụng',
            'danhSach' => $danhSach
        ];
        
        $this->view('admin/yeucaunhatuyendung', $data);
    }

    public function duyetyeucau($id) {
        $id = (int)$id;
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Phương thức không hợp lệ';
            $this->redirect('admin/yeucaunhatuyendung');
            return;
        }
        
        // CSRF Protection
        verify_csrf();
        
        try {
            $hanhdong = $_POST['hanhdong'] ?? '';
            
            if ($hanhdong == 'duyet') {
                if ($this->adminModel->duyetNhaTuyenDung($id)) {
                    $_SESSION['success'] = 'Duyệt yêu cầu thành công. Người dùng đã trở thành nhà tuyển dụng.';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi duyệt yêu cầu.';
                }
                
            } elseif ($hanhdong == 'tuchoi') {
                if ($this->adminModel->tuChoiNhaTuyenDung($id)) {
                    $_SESSION['success'] = 'Đã từ chối yêu cầu. Thông tin công ty đã được xóa.';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi từ chối yêu cầu.';
                }
            } else {
                $_SESSION['error'] = 'Hành động không hợp lệ: ' . htmlspecialchars($hanhdong);
            }
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            error_log("Error in duyetyeucau: " . $e->getMessage());
        }
        
        $this->redirect('admin/yeucaunhatuyendung');
    }

    public function tintuyendung() {
        $trangthai = $_GET['trangthai'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $recordsPerPage = 20;
        $offset = ($page - 1) * $recordsPerPage;
        
        $danhSach = $this->adminModel->layDanhSachTinTuyenDung($trangthai, $recordsPerPage, $offset);
        $total = $this->adminModel->demTinTuyenDung($trangthai);
        $pagination = $this->paginate($total, $page, $recordsPerPage);
        
        $data = [
            'pageTitle' => 'Quản lý tin tuyển dụng',
            'danhSach' => $danhSach,
            'pagination' => $pagination,
            'filterTrangthai' => $trangthai
        ];
        
        $this->view('admin/tintuyendung', $data);
    }

    public function duyettin($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/tintuyendung');
        }
        
        // CSRF Protection
        verify_csrf();
        
        $trangthai = $_POST['trangthai'] ?? '';
        
        if (in_array($trangthai, ['dangmo', 'an'])) {
            $this->tinModel->capNhatTrangThai($id, $trangthai);
            $_SESSION['success'] = 'Cập nhật trạng thái tin thành công';
        }
        
        $this->redirect('admin/tintuyendung');
    }

    public function xoatin($id) {
        if ($this->tinModel->xoa($id)) {
            $_SESSION['success'] = 'Xóa tin thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        
        $this->redirect('admin/tintuyendung');
    }

    public function donungtuyen() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $recordsPerPage = 20;
        $offset = ($page - 1) * $recordsPerPage;
        
        $danhSach = $this->adminModel->layDanhSachDonUngTuyen($recordsPerPage, $offset);
        $total = $this->adminModel->demDonUngTuyen();
        $pagination = $this->paginate($total, $page, $recordsPerPage);
        
        $data = [
            'pageTitle' => 'Quản lý đơn ứng tuyển',
            'danhSach' => $danhSach,
            'pagination' => $pagination
        ];
        
        $this->view('admin/donungtuyen', $data);
    }

    public function xoadon($id) {
        if ($this->donModel->xoa($id)) {
            $_SESSION['success'] = 'Xóa đơn thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        
        $this->redirect('admin/donungtuyen');
    }

    public function thongke() {
        // Thống kê chi tiết
        $data = [
            'pageTitle' => 'Thống kê hệ thống'
        ];
        
        $this->view('admin/thongke', $data);
    }
}
?>
