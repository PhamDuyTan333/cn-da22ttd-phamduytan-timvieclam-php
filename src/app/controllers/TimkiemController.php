<?php


require_once BASE_PATH . 'app/models/TinTuyenDungModel.php';
require_once BASE_PATH . 'app/models/DanhMucModel.php';

class TimkiemController extends BaseController {
    
    private $tinModel;
    private $danhMucModel;
    
    public function __construct() {
        parent::__construct();
        $this->tinModel = new TinTuyenDungModel();
        $this->danhMucModel = new DanhMucModel();
    }

    public function index() {
        // Lấy tham số tìm kiếm
        $tukhoa = $this->sanitize($_GET['tukhoa'] ?? '');
        // Hỗ trợ cả 'nganh' và 'nganhnghe' parameter
        $nganhnghe = isset($_GET['nganhnghe']) ? (int)$_GET['nganhnghe'] : (isset($_GET['nganh']) ? (int)$_GET['nganh'] : null);
        $tinhthanh = isset($_GET['tinhthanh']) ? (int)$_GET['tinhthanh'] : null;
        $mucluong = isset($_GET['mucluong']) ? (int)$_GET['mucluong'] : null;
        $loaicongviec = isset($_GET['loaicongviec']) ? (int)$_GET['loaicongviec'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        $recordsPerPage = 12;
        $offset = ($page - 1) * $recordsPerPage;
        
        // Tìm kiếm
        $ketQua = $this->tinModel->timKiem($tukhoa, $nganhnghe, $tinhthanh, $mucluong, $loaicongviec, $recordsPerPage, $offset);
        $tongKetQua = $this->tinModel->demTimKiem($tukhoa, $nganhnghe, $tinhthanh, $mucluong, $loaicongviec);
        
        // Phân trang
        $pagination = $this->paginate($tongKetQua, $page, $recordsPerPage);
        
        // Lấy danh mục
        $danhMuc = $this->layDanhMuc();
        
        $data = [
            'pageTitle' => 'Tìm việc làm',
            'ketQua' => $ketQua,
            'tongKetQua' => $tongKetQua,
            'pagination' => $pagination,
            'danhMuc' => $danhMuc,
            'filter' => [
                'tukhoa' => $tukhoa,
                'nganhnghe' => $nganhnghe,
                'tinhthanh' => $tinhthanh,
                'mucluong' => $mucluong,
                'loaicongviec' => $loaicongviec
            ]
        ];
        
        $this->view('timkiem/index', $data);
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
