<?php

class ThongTinUngVienModel {
    private $db;
    private $table = 'thongtinungvien';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function layThongTin($nguoidungId) {
        $sql = "SELECT * FROM " . $this->table . " WHERE nguoidung_id = :nguoidung_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nguoidung_id', $nguoidungId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function taoThongTin($data) {
        $sql = "INSERT INTO " . $this->table . " 
                (nguoidung_id, ngaysinh, gioitinh, trinhdo, kinhnghiem, kynang, muctieucanhan)
                VALUES (:nguoidung_id, :ngaysinh, :gioitinh, :trinhdo, :kinhnghiem, :kynang, :muctieucanhan)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':nguoidung_id', $data['nguoidung_id']);
        $stmt->bindParam(':ngaysinh', $data['ngaysinh']);
        $stmt->bindParam(':gioitinh', $data['gioitinh']);
        $stmt->bindParam(':trinhdo', $data['trinhdo']);
        $stmt->bindParam(':kinhnghiem', $data['kinhnghiem']);
        $stmt->bindParam(':kynang', $data['kynang']);
        $stmt->bindParam(':muctieucanhan', $data['muctieucanhan']);
        
        return $stmt->execute();
    }

    public function taoThongTinMacDinh($nguoidungId) {
        $sql = "INSERT INTO " . $this->table . " (nguoidung_id) VALUES (:nguoidung_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nguoidung_id', $nguoidungId);
        return $stmt->execute();
    }

    public function capNhat($nguoidungId, $data) {
        $sql = "UPDATE " . $this->table . " 
                SET ngaysinh = :ngaysinh,
                    gioitinh = :gioitinh,
                    trinhdo = :trinhdo,
                    kinhnghiem = :kinhnghiem,
                    kynang = :kynang,
                    muctieucanhan = :muctieucanhan
                WHERE nguoidung_id = :nguoidung_id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':nguoidung_id', $nguoidungId);
        $stmt->bindParam(':ngaysinh', $data['ngaysinh']);
        $stmt->bindParam(':gioitinh', $data['gioitinh']);
        $stmt->bindParam(':trinhdo', $data['trinhdo']);
        $stmt->bindParam(':kinhnghiem', $data['kinhnghiem']);
        $stmt->bindParam(':kynang', $data['kynang']);
        $stmt->bindParam(':muctieucanhan', $data['muctieucanhan']);
        
        return $stmt->execute();
    }

    public function kiemTraTonTai($nguoidungId) {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE nguoidung_id = :nguoidung_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nguoidung_id', $nguoidungId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }
}
?>
