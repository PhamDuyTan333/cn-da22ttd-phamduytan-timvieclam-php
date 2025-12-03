<?php

class NguoiDungModel {
    private $db;
    private $table = 'nguoidung';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function taoTaiKhoan($data) {
        $sql = "INSERT INTO " . $this->table . " 
                (email, matkhau, hoten, sodienthoai, vaitro) 
                VALUES (:email, :matkhau, :hoten, :sodienthoai, :vaitro)";
        
        $stmt = $this->db->prepare($sql);
        
        // Hash mật khẩu
        $matkhauHash = password_hash($data['matkhau'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':matkhau', $matkhauHash);
        $stmt->bindParam(':hoten', $data['hoten']);
        $stmt->bindParam(':sodienthoai', $data['sodienthoai']);
        $stmt->bindParam(':vaitro', $data['vaitro']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function kiemTraEmail($email) {
        $sql = "SELECT id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function dangNhap($email, $matkhau) {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email AND trangthai = 'hoatdong'";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Kiểm tra mật khẩu
            if (password_verify($matkhau, $user['matkhau'])) {
                return $user;
            }
        }
        
        return false;
    }

    public function layThongTin($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function layDiaChi($id) {
        $sql = "SELECT diachi FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['diachi'] : null;
    }

    public function capNhatThongTin($id, $data) {
        $sql = "UPDATE " . $this->table . " 
                SET hoten = :hoten, 
                    sodienthoai = :sodienthoai, 
                    diachi = :diachi";
        
        if (isset($data['avatar'])) {
            $sql .= ", avatar = :avatar";
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':hoten', $data['hoten']);
        $stmt->bindParam(':sodienthoai', $data['sodienthoai']);
        $stmt->bindParam(':diachi', $data['diachi']);
        
        if (isset($data['avatar'])) {
            $stmt->bindParam(':avatar', $data['avatar']);
        }
        
        return $stmt->execute();
    }

    public function doiMatKhau($id, $matkhauCu, $matkhauMoi) {
        // Lấy mật khẩu hiện tại
        $user = $this->layThongTin($id);
        
        // Kiểm tra mật khẩu cũ
        if (!password_verify($matkhauCu, $user['matkhau'])) {
            return false;
        }
        
        // Cập nhật mật khẩu mới
        $sql = "UPDATE " . $this->table . " SET matkhau = :matkhau WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        $matkhauHash = password_hash($matkhauMoi, PASSWORD_DEFAULT);
        $stmt->bindParam(':matkhau', $matkhauHash);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function capNhatVaiTro($id, $vaitro) {
        $sql = "UPDATE " . $this->table . " SET vaitro = :vaitro WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':vaitro', $vaitro, PDO::PARAM_STR);
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        
        $result = $stmt->execute();
        $rowCount = $stmt->rowCount();
        
        // Debug log
        error_log("capNhatVaiTro - SQL: $sql");
        error_log("capNhatVaiTro - ID: $id (type: " . gettype($id) . "), Vai trò: $vaitro");
        error_log("capNhatVaiTro - Execute result: " . ($result ? 'true' : 'false') . ", Rows affected: $rowCount");
        
        // Trả về true nếu execute thành công, không cần kiểm tra rowCount
        // vì nếu giá trị giống nhau thì rowCount = 0 nhưng vẫn là thành công
        return $result;
    }

    public function capNhatXacMinh($id, $xacminh) {
        $sql = "UPDATE " . $this->table . " SET xacminh = :xacminh WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':xacminh', (int)$xacminh, PDO::PARAM_INT);
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        
        $result = $stmt->execute();
        $rowCount = $stmt->rowCount();
        
        // Debug log
        error_log("capNhatXacMinh - SQL: $sql");
        error_log("capNhatXacMinh - ID: $id (type: " . gettype($id) . "), Xác minh: $xacminh");
        error_log("capNhatXacMinh - Execute result: " . ($result ? 'true' : 'false') . ", Rows affected: $rowCount");
        
        // Trả về true nếu execute thành công
        return $result;
    }

    public function capNhatTrangThai($id, $trangthai) {
        $sql = "UPDATE " . $this->table . " SET trangthai = :trangthai WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':trangthai', $trangthai);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function layDanhSach($vaitro = null, $limit = null, $offset = 0) {
        $sql = "SELECT * FROM " . $this->table;
        
        if ($vaitro) {
            $sql .= " WHERE vaitro = :vaitro";
        }
        
        $sql .= " ORDER BY ngaytao DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($vaitro) {
            $stmt->bindParam(':vaitro', $vaitro);
        }
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function demNguoiDung($vaitro = null) {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;
        
        if ($vaitro) {
            $sql .= " WHERE vaitro = :vaitro";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($vaitro) {
            $stmt->bindParam(':vaitro', $vaitro);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'];
    }
}
?>
