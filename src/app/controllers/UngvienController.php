<?php



class UngvienController extends BaseController {
    
    private $db;
    
    public function __construct() {
        parent::__construct();
        $this->checkRole(['ungvien', 'choduyet']);
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        $this->redirect('ungvien/hoso');
    }

    public function hoso() {
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        // Lấy thông tin người dùng
        $sql = "SELECT nd.*, tuv.* 
                FROM nguoidung nd
                LEFT JOIN thongtinungvien tuv ON nd.id = tuv.nguoidung_id
                WHERE nd.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $nguoidungId);
        $stmt->execute();
        $user = $stmt->fetch();
        
        $data = [
            'pageTitle' => 'Hồ sơ của tôi',
            'user' => $user
        ];
        
        $this->view('ungvien/hoso', $data);
    }

    public function capnhathoso() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('ungvien/hoso');
        }
        
        // CSRF Protection
        verify_csrf();
        
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        // Lấy dữ liệu
        $hoten = $this->sanitize($_POST['hoten'] ?? '');
        $sodienthoai = $this->sanitize($_POST['sodienthoai'] ?? '');
        $diachi = $this->sanitize($_POST['diachi'] ?? '');
        $ngaysinh = $this->sanitize($_POST['ngaysinh'] ?? '');
        $gioitinh = $this->sanitize($_POST['gioitinh'] ?? '');
        $trinhdo = $this->sanitize($_POST['trinhdo'] ?? '');
        $kinhnghiem = $this->sanitize($_POST['kinhnghiem'] ?? '');
        $kynang = $this->sanitize($_POST['kynang'] ?? '');
        $muctieu = $this->sanitize($_POST['muctieu'] ?? '');
        
        try {
            // Cập nhật bảng nguoidung
            $sql = "UPDATE nguoidung 
                    SET hoten = :hoten, 
                        sodienthoai = :sodienthoai, 
                        diachi = :diachi
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':hoten', $hoten);
            $stmt->bindParam(':sodienthoai', $sodienthoai);
            $stmt->bindParam(':diachi', $diachi);
            $stmt->bindParam(':id', $nguoidungId);
            $stmt->execute();
            
            // Cập nhật bảng thongtinungvien
            $sql = "UPDATE thongtinungvien 
                    SET ngaysinh = :ngaysinh,
                        gioitinh = :gioitinh,
                        trinhdo = :trinhdo,
                        kinhnghiem = :kinhnghiem,
                        kynang = :kynang,
                        muctieucanhan = :muctieu
                    WHERE nguoidung_id = :nguoidung_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ngaysinh', $ngaysinh);
            $stmt->bindParam(':gioitinh', $gioitinh);
            $stmt->bindParam(':trinhdo', $trinhdo);
            $stmt->bindParam(':kinhnghiem', $kinhnghiem);
            $stmt->bindParam(':kynang', $kynang);
            $stmt->bindParam(':muctieu', $muctieu);
            $stmt->bindParam(':nguoidung_id', $nguoidungId);
            $stmt->execute();
            
            // Upload CV nếu có
            if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
                $uploadResult = $this->uploadFile($_FILES['cv'], CV_PATH, ALLOWED_CV_TYPES);
                
                if ($uploadResult['success']) {
                    // Xóa CV cũ nếu có
                    $sqlOld = "SELECT cv_file FROM thongtinungvien WHERE nguoidung_id = :nguoidung_id";
                    $stmtOld = $this->db->prepare($sqlOld);
                    $stmtOld->bindParam(':nguoidung_id', $nguoidungId);
                    $stmtOld->execute();
                    $oldCV = $stmtOld->fetch();
                    
                    if ($oldCV && $oldCV['cv_file']) {
                        $this->deleteFile(CV_PATH . $oldCV['cv_file']);
                    }
                    
                    // Cập nhật CV mới
                    $sql = "UPDATE thongtinungvien SET cv_file = :cv WHERE nguoidung_id = :nguoidung_id";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':cv', $uploadResult['filename']);
                    $stmt->bindParam(':nguoidung_id', $nguoidungId);
                    $stmt->execute();
                }
            }
            
            // Upload avatar nếu có
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $uploadResult = $this->uploadFile($_FILES['avatar'], AVATAR_PATH, ALLOWED_IMAGE_TYPES);
                
                if ($uploadResult['success']) {
                    $sql = "UPDATE nguoidung SET avatar = :avatar WHERE id = :id";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':avatar', $uploadResult['filename']);
                    $stmt->bindParam(':id', $nguoidungId);
                    $stmt->execute();
                    
                    $_SESSION['avatar'] = $uploadResult['filename'];
                }
            }
            
            $_SESSION['hoten'] = $hoten;
            $_SESSION['success'] = 'Cập nhật hồ sơ thành công';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }
        
        $this->redirect('ungvien/hoso');
    }

    public function donungtuyen() {
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        // Lấy danh sách đơn
        $sql = "SELECT d.*, t.tieude, t.nganhnghe_id, n.tennganh, 
                tt.tentinh, m.tenmucluong, nhatd.tencongty, nhatd.logo
                FROM donungtuyen d
                INNER JOIN tintuyendung t ON d.tintuyendung_id = t.id
                LEFT JOIN nganhnghe n ON t.nganhnghe_id = n.id
                LEFT JOIN tinhthanh tt ON t.tinhthanh_id = tt.id
                LEFT JOIN mucluong m ON t.mucluong_id = m.id
                LEFT JOIN nguoidung nd ON t.nguoidung_id = nd.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id
                WHERE d.nguoidung_id = :nguoidung_id
                ORDER BY d.ngaynop DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nguoidung_id', $nguoidungId);
        $stmt->execute();
        $danhSachDon = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $data = [
            'pageTitle' => 'Đơn ứng tuyển của tôi',
            'danhSachDon' => $danhSachDon
        ];
        
        $this->view('ungvien/donungtuyen', $data);
    }

    public function yeucautuyendung() {
        // Kiểm tra nếu đã là nhà tuyển dụng hoặc đang chờ duyệt
        if ($_SESSION['vaitro'] == 'tuyendung') {
            $_SESSION['info'] = 'Bạn đã là nhà tuyển dụng';
            $this->redirect('nhatuyendung');
        }
        
        if ($_SESSION['vaitro'] == 'choduyet') {
            $_SESSION['info'] = 'Yêu cầu của bạn đang chờ phê duyệt';
            $this->redirect('');
        }
        
        $data = [
            'pageTitle' => 'Trở thành nhà tuyển dụng'
        ];
        
        $this->view('ungvien/yeucautuyendung', $data);
    }

    public function guiyeucau() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('ungvien/yeucautuyendung');
        }
        
        // CSRF Protection
        verify_csrf();
        
        $nguoidungId = $_SESSION['nguoidung_id'];
        
        $tencongty = $this->sanitize($_POST['tencongty'] ?? '');
        $masothue = $this->sanitize($_POST['masothue'] ?? '');
        $emailcongty = $this->sanitize($_POST['emailcongty'] ?? '');
        $diachi_congty = $this->sanitize($_POST['diachi_congty'] ?? '');
        $website = $this->sanitize($_POST['website'] ?? '');
        $quymo = $this->sanitize($_POST['quymo'] ?? '');
        $linhvuc = $this->sanitize($_POST['linhvuc'] ?? '');
        $mota = $this->sanitize($_POST['mota'] ?? '');
        $lydoyeucau = $this->sanitize($_POST['lydoyeucau'] ?? '');
        
        // Validate
        $errors = [];
        
        if (empty($tencongty)) $errors[] = 'Vui lòng nhập tên công ty';
        if (empty($emailcongty)) $errors[] = 'Vui lòng nhập email công ty';
        if (empty($lydoyeucau)) $errors[] = 'Vui lòng nhập lý do yêu cầu';
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $this->redirect('ungvien/yeucautuyendung');
        }
        
        try {
            // Tạo hoặc cập nhật thông tin nhà tuyển dụng
            $sql = "INSERT INTO thongtinnhatuyendung 
                    (nguoidung_id, tencongty, masothue, diachi_congty, website, 
                     email_congty, quymo, linhvuc, mota, lydoyeucau)
                    VALUES (:nguoidung_id, :tencongty, :masothue, :diachi_congty, :website,
                            :emailcongty, :quymo, :linhvuc, :mota, :lydoyeucau)
                    ON DUPLICATE KEY UPDATE
                    tencongty = VALUES(tencongty), 
                    masothue = VALUES(masothue), 
                    diachi_congty = VALUES(diachi_congty),
                    website = VALUES(website), 
                    email_congty = VALUES(email_congty), 
                    quymo = VALUES(quymo),
                    linhvuc = VALUES(linhvuc), 
                    mota = VALUES(mota), 
                    lydoyeucau = VALUES(lydoyeucau)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':nguoidung_id', $nguoidungId);
            $stmt->bindParam(':tencongty', $tencongty);
            $stmt->bindParam(':masothue', $masothue);
            $stmt->bindParam(':diachi_congty', $diachi_congty);
            $stmt->bindParam(':website', $website);
            $stmt->bindParam(':emailcongty', $emailcongty);
            $stmt->bindParam(':quymo', $quymo);
            $stmt->bindParam(':linhvuc', $linhvuc);
            $stmt->bindParam(':mota', $mota);
            $stmt->bindParam(':lydoyeucau', $lydoyeucau);
            $stmt->execute();
            
            // Upload logo nếu có
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                $uploadResult = $this->uploadFile($_FILES['logo'], LOGO_PATH, ALLOWED_IMAGE_TYPES);
                
                if ($uploadResult['success']) {
                    $sql = "UPDATE thongtinnhatuyendung SET logo = :logo WHERE nguoidung_id = :nguoidung_id";
                    $stmt = $this->db->prepare($sql);
                    $stmt->bindParam(':logo', $uploadResult['filename']);
                    $stmt->bindParam(':nguoidung_id', $nguoidungId);
                    $stmt->execute();
                }
            }
            
            // Cập nhật vai trò thành chờ duyệt
            $sql = "UPDATE nguoidung SET vaitro = 'choduyet' WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $nguoidungId);
            $stmt->execute();
            
            $_SESSION['vaitro'] = 'choduyet';
            $_SESSION['success'] = 'Gửi yêu cầu thành công! Vui lòng chờ quản trị viên phê duyệt.';
            $this->redirect('');
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            $this->redirect('ungvien/yeucautuyendung');
        }
    }

    public function thaydoicv() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        // CSRF Protection
        try {
            verify_csrf();
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'CSRF token invalid']);
            exit;
        }
        
        $nguoidungId = $_SESSION['nguoidung_id'];
        $donId = $_POST['don_id'] ?? 0;
        
        if (!$donId) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn ứng tuyển']);
            exit;
        }
        
        try {
            // Kiểm tra quyền sở hữu đơn ứng tuyển và trạng thái
            $sql = "SELECT id, cv_file, trangthai FROM donungtuyen WHERE id = :id AND nguoidung_id = :nguoidung_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $donId);
            $stmt->bindParam(':nguoidung_id', $nguoidungId);
            $stmt->execute();
            $don = $stmt->fetch();
            
            if (!$don) {
                echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thay đổi CV này']);
                exit;
            }
            
            // Chỉ cho phép thay đổi CV khi trạng thái là 'moi'
            if ($don['trangthai'] !== 'moi') {
                echo json_encode(['success' => false, 'message' => 'Chỉ có thể thay đổi CV khi đơn ứng tuyển ở trạng thái mới']);
                exit;
            }
            
            // Xử lý upload file mới
            if (!isset($_FILES['new_cv']) || $_FILES['new_cv']['error'] !== UPLOAD_ERR_OK) {
                echo json_encode(['success' => false, 'message' => 'Vui lòng chọn file CV']);
                exit;
            }
            
            $file = $_FILES['new_cv'];
            $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            $maxSize = 5 * 1024 * 1024; // 5MB
            
            // Kiểm tra loại file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            
            if (!in_array($mimeType, $allowedTypes)) {
                echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận file PDF, DOC, DOCX']);
                exit;
            }
            
            // Kiểm tra kích thước
            if ($file['size'] > $maxSize) {
                echo json_encode(['success' => false, 'message' => 'File quá lớn. Tối đa 5MB']);
                exit;
            }
            
            // Tạo tên file mới
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = 'cv_' . $nguoidungId . '_' . time() . '.' . $extension;
            $uploadPath = BASE_PATH . 'public/uploads/cv/' . $newFileName;
            
            // Upload file mới
            if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                echo json_encode(['success' => false, 'message' => 'Không thể upload file']);
                exit;
            }
            
            // Xóa file cũ nếu tồn tại
            if ($don['cv_file']) {
                $oldFilePath = BASE_PATH . 'public/uploads/cv/' . $don['cv_file'];
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            
            // Cập nhật database
            $sql = "UPDATE donungtuyen SET cv_file = :cv_file WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':cv_file', $newFileName);
            $stmt->bindParam(':id', $donId);
            $stmt->execute();
            
            echo json_encode(['success' => true, 'message' => 'Thay đổi CV thành công']);
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
        exit;
    }
}
?>
