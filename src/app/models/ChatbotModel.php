<?php

class ChatbotModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function layLichSuTinNhan($sessionId, $limit = 3) {
        $sql = "SELECT message, response FROM chatbot_messages 
                WHERE session_id = :session_id AND message_type = 'user'
                ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':session_id', $sessionId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layLichSuHoiThoai($sessionId) {
        $sql = "SELECT * FROM chatbot_messages 
                WHERE session_id = :session_id 
                ORDER BY created_at ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':session_id', $sessionId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function layLichSuTheoNguoiDung($nguoidungId, $limit = 50) {
        $sql = "SELECT * FROM chatbot_messages 
                WHERE nguoidung_id = :nguoidung_id 
                ORDER BY id ASC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nguoidung_id', $nguoidungId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function xoaHoiThoai($sessionId) {
        try {
            $this->db->beginTransaction();
            
            $sqlDeleteMessages = "DELETE FROM chatbot_messages WHERE session_id = :session_id";
            $stmtDeleteMessages = $this->db->prepare($sqlDeleteMessages);
            $stmtDeleteMessages->bindParam(':session_id', $sessionId);
            $stmtDeleteMessages->execute();
            
            $sqlDeleteConv = "DELETE FROM chatbot_conversations WHERE session_id = :session_id";
            $stmtDeleteConv = $this->db->prepare($sqlDeleteConv);
            $stmtDeleteConv->bindParam(':session_id', $sessionId);
            $stmtDeleteConv->execute();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    public function xoaHoiThoaiTheoNguoiDung($nguoidungId) {
        try {
            $this->db->beginTransaction();
            
            // Xóa tất cả tin nhắn của người dùng
            $sqlDeleteMessages = "DELETE FROM chatbot_messages WHERE nguoidung_id = :nguoidung_id";
            $stmtDeleteMessages = $this->db->prepare($sqlDeleteMessages);
            $stmtDeleteMessages->bindParam(':nguoidung_id', $nguoidungId, PDO::PARAM_INT);
            $stmtDeleteMessages->execute();
            
            // Xóa các cuộc hội thoại của người dùng
            $sqlDeleteConv = "DELETE FROM chatbot_conversations WHERE nguoidung_id = :nguoidung_id";
            $stmtDeleteConv = $this->db->prepare($sqlDeleteConv);
            $stmtDeleteConv->bindParam(':nguoidung_id', $nguoidungId, PDO::PARAM_INT);
            $stmtDeleteConv->execute();
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Xóa hội thoại theo người dùng lỗi: " . $e->getMessage());
            return false;
        }
    }

    public function luuTinNhan($sessionId, $message, $response, $nguoidungId = null) {
        $sqlCheck = "SELECT id FROM chatbot_conversations WHERE session_id = :session_id";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bindParam(':session_id', $sessionId);
        $stmtCheck->execute();
        $conversation = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        
        if (!$conversation) {
            $sqlConv = "INSERT INTO chatbot_conversations (session_id, nguoidung_id, ngaytao) 
                       VALUES (:session_id, :nguoidung_id, NOW())";
            $stmtConv = $this->db->prepare($sqlConv);
            $stmtConv->bindParam(':session_id', $sessionId);
            $stmtConv->bindParam(':nguoidung_id', $nguoidungId, PDO::PARAM_INT);
            $stmtConv->execute();
            $conversationId = $this->db->lastInsertId();
        } else {
            $conversationId = $conversation['id'];
            
            // Cập nhật nguoidung_id nếu chưa có
            if ($nguoidungId) {
                $sqlUpdate = "UPDATE chatbot_conversations SET nguoidung_id = :nguoidung_id WHERE id = :id";
                $stmtUpdate = $this->db->prepare($sqlUpdate);
                $stmtUpdate->bindParam(':nguoidung_id', $nguoidungId, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':id', $conversationId, PDO::PARAM_INT);
                $stmtUpdate->execute();
            }
        }
        
        $sqlUser = "INSERT INTO chatbot_messages (session_id, conversation_id, message, message_type, nguoidung_id, created_at)
                   VALUES (:session_id, :conversation_id, :message, 'user', :nguoidung_id, NOW())";
        $stmtUser = $this->db->prepare($sqlUser);
        $stmtUser->bindParam(':session_id', $sessionId);
        $stmtUser->bindParam(':conversation_id', $conversationId);
        $stmtUser->bindParam(':message', $message);
        $stmtUser->bindParam(':nguoidung_id', $nguoidungId, PDO::PARAM_INT);
        $stmtUser->execute();
        
        $sqlBot = "INSERT INTO chatbot_messages (session_id, conversation_id, message, response, message_type, nguoidung_id, created_at)
                  VALUES (:session_id, :conversation_id, :message, :response, 'bot', :nguoidung_id, NOW())";
        $stmtBot = $this->db->prepare($sqlBot);
        $stmtBot->bindParam(':session_id', $sessionId);
        $stmtBot->bindParam(':conversation_id', $conversationId);
        $stmtBot->bindParam(':message', $message);
        $stmtBot->bindParam(':response', $response);
        $stmtBot->bindParam(':nguoidung_id', $nguoidungId, PDO::PARAM_INT);
        return $stmtBot->execute();
    }

    public function timKiemViecLam($filters) {
        $sql = "SELECT t.*, n.tennganh, tt.tentinh, l.tenloai, m.tenmucluong,
                nd.hoten, nhatd.tencongty, nhatd.logo
                FROM tintuyendung t
                LEFT JOIN nganhnghe n ON t.nganhnghe_id = n.id
                LEFT JOIN tinhthanh tt ON t.tinhthanh_id = tt.id
                LEFT JOIN loaicongviec l ON t.loaicongviec_id = l.id
                LEFT JOIN mucluong m ON t.mucluong_id = m.id
                LEFT JOIN nguoidung nd ON t.nguoidung_id = nd.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id
                WHERE t.trangthai = 'dangmo' AND t.ngayhethan >= CURDATE()";
        
        $params = [];
        
        if (!empty($filters['keyword'])) {
            $sql .= " AND (t.tieude LIKE :keyword OR t.mota LIKE :keyword OR n.tennganh LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        
        if (!empty($filters['nganhnghe_id'])) {
            $sql .= " AND t.nganhnghe_id = :nganhnghe_id";
            $params[':nganhnghe_id'] = $filters['nganhnghe_id'];
        }
        
        if (!empty($filters['tinhthanh_id'])) {
            $sql .= " AND t.tinhthanh_id = :tinhthanh_id";
            $params[':tinhthanh_id'] = $filters['tinhthanh_id'];
        }
        
        if (!empty($filters['loaicongviec_id'])) {
            $sql .= " AND t.loaicongviec_id = :loaicongviec_id";
            $params[':loaicongviec_id'] = $filters['loaicongviec_id'];
        }
        
        if (!empty($filters['mucluong_id'])) {
            $sql .= " AND t.mucluong_id = :mucluong_id";
            $params[':mucluong_id'] = $filters['mucluong_id'];
        }
        
        $sql .= " ORDER BY t.ngaydang DESC LIMIT :limit";
        $params[':limit'] = $filters['limit'] ?? 5;
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            if ($key === ':limit') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function timDiaDiem($tenDiaDiem) {
        $sqlCheckDiaDiem = "SELECT id, tentinh FROM tinhthanh WHERE tentinh LIKE :tentinh LIMIT 1";
        $stmtCheck = $this->db->prepare($sqlCheckDiaDiem);
        $stmtCheck->bindValue(':tentinh', '%' . $tenDiaDiem . '%');
        $stmtCheck->execute();
        return $stmtCheck->fetch(PDO::FETCH_ASSOC);
    }

    public function layDanhSachNganhNghe() {
        $sql = "SELECT id, tennganh FROM nganhnghe ORDER BY tennganh ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layDanhSachTinhThanh() {
        $sql = "SELECT id, tentinh FROM tinhthanh ORDER BY tentinh ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layDanhSachLoaiCongViec() {
        $sql = "SELECT id, tenloai FROM loaicongviec ORDER BY tenloai ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layDanhSachMucLuong() {
        $sql = "SELECT id, tenmucluong FROM mucluong ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layChiTietTinTuyenDung($id) {
        $sql = "SELECT t.*, n.tennganh, tt.tentinh, l.tenloai, m.tenmucluong,
                nd.hoten, nhatd.tencongty, nhatd.logo, nhatd.diachi_congty, nhatd.website
                FROM tintuyendung t
                LEFT JOIN nganhnghe n ON t.nganhnghe_id = n.id
                LEFT JOIN tinhthanh tt ON t.tinhthanh_id = tt.id
                LEFT JOIN loaicongviec l ON t.loaicongviec_id = l.id
                LEFT JOIN mucluong m ON t.mucluong_id = m.id
                LEFT JOIN nguoidung nd ON t.nguoidung_id = nd.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id
                WHERE t.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function layThongKeHeThong() {
        $sql = "SELECT 
            (SELECT COUNT(*) FROM tintuyendung WHERE trangthai = 'dangmo' AND ngayhethan >= CURDATE()) as tongcongviec,
            (SELECT COUNT(*) FROM nguoidung WHERE vaitro = 'tuyendung') as tongnhatuyendung,
            (SELECT COUNT(*) FROM nguoidung WHERE vaitro = 'ungvien') as tongungvien";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function layTopNganhNghe($limit = 5) {
        $sql = "SELECT n.tennganh, COUNT(t.id) as soluong
                FROM nganhnghe n
                LEFT JOIN tintuyendung t ON n.id = t.nganhnghe_id 
                WHERE t.trangthai = 'dangmo' AND t.ngayhethan >= CURDATE()
                GROUP BY n.id, n.tennganh
                ORDER BY soluong DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm việc làm theo vị trí dựa trên địa chỉ người dùng
     * So khớp với cả diachilamviec và tinhthanh
     */
    public function timViecTheoViTri($diachiNguoiDung, $addressParts) {
        $sql = "SELECT t.*, n.tennganh, tt.tentinh, l.tenloai, m.tenmucluong,
                nd.hoten, nhatd.tencongty, nhatd.logo
                FROM tintuyendung t
                LEFT JOIN nganhnghe n ON t.nganhnghe_id = n.id
                LEFT JOIN tinhthanh tt ON t.tinhthanh_id = tt.id
                LEFT JOIN loaicongviec l ON t.loaicongviec_id = l.id
                LEFT JOIN mucluong m ON t.mucluong_id = m.id
                LEFT JOIN nguoidung nd ON t.nguoidung_id = nd.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id
                WHERE t.trangthai = 'dangmo' AND t.ngayhethan >= CURDATE()";
        
        $conditions = [];
        $params = [];
        
        // Tìm theo tỉnh/thành phố
        if (!empty($addressParts['tinh'])) {
            $conditions[] = "(tt.tentinh LIKE :tinh OR LOWER(t.diachilamviec) LIKE :diachitinh)";
            $params[':tinh'] = '%' . $addressParts['tinh'] . '%';
            $params[':diachitinh'] = '%' . mb_strtolower($addressParts['tinh'], 'UTF-8') . '%';
        }
        
        // Tìm theo quận/huyện nếu có
        if (!empty($addressParts['quan'])) {
            $conditions[] = "LOWER(t.diachilamviec) LIKE :quan";
            $params[':quan'] = '%' . mb_strtolower($addressParts['quan'], 'UTF-8') . '%';
        }
        
        // Tìm theo phường/xã nếu có
        if (!empty($addressParts['phuong'])) {
            $conditions[] = "LOWER(t.diachilamviec) LIKE :phuong";
            $params[':phuong'] = '%' . mb_strtolower($addressParts['phuong'], 'UTF-8') . '%';
        }
        
        if (!empty($conditions)) {
            $sql .= " AND (" . implode(' OR ', $conditions) . ")";
        }
        
        $sql .= " ORDER BY 
                  CASE 
                    WHEN LOWER(t.diachilamviec) LIKE :diachichinh THEN 1
                    WHEN tt.tentinh LIKE :tinhchinh THEN 2
                    ELSE 3
                  END,
                  t.ngaydang DESC 
                  LIMIT 10";
        
        // Thêm params cho ORDER BY
        $params[':diachichinh'] = '%' . $diachiNguoiDung . '%';
        $params[':tinhchinh'] = !empty($addressParts['tinh']) ? '%' . $addressParts['tinh'] . '%' : '%';
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
