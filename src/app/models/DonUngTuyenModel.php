<?php

class DonUngTuyenModel {
    private $db;
    private $table = 'donungtuyen';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function taoDon($data) {
        $sql = "INSERT INTO " . $this->table . " 
                (tintuyendung_id, nguoidung_id, cv_file, thuungtuyen, trangthai)
                VALUES (:tintuyendung_id, :nguoidung_id, :cv_file, :thuungtuyen, 'moi')";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':tintuyendung_id', $data['tintuyendung_id']);
        $stmt->bindParam(':nguoidung_id', $data['nguoidung_id']);
        $stmt->bindParam(':cv_file', $data['cv_file']);
        $stmt->bindParam(':thuungtuyen', $data['thuungtuyen']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function kiemTraUngTuyen($tintuyendungId, $nguoidungId) {
        $sql = "SELECT id FROM " . $this->table . " 
                WHERE tintuyendung_id = :tintuyendung_id 
                AND nguoidung_id = :nguoidung_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tintuyendung_id', $tintuyendungId);
        $stmt->bindParam(':nguoidung_id', $nguoidungId);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function layDanhSachTheoTin($tintuyendungId, $filterTrangthai = '', $limit = null, $offset = 0) {
        $sql = "SELECT d.*, nd.hoten, nd.email, nd.sodienthoai, nd.avatar,
                tuv.ngaysinh, tuv.gioitinh, tuv.trinhdo, tuv.kinhnghiem
                FROM " . $this->table . " d
                INNER JOIN nguoidung nd ON d.nguoidung_id = nd.id
                LEFT JOIN thongtinungvien tuv ON nd.id = tuv.nguoidung_id
                WHERE d.tintuyendung_id = :tintuyendung_id";
        
        // Thêm điều kiện lọc theo trạng thái
        if (!empty($filterTrangthai)) {
            $sql .= " AND d.trangthai = :trangthai";
        }
        
        $sql .= " ORDER BY d.ngaynop DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tintuyendung_id', $tintuyendungId);
        
        // Bind filter nếu có
        if (!empty($filterTrangthai)) {
            $stmt->bindParam(':trangthai', $filterTrangthai);
        }
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layDanhSachTheoUngVien($nguoidungId, $limit = null, $offset = 0) {
        $sql = "SELECT d.*, t.tieude, t.ngayhethan,
                nhatd.tencongty, nhatd.logo
                FROM " . $this->table . " d
                INNER JOIN tintuyendung t ON d.tintuyendung_id = t.id
                LEFT JOIN nguoidung nd ON t.nguoidung_id = nd.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id
                WHERE d.nguoidung_id = :nguoidung_id
                ORDER BY d.ngaynop DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nguoidung_id', $nguoidungId);
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layDanhSachTheoNhaTuyenDung($nguoidungId, $filterTrangthai = '') {
        $sql = "SELECT d.*, nd.hoten, nd.email, nd.sodienthoai, nd.avatar,
                tuv.ngaysinh, tuv.gioitinh, tuv.trinhdo, tuv.kinhnghiem,
                t.tieude, t.id as tintuyendung_id
                FROM " . $this->table . " d
                INNER JOIN nguoidung nd ON d.nguoidung_id = nd.id
                LEFT JOIN thongtinungvien tuv ON nd.id = tuv.nguoidung_id
                INNER JOIN tintuyendung t ON d.tintuyendung_id = t.id
                WHERE t.nguoidung_id = :nguoidung_id";
        
        // Thêm điều kiện lọc theo trạng thái
        if (!empty($filterTrangthai)) {
            $sql .= " AND d.trangthai = :trangthai";
        }
        
        $sql .= " ORDER BY d.ngaynop DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nguoidung_id', $nguoidungId);
        
        // Bind filter nếu có
        if (!empty($filterTrangthai)) {
            $stmt->bindParam(':trangthai', $filterTrangthai);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layChiTiet($id) {
        $sql = "SELECT d.*, 
                nd.hoten, nd.email, nd.sodienthoai, nd.avatar, nd.diachi,
                tuv.ngaysinh, tuv.gioitinh, tuv.trinhdo, tuv.kinhnghiem, tuv.kynang, tuv.muctieucanhan,
                t.tieude, t.nguoidung_id as nhatuyendung_id,
                nhatd.tencongty
                FROM " . $this->table . " d
                INNER JOIN nguoidung nd ON d.nguoidung_id = nd.id
                LEFT JOIN thongtinungvien tuv ON nd.id = tuv.nguoidung_id
                INNER JOIN tintuyendung t ON d.tintuyendung_id = t.id
                LEFT JOIN thongtinnhatuyendung nhatd ON t.nguoidung_id = nhatd.nguoidung_id
                WHERE d.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function capNhatTrangThai($id, $trangthai, $ghichu = null) {
        $sql = "UPDATE " . $this->table . " 
                SET trangthai = :trangthai";
        
        if ($ghichu !== null) {
            $sql .= ", ghichu = :ghichu";
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':trangthai', $trangthai);
        $stmt->bindParam(':id', $id);
        
        if ($ghichu !== null) {
            $stmt->bindParam(':ghichu', $ghichu);
        }
        
        return $stmt->execute();
    }

    public function xoa($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function demTheoTrangThai($tintuyendungId, $trangthai) {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table . " 
                WHERE tintuyendung_id = :tintuyendung_id 
                AND trangthai = :trangthai";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tintuyendung_id', $tintuyendungId);
        $stmt->bindParam(':trangthai', $trangthai);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function thongKe($nguoidungId = null) {
        $sql = "SELECT 
                COUNT(*) as tongdon,
                SUM(CASE WHEN trangthai = 'dangxem' THEN 1 ELSE 0 END) as dangxem,
                SUM(CASE WHEN trangthai = 'phongvan' THEN 1 ELSE 0 END) as phongvan,
                SUM(CASE WHEN trangthai = 'tuchoi' THEN 1 ELSE 0 END) as tuchoi,
                SUM(CASE WHEN trangthai = 'nhanviec' THEN 1 ELSE 0 END) as nhanviec
                FROM " . $this->table;
        
        if ($nguoidungId) {
            $sql .= " WHERE nguoidung_id = :nguoidung_id";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($nguoidungId) {
            $stmt->bindParam(':nguoidung_id', $nguoidungId);
        }
        
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function thongKeTheoTin($tintuyendungId) {
        $sql = "SELECT 
                COUNT(*) as tong,
                COALESCE(SUM(CASE WHEN trangthai = 'dangxem' THEN 1 ELSE 0 END), 0) as dangxem,
                COALESCE(SUM(CASE WHEN trangthai = 'phongvan' THEN 1 ELSE 0 END), 0) as phongvan,
                COALESCE(SUM(CASE WHEN trangthai = 'nhanviec' THEN 1 ELSE 0 END), 0) as nhanviec
                FROM " . $this->table . " 
                WHERE tintuyendung_id = :tintuyendung_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tintuyendung_id', $tintuyendungId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
