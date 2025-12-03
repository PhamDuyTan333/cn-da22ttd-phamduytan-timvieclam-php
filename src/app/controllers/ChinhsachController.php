<?php



class ChinhsachController extends BaseController {

    public function dieukhoansudung() {
        $data = [
            'pageTitle' => 'Điều khoản sử dụng'
        ];
        
        $this->view('chinhsach/dieukhoansudung', $data);
    }

    public function chinhsachbaomat() {
        $data = [
            'pageTitle' => 'Chính sách bảo mật'
        ];
        
        $this->view('chinhsach/chinhsachbaomat', $data);
    }
}
?>
