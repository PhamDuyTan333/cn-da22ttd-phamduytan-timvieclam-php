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
        
        try {
            $vaitro = $this->apiModel->layThongKeVaiTro();
            $tin = $this->apiModel->layThongKeTin();
            $don = $this->apiModel->layThongKeDon();
            $nganh = $this->apiModel->layTopNganhNghe(10);
            $xuhuong = $this->apiModel->layXuHuongTheoNgay(90); // Tăng từ 30 lên 90 ngày
            
            $response = [
                'success' => true,
                'vaitro' => $vaitro,
                'tin' => $tin,
                'don' => $don,
                'nganh' => $nganh,
                'xuhuong' => $xuhuong
            ];
            
            echo json_encode($response);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit;
    }
    
    public function nguoidungtheongy() {
        header('Content-Type: application/json');
        
        try {
            $type = $_GET['type'] ?? 'day'; // day, week, month
            $data = $this->apiModel->layNguoiDungTheoKhoangThoiGian($type);
            
            echo json_encode([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit;
    }
}
?>
