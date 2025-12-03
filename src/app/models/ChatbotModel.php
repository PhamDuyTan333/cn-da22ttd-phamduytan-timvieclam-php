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

    public function luuTinNhan($sessionId, $message, $response) {
        $sqlCheck = "SELECT id FROM chatbot_conversations WHERE session_id = :session_id";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bindParam(':session_id', $sessionId);
        $stmtCheck->execute();
        $conversation = $stmtCheck->fetch(PDO::FETCH_ASSOC);
        
        if (!$conversation) {
            $sqlConv = "INSERT INTO chatbot_conversations (session_id, started_at) 
                       VALUES (:session_id, NOW())";
            $stmtConv = $this->db->prepare($sqlConv);
            $stmtConv->bindParam(':session_id', $sessionId);
            $stmtConv->execute();
            $conversationId = $this->db->lastInsertId();
        } else {
            $conversationId = $conversation['id'];
        }
        
        $sqlUser = "INSERT INTO chatbot_messages (session_id, conversation_id, message, message_type, created_at)
                   VALUES (:session_id, :conversation_id, :message, 'user', NOW())";
        $stmtUser = $this->db->prepare($sqlUser);
        $stmtUser->bindParam(':session_id', $sessionId);
        $stmtUser->bindParam(':conversation_id', $conversationId);
        $stmtUser->bindParam(':message', $message);
        $stmtUser->execute();
        
        $sqlBot = "INSERT INTO chatbot_messages (session_id, conversation_id, message, response, message_type, created_at)
                  VALUES (:session_id, :conversation_id, :message, :response, 'bot', NOW())";
        $stmtBot = $this->db->prepare($sqlBot);
        $stmtBot->bindParam(':session_id', $sessionId);
        $stmtBot->bindParam(':conversation_id', $conversationId);
        $stmtBot->bindParam(':message', $message);
        $stmtBot->bindParam(':response', $response);
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
}
?>
