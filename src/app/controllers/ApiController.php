<?php


require_once BASE_PATH . 'app/models/ApiModel.php';

class ApiController extends BaseController {
    private $apiModel;
    
    public function __construct() {
        parent::__construct();
        $this->apiModel = new ApiModel();
    }
    
    public function thongke() {
        header('Content-Type: application/json');
        
        $vaitro = $this->apiModel->layThongKeVaiTro();
        $tin = $this->apiModel->layThongKeTin();
        $don = $this->apiModel->layThongKeDon();
        $nganh = $this->apiModel->layTopNganhNghe(10);
        $xuhuong = $this->apiModel->layXuHuongTheoNgay(30);
        
        $response = [
            'vaitro' => $vaitro,
            'tin' => $tin,
            'don' => $don,
            'nganh' => $nganh,
            'xuhuong' => $xuhuong
        ];
        
        echo json_encode($response);
        exit;
    }
}
?>
