<?php


require_once BASE_PATH . 'app/models/TinTuyenDungModel.php';
require_once BASE_PATH . 'app/models/DonUngTuyenModel.php';

class TintuyendungController extends BaseController {
    
    private $tinModel;
    private $donModel;
    
    public function __construct() {
        parent::__construct();
        $this->tinModel = new TinTuyenDungModel();
        $this->donModel = new DonUngTuyenModel();
    }

    public function chitiet($id) {
        // Lấy thông tin tin tuyển dụng
        $tin = $this->tinModel->layChiTiet($id);
        
        if (!$tin) {
            $_SESSION['error'] = 'Tin tuyển dụng không tồn tại';
            $this->redirect('timkiem');
        }
        
        // Kiểm tra trạng thái
        if ($tin['trangthai'] != 'dangmo' || $tin['ngayhethan'] < date('Y-m-d')) {
            if (!isset($_SESSION['nguoidung_id']) || 
                ($_SESSION['vaitro'] != 'admin' && $tin['nguoidung_id'] != $_SESSION['nguoidung_id'])) {
                $_SESSION['error'] = 'Tin tuyển dụng không còn mở';
                $this->redirect('timkiem');
            }
        }
        
        // Tăng lượt xem
        $this->tinModel->tangLuotXem($id);
        
        // Kiểm tra đã ứng tuyển chưa
        $daUngTuyen = false;
        $cvHoSo = null;
        if (isset($_SESSION['nguoidung_id'])) {
            $daUngTuyen = $this->donModel->kiemTraUngTuyen($id, $_SESSION['nguoidung_id']);
            
            // Lấy CV từ hồ sơ ứng viên
            if ($_SESSION['vaitro'] === 'ungvien') {
                require_once BASE_PATH . 'app/models/ThongTinUngVienModel.php';
                $ungvienModel = new ThongTinUngVienModel();
                $hoSo = $ungvienModel->layThongTinTheoNguoiDung($_SESSION['nguoidung_id']);
                if ($hoSo && !empty($hoSo['cv_file'])) {
                    $cvHoSo = $hoSo['cv_file'];
                }
            }
        }
        
        // Lấy tin liên quan
        $tinLienQuan = $this->tinModel->timKiem('', $tin['nganhnghe_id'], null, null, null, 4, 0);
        
        $data = [
            'pageTitle' => $tin['tieude'],
            'tin' => $tin,
            'daUngTuyen' => $daUngTuyen,
            'cvHoSo' => $cvHoSo,
            'tinLienQuan' => $tinLienQuan
        ];
        
        $this->view('tintuyendung/chitiet', $data);
    }

    public function ungtuyen($id) {
        $this->checkRole(['ungvien']);
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tintuyendung/chitiet/' . $id);
        }
        
        // CSRF Protection
        verify_csrf();
        
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        // Kiểm tra đã ứng tuyển chưa
        if ($this->donModel->kiemTraUngTuyen($id, $nguoidungId)) {
            $_SESSION['error'] = 'Bạn đã ứng tuyển vào tin này rồi';
            $this->redirect('tintuyendung/chitiet/' . $id);
        }
        
        $thuungtuyen = $this->sanitize($_POST['thuungtuyen'] ?? '');
        
        // Validate
        if (empty($thuungtuyen)) {
            $_SESSION['error'] = 'Vui lòng nhập thư ứng tuyển';
            $this->redirect('tintuyendung/chitiet/' . $id);
        }
        
        // Xử lý CV
        $cvFile = '';
        $cvType = $_POST['cv_type'] ?? 'new';
        
        if ($cvType === 'existing' && !empty($_POST['cv_existing'])) {
            // Sử dụng CV từ hồ sơ
            $cvFile = $this->sanitize($_POST['cv_existing']);
            
            // Kiểm tra file có tồn tại không
            if (!file_exists(CV_PATH . $cvFile)) {
                $_SESSION['error'] = 'CV trong hồ sơ không tồn tại. Vui lòng upload CV mới.';
                $this->redirect('tintuyendung/chitiet/' . $id);
            }
        } else {
            // Upload CV mới
            if (!isset($_FILES['cv']) || $_FILES['cv']['error'] != 0) {
                $_SESSION['error'] = 'Vui lòng tải lên CV';
                $this->redirect('tintuyendung/chitiet/' . $id);
            }
            
            $uploadResult = $this->uploadFile($_FILES['cv'], CV_PATH, ALLOWED_CV_TYPES);
            
            if (!$uploadResult['success']) {
                $_SESSION['error'] = $uploadResult['message'];
                $this->redirect('tintuyendung/chitiet/' . $id);
            }
            
            $cvFile = $uploadResult['filename'];
        }
        
        // Tạo đơn ứng tuyển
        $data = [
            'tintuyendung_id' => $id,
            'nguoidung_id' => $nguoidungId,
            'cv_file' => $cvFile,
            'thuungtuyen' => $thuungtuyen
        ];
        
        if ($this->donModel->taoDon($data)) {
            $_SESSION['success'] = 'Ứng tuyển thành công! Nhà tuyển dụng sẽ xem xét hồ sơ của bạn.';
            $this->redirect('ungvien/donungtuyen');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
            $this->redirect('tintuyendung/chitiet/' . $id);
        }
    }
}
?>
