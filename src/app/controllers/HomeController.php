<?php


require_once BASE_PATH . 'app/models/TinTuyenDungModel.php';
require_once BASE_PATH . 'app/models/DanhMucModel.php';

class HomeController extends BaseController {
    
    private $tinModel;
    private $danhMucModel;
    
    public function __construct() {
        parent::__construct();
        $this->tinModel = new TinTuyenDungModel();
        $this->danhMucModel = new DanhMucModel();
    }
    
    public function index() {
        $tinTuyenDung = $this->tinModel->layDanhSachMoiNhat(12);
        $nganhNghe = $this->danhMucModel->layNganhNghe(8);
        $tinhThanh = $this->danhMucModel->layTinhThanh();
        $stats = $this->danhMucModel->layThongKe();
        $tuKhoaPhoBien = $this->danhMucModel->layNganhNghe(5);
        
        $data = [
            'pageTitle' => 'Trang chủ',
            'tinTuyenDung' => $tinTuyenDung,
            'nganhNghe' => $nganhNghe,
            'tinhThanh' => $tinhThanh,
            'stats' => $stats,
            'tuKhoaPhoBien' => $tuKhoaPhoBien
        ];
        
        $this->view('home/index', $data);
    }
}
?>
