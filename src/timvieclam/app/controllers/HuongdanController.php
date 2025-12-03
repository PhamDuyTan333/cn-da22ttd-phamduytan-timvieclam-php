<?php



class HuongdanController extends BaseController {

    public function ungvien() {
        $data = [
            'pageTitle' => 'Hướng dẫn cho Ứng viên'
        ];
        
        $this->view('huongdan/ungvien', $data);
    }

    public function nhatuyendung() {
        $data = [
            'pageTitle' => 'Hướng dẫn cho Nhà tuyển dụng'
        ];
        
        $this->view('huongdan/nhatuyendung', $data);
    }
}
?>
