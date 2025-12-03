<?php


require_once BASE_PATH . 'app/models/TinTuyenDungModel.php';
require_once BASE_PATH . 'app/models/DonUngTuyenModel.php';
require_once BASE_PATH . 'app/models/ThongTinNhaTuyenDungModel.php';
require_once BASE_PATH . 'app/models/DanhMucModel.php';

class NhatuyendungController extends BaseController {
    
    private $tinModel;
    private $donModel;
    private $thongTinModel;
    private $danhMucModel;
    
    public function __construct() {
        parent::__construct();
        $this->checkRole(['tuyendung']);
        $this->tinModel = new TinTuyenDungModel();
        $this->donModel = new DonUngTuyenModel();
        $this->thongTinModel = new ThongTinNhaTuyenDungModel();
        $this->danhMucModel = new DanhMucModel();
    }

    public function index() {
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        $stats = $this->thongTinModel->layThongKe($nguoidungId);
        $danhSachTin = $this->tinModel->layDanhSachTheoNguoiDung($nguoidungId, 5);
        
        $data = [
            'pageTitle' => 'Quản lý tuyển dụng',
            'stats' => $stats,
            'tongdon' => $stats['tongdon'],
            'danhSachTin' => $danhSachTin
        ];
        
        $this->view('nhatuyendung/index', $data);
    }

    public function danhsachtin() {
        $nguoidungId = $_SESSION['nguoidung_id'];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $recordsPerPage = 10;
        $offset = ($page - 1) * $recordsPerPage;
        
        $total = $this->tinModel->demTheoNguoiDung($nguoidungId);
        $danhSachTin = $this->tinModel->layDanhSachTheoNguoiDung($nguoidungId, $recordsPerPage, $offset);
        $pagination = $this->paginate($total, $page, $recordsPerPage);
        
        $data = [
            'pageTitle' => 'Danh sách tin tuyển dụng',
            'danhSach' => $danhSachTin,
            'filterTrangthai' => isset($_GET['trangthai']) ? $_GET['trangthai'] : null,
            'pagination' => $pagination
        ];
        
        $this->view('nhatuyendung/danhsachtin', $data);
    }

    public function dangtin() {
        $danhMuc = $this->layDanhMuc();
        
        $data = [
            'pageTitle' => 'Đăng tin tuyển dụng',
            'danhMuc' => $danhMuc
        ];
        
        $this->view('nhatuyendung/dangtin', $data);
    }

