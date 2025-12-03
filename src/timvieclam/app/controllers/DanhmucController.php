<?php


require_once BASE_PATH . 'app/models/DanhMucModel.php';

class DanhmucController extends BaseController {
    private $danhMucModel;
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->danhMucModel = new DanhMucModel();
        $this->db = Database::getInstance()->getConnection();
        
        // Kiểm tra quyền admin
        if (!isset($_SESSION['nguoidung_id']) || $_SESSION['vaitro'] != 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            $this->redirect('dangnhap');
        }
    }

    public function index() {
        $stats = $this->danhMucModel->demDanhMuc();
        
        require_once BASE_PATH . 'app/views/admin/danhmuc/index.php';
    }

    public function nganhnghe() {
        $danhSach = $this->danhMucModel->layTatCaNganhNghe();
        
        require_once BASE_PATH . 'app/views/admin/danhmuc/nganhnghe.php';
    }

    public function themnganhnghe() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // CSRF Protection
            verify_csrf();
            
            $tennganh = trim($_POST['tennganh']);
            $mota = trim($_POST['mota'] ?? '');
            
            if (empty($tennganh)) {
                $_SESSION['error'] = 'Vui lòng nhập tên ngành nghề';
                $this->redirect('danhmuc/nganhnghe');
                return;
            }
            
            if ($this->danhMucModel->themNganhNghe($tennganh, $mota)) {
                $_SESSION['success'] = 'Thêm ngành nghề thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
        }
        
        $this->redirect('danhmuc/nganhnghe');
    }

    public function suanganhnghe($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // CSRF Protection
            verify_csrf();
            
            $tennganh = trim($_POST['tennganh']);
            $mota = trim($_POST['mota'] ?? '');
            
            if (empty($tennganh)) {
                $_SESSION['error'] = 'Vui lòng nhập tên ngành nghề';
                $this->redirect('danhmuc/nganhnghe');
                return;
            }
            
            if ($this->danhMucModel->capNhatNganhNghe($id, $tennganh, $mota)) {
                $_SESSION['success'] = 'Cập nhật thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
        }
        
        $this->redirect('danhmuc/nganhnghe');
    }

    public function xoanganhnghe($id) {
        if ($this->danhMucModel->kiemTraNganhNgheDangDung($id)) {
            $_SESSION['error'] = 'Không thể xóa ngành nghề đang được sử dụng';
            $this->redirect('danhmuc/nganhnghe');
            return;
        }
        
        if ($this->danhMucModel->xoaNganhNghe($id)) {
            $_SESSION['success'] = 'Xóa ngành nghề thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        
        $this->redirect('danhmuc/nganhnghe');
    }    
    public function mucluong() {
        $danhSach = $this->danhMucModel->layMucLuong();
        
        require_once BASE_PATH . 'app/views/admin/danhmuc/mucluong.php';
    }
    
    public function themmucluong() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            verify_csrf();
            
            $ten = trim($_POST['tenmucluong'] ?? '');
            $giatri_min = isset($_POST['giatri_min']) && $_POST['giatri_min'] !== '' ? floatval($_POST['giatri_min']) : null;
            $giatri_max = isset($_POST['giatri_max']) && $_POST['giatri_max'] !== '' ? floatval($_POST['giatri_max']) : null;
            
            if (empty($ten)) {
                $_SESSION['error'] = 'Vui lòng nhập tên mức lương';
                $this->redirect('danhmuc/mucluong');
                return;
            }
            
            $sql = "INSERT INTO mucluong (tenmucluong, giatri_min, giatri_max) VALUES (:ten, :min, :max)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ten', $ten);
            $stmt->bindParam(':min', $giatri_min);
            $stmt->bindParam(':max', $giatri_max);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Thêm mức lương thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
        }
        
        $this->redirect('danhmuc/mucluong');
    }
    
    public function suamucluong($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            verify_csrf();
            
            $ten = trim($_POST['tenmucluong'] ?? '');
            $giatri_min = isset($_POST['giatri_min']) && $_POST['giatri_min'] !== '' ? floatval($_POST['giatri_min']) : null;
            $giatri_max = isset($_POST['giatri_max']) && $_POST['giatri_max'] !== '' ? floatval($_POST['giatri_max']) : null;
            
            if (empty($ten)) {
                $_SESSION['error'] = 'Vui lòng nhập tên mức lương';
                $this->redirect('danhmuc/mucluong');
                return;
            }
            
            $sql = "UPDATE mucluong SET tenmucluong = :ten, giatri_min = :min, giatri_max = :max WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ten', $ten);
            $stmt->bindParam(':min', $giatri_min);
            $stmt->bindParam(':max', $giatri_max);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Cập nhật mức lương thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
        }
        
        $this->redirect('danhmuc/mucluong');
    }
    
    public function xoamucluong($id) {
        verify_csrf();
        
        // Check if being used
        $check = $this->db->query("SELECT COUNT(*) FROM tintuyendung WHERE mucluong_id = $id")->fetchColumn();
        
        if ($check > 0) {
            $_SESSION['error'] = 'Không thể xóa mức lương đang được sử dụng';
            $this->redirect('danhmuc/mucluong');
            return;
        }
        
        $sql = "DELETE FROM mucluong WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Xóa mức lương thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        
        $this->redirect('danhmuc/mucluong');
    }

    public function loaicongviec() {
        $danhSach = $this->danhMucModel->layLoaiCongViec();
        
        require_once BASE_PATH . 'app/views/admin/danhmuc/loaicongviec.php';
    }
    
    public function themloaicongviec() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            verify_csrf();
            
            $ten = trim($_POST['tenloai'] ?? '');
            $mota = trim($_POST['mota'] ?? '');
            
            if (empty($ten)) {
                $_SESSION['error'] = 'Vui lòng nhập tên loại công việc';
                $this->redirect('danhmuc/loaicongviec');
                return;
            }
            
            $sql = "INSERT INTO loaicongviec (tenloai, mota) VALUES (:ten, :mota)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ten', $ten);
            $stmt->bindParam(':mota', $mota);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Thêm loại công việc thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
        }
        
        $this->redirect('danhmuc/loaicongviec');
    }
    
    public function sualoaicongviec($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            verify_csrf();
            
            $ten = trim($_POST['tenloai'] ?? '');
            $mota = trim($_POST['mota'] ?? '');
            
            if (empty($ten)) {
                $_SESSION['error'] = 'Vui lòng nhập tên loại công việc';
                $this->redirect('danhmuc/loaicongviec');
                return;
            }
            
            $sql = "UPDATE loaicongviec SET tenloai = :ten, mota = :mota WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ten', $ten);
            $stmt->bindParam(':mota', $mota);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Cập nhật loại công việc thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
        }
        
        $this->redirect('danhmuc/loaicongviec');
    }
    
    public function xoaloaicongviec($id) {
        verify_csrf();
        
        // Check if being used
        $check = $this->db->query("SELECT COUNT(*) FROM tintuyendung WHERE loaicongviec_id = $id")->fetchColumn();
        
        if ($check > 0) {
            $_SESSION['error'] = 'Không thể xóa loại công việc đang được sử dụng';
            $this->redirect('danhmuc/loaicongviec');
            return;
        }
        
        $sql = "DELETE FROM loaicongviec WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Xóa loại công việc thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        
        $this->redirect('danhmuc/loaicongviec');
    }

    public function tinhthanh() {
        $danhSach = $this->danhMucModel->layTinhThanh();
        
        require_once BASE_PATH . 'app/views/admin/danhmuc/tinhthanh.php';
    }
    
    public function themtinhthanh() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            verify_csrf();
            
            $ten = trim($_POST['tentinh'] ?? '');
            
            if (empty($ten)) {
                $_SESSION['error'] = 'Vui lòng nhập tên tỉnh/thành phố';
                $this->redirect('danhmuc/tinhthanh');
                return;
            }
            
            $sql = "INSERT INTO tinhthanh (tentinh) VALUES (:ten)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ten', $ten);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Thêm tỉnh/thành phố thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
        }
        
        $this->redirect('danhmuc/tinhthanh');
    }
    
    public function suatinhthanh($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            verify_csrf();
            
            $ten = trim($_POST['tentinh'] ?? '');
            
            if (empty($ten)) {
                $_SESSION['error'] = 'Vui lòng nhập tên tỉnh/thành phố';
                $this->redirect('danhmuc/tinhthanh');
                return;
            }
            
            $sql = "UPDATE tinhthanh SET tentinh = :ten WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ten', $ten);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Cập nhật tỉnh/thành phố thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
        }
        
        $this->redirect('danhmuc/tinhthanh');
    }
    
    public function xoatinhthanh($id) {
        verify_csrf();
        
        // Check if being used
        $check = $this->db->query("SELECT COUNT(*) FROM tintuyendung WHERE tinhthanh_id = $id")->fetchColumn();
        
        if ($check > 0) {
            $_SESSION['error'] = 'Không thể xóa tỉnh/thành phố đang được sử dụng';
            $this->redirect('danhmuc/tinhthanh');
            return;
        }
        
        $sql = "DELETE FROM tinhthanh WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Xóa tỉnh/thành phố thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        
        $this->redirect('danhmuc/tinhthanh');
    }

    public function cauhinh() {
        $config = $this->danhMucModel->layCauHinh();
        
        require_once BASE_PATH . 'app/views/admin/danhmuc/cauhinh.php';
    }

    public function capnhatcauhinh() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // CSRF Protection
            verify_csrf();
            
            $configs = [
                'max_cv_size' => (int)$_POST['max_cv_size'] * 1048576,
                'allowed_cv_types' => trim($_POST['allowed_cv_types']),
                'tin_expire_days' => (int)$_POST['tin_expire_days'],
                'max_tin_per_employer' => (int)$_POST['max_tin_per_employer']
            ];
            
            if ($this->danhMucModel->capNhatNhieuCauHinh($configs)) {
                $_SESSION['success'] = 'Cập nhật cấu hình thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
        }
        
        $this->redirect('danhmuc/cauhinh');
    }
}