    public function xulyDangtin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('nhatuyendung/dangtin');
        }
        
        // CSRF Protection
        verify_csrf();
        
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        // Lấy dữ liệu
        $tieude = $this->sanitize($_POST['tieude'] ?? '');
        $nganhnghe_id = $this->sanitize($_POST['nganhnghe_id'] ?? '');
        $mucluong_id = $this->sanitize($_POST['mucluong_id'] ?? '');
        $loaicongviec_id = $this->sanitize($_POST['loaicongviec_id'] ?? '');
        $tinhthanh_id = $this->sanitize($_POST['tinhthanh_id'] ?? '');
        $diachilamviec = $this->sanitize($_POST['diachilamviec'] ?? '');
        $soluong = $this->sanitize($_POST['soluong'] ?? 1);
        $gioitinh_yc = $this->sanitize($_POST['gioitinh_yc'] ?? 'khongphanbiet');
        $mota = $this->sanitize($_POST['mota'] ?? '');
        $yeucau = $this->sanitize($_POST['yeucau'] ?? '');
        $quyenloi = $this->sanitize($_POST['quyenloi'] ?? '');
        $hannop = $this->sanitize($_POST['hannop'] ?? '');
        
        // Validate
        $errors = [];
        
        if (empty($tieude)) $errors[] = 'Vui lòng nhập tiêu đề';
        if (empty($nganhnghe_id)) $errors[] = 'Vui lòng chọn ngành nghề';
        if (empty($mota)) $errors[] = 'Vui lòng nhập mô tả công việc';
        if (empty($yeucau)) $errors[] = 'Vui lòng nhập yêu cầu ứng viên';
        if (empty($hannop)) $errors[] = 'Vui lòng chọn hạn nộp hồ sơ';
        
        // Kiểm tra ngày hết hạn
        if (!empty($hannop) && strtotime($hannop) < strtotime(date('Y-m-d'))) {
            $errors[] = 'Hạn nộp hồ sơ phải lớn hơn ngày hiện tại';
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['old_input'] = $_POST;
            $this->redirect('nhatuyendung/dangtin');
        }
        
        // Tạo tin
        $dataTin = [
            'nguoidung_id' => $nguoidungId,
            'tieude' => $tieude,
            'nganhnghe_id' => $nganhnghe_id,
            'mucluong_id' => $mucluong_id ?: null,
            'loaicongviec_id' => $loaicongviec_id ?: null,
            'tinhthanh_id' => $tinhthanh_id ?: null,
            'diachilamviec' => $diachilamviec,
            'soluong' => $soluong,
            'gioitinh_yc' => $gioitinh_yc,
            'mota' => $mota,
            'yeucau' => $yeucau,
            'quyenloi' => $quyenloi,
            'ngayhethan' => $hannop
        ];
        
        if ($this->tinModel->taoTin($dataTin)) {
            $_SESSION['success'] = 'Đăng tin thành công! Tin của bạn đang chờ phê duyệt.';
            $this->redirect('nhatuyendung/danhsachtin');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
            $this->redirect('nhatuyendung/dangtin');
        }
    }

    public function suatin($id) {
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        // Lấy thông tin tin
        $tin = $this->tinModel->layChiTiet($id);
        
        if (!$tin || $tin['nguoidung_id'] != $nguoidungId) {
            $_SESSION['error'] = 'Tin tuyển dụng không tồn tại';
            $this->redirect('nhatuyendung/danhsachtin');
        }
        
        $danhMuc = $this->layDanhMuc();
        
        $data = [
            'pageTitle' => 'Sửa tin tuyển dụng',
            'tin' => $tin,
            'danhMuc' => $danhMuc
        ];
        
        $this->view('nhatuyendung/suatin', $data);
    }

    public function xulySuatin($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('nhatuyendung/suatin/' . $id);
        }
        
        // CSRF Protection
        verify_csrf();
        
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        // Kiểm tra quyền
        $tin = $this->tinModel->layChiTiet($id);
        if (!$tin || $tin['nguoidung_id'] != $nguoidungId) {
            $_SESSION['error'] = 'Bạn không có quyền sửa tin này';
            $this->redirect('nhatuyendung/danhsachtin');
        }
        
        // Lấy dữ liệu (giống dangtin)
        $dataTin = [
            'tieude' => $this->sanitize($_POST['tieude'] ?? ''),
            'nganhnghe_id' => $this->sanitize($_POST['nganhnghe_id'] ?? ''),
            'mucluong_id' => $this->sanitize($_POST['mucluong_id'] ?? '') ?: null,
            'loaicongviec_id' => $this->sanitize($_POST['loaicongviec_id'] ?? '') ?: null,
            'tinhthanh_id' => $this->sanitize($_POST['tinhthanh_id'] ?? '') ?: null,
            'diachilamviec' => $this->sanitize($_POST['diachilamviec'] ?? ''),
            'soluong' => $this->sanitize($_POST['soluong'] ?? 1),
            'gioitinh_yc' => $this->sanitize($_POST['gioitinh_yc'] ?? 'khongphanbiet'),
            'mota' => $this->sanitize($_POST['mota'] ?? ''),
            'yeucau' => $this->sanitize($_POST['yeucau'] ?? ''),
            'quyenloi' => $this->sanitize($_POST['quyenloi'] ?? ''),
            'ngayhethan' => $this->sanitize($_POST['hannop'] ?? '')
        ];
        
        if ($this->tinModel->capNhat($id, $dataTin)) {
            $_SESSION['success'] = 'Cập nhật tin thành công';
            $this->redirect('nhatuyendung/danhsachtin');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
            $this->redirect('nhatuyendung/suatin/' . $id);
        }
    }

    public function xoatin($id) {
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        // Kiểm tra quyền
        $tin = $this->tinModel->layChiTiet($id);
        if (!$tin || $tin['nguoidung_id'] != $nguoidungId) {
            $_SESSION['error'] = 'Bạn không có quyền xóa tin này';
            $this->redirect('nhatuyendung/danhsachtin');
        }
        
        if ($this->tinModel->xoa($id)) {
            $_SESSION['success'] = 'Xóa tin thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        
        $this->redirect('nhatuyendung/danhsachtin');
    }

    public function giahan($id) {
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        // Kiểm tra quyền sở hữu tin
        $tin = $this->tinModel->layChiTiet($id);
        if (!$tin || $tin['nguoidung_id'] != $nguoidungId) {
            $_SESSION['error'] = 'Bạn không có quyền gia hạn tin này';
            $this->redirect('nhatuyendung/danhsachtin');
            return;
        }
        
        // Gia hạn thêm 30 ngày từ ngày hết hạn hiện tại
        $ngayHetHanCu = $tin['ngayhethan'];
        $ngayHetHanMoi = date('Y-m-d', strtotime($ngayHetHanCu . ' +30 days'));
        
        // Nếu tin đã hết hạn, gia hạn từ hôm nay
        if (strtotime($ngayHetHanCu) < time()) {
            $ngayHetHanMoi = date('Y-m-d', strtotime('+30 days'));
        }
        
        if ($this->tinModel->giaHan($id, $ngayHetHanMoi)) {
            $_SESSION['success'] = 'Gia hạn tin thành công đến ngày ' . date('d/m/Y', strtotime($ngayHetHanMoi)) . '. Tin đang chờ admin duyệt lại.';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi gia hạn tin';
        }
        
        $this->redirect('nhatuyendung/danhsachtin');
    }

    public function danhsachungvien($tinId = null) {
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        if ($tinId) {
            // Xem ứng viên của một tin cụ thể
            $tin = $this->tinModel->layChiTiet($tinId);
            if (!$tin || $tin['nguoidung_id'] != $nguoidungId) {
                $_SESSION['error'] = 'Bạn không có quyền xem danh sách này';
                $this->redirect('nhatuyendung/danhsachtin');
            }
            
            // Lấy filter trạng thái từ URL
            $filterTrangthai = isset($_GET['trangthai']) ? $_GET['trangthai'] : '';
            
            // Lấy danh sách ứng viên với filter
            $danhSachUngVien = $this->donModel->layDanhSachTheoTin($tinId, $filterTrangthai);
            $thongKe = $this->donModel->thongKeTheoTin($tinId);
            
            $data = [
                'pageTitle' => 'Danh sách ứng viên - ' . $tin['tieude'],
                'tinTuyenDung' => $tin,
                'thongKe' => $thongKe,
                'danhSach' => $danhSachUngVien,
                'filterTrangthai' => $filterTrangthai
            ];
        } else {
            // Xem tất cả ứng viên của nhà tuyển dụng
            $filterTrangthai = isset($_GET['trangthai']) ? $_GET['trangthai'] : '';
            
            // Lấy tất cả đơn ứng tuyển của nhà tuyển dụng này
            $danhSachUngVien = $this->donModel->layDanhSachTheoNhaTuyenDung($nguoidungId, $filterTrangthai);
            
            $data = [
                'pageTitle' => 'Tất cả ứng viên',
                'tinTuyenDung' => null,
                'danhSach' => $danhSachUngVien,
                'filterTrangthai' => $filterTrangthai
            ];
        }
        
        $this->view('nhatuyendung/danhsachungvien', $data);
    }

    public function chitietungvien($donId) {
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        // Lấy thông tin đơn
        $don = $this->donModel->layChiTiet($donId);
        
        if (!$don || $don['nhatuyendung_id'] != $nguoidungId) {
            $_SESSION['error'] = 'Bạn không có quyền xem hồ sơ này';
            $this->redirect('nhatuyendung');
        }
        
        $data = [
            'pageTitle' => 'Hồ sơ ứng viên - ' . $don['hoten'],
            'don' => $don
        ];
        
        $this->view('nhatuyendung/chitietungvien', $data);
    }

    public function capnhattrangthai($donId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('nhatuyendung');
        }
        
        // CSRF Protection
        verify_csrf();
        
        $nguoidungId = $_SESSION['nguoidung_id'];
        $trangthai = $this->sanitize($_POST['trangthai'] ?? '');
        $ghichu = $this->sanitize($_POST['ghichu'] ?? '');
        
        // Kiểm tra quyền
        $don = $this->donModel->layChiTiet($donId);
        if (!$don || $don['nhatuyendung_id'] != $nguoidungId) {
            $_SESSION['error'] = 'Bạn không có quyền thực hiện';
            $this->redirect('nhatuyendung');
        }
        
        if ($this->donModel->capNhatTrangThai($donId, $trangthai, $ghichu)) {
            $_SESSION['success'] = 'Cập nhật trạng thái thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        
        $this->redirect('nhatuyendung/chitietungvien/' . $donId);
    }

    private function layDanhMuc() {
        return [
            'nganhnghe' => $this->danhMucModel->layNganhNghe(),
            'tinhthanh' => $this->danhMucModel->layTinhThanh(),
            'mucluong' => $this->danhMucModel->layMucLuong(),
            'loaicongviec' => $this->danhMucModel->layLoaiCongViec()
        ];
    }
}
?>
