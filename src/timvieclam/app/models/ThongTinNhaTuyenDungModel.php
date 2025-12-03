<?php

class ThongTinNhaTuyenDungModel {
    private $db;
    private $table = 'thongtinnhatuyendung';
    
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
                (nguoidung_id, tencongty, diachi_congty, website, logo, mota, quymo, linhvuc)
                VALUES (:nguoidung_id, :tencongty, :diachi_congty, :website, :logo, :mota, :quymo, :linhvuc)";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':nguoidung_id', $data['nguoidung_id']);
        $stmt->bindParam(':tencongty', $data['tencongty']);
        $stmt->bindParam(':diachi_congty', $data['diachi_congty']);
        $stmt->bindParam(':website', $data['website']);
        $stmt->bindParam(':logo', $data['logo']);
        $stmt->bindParam(':mota', $data['mota']);
        $stmt->bindParam(':quymo', $data['quymo']);
        $stmt->bindParam(':linhvuc', $data['linhvuc']);
        
        return $stmt->execute();
    }

    public function capNhat($nguoidungId, $data) {
        $sql = "UPDATE " . $this->table . " 
                SET tencongty = :tencongty,
                    diachi_congty = :diachi_congty,
                    website = :website,
                    mota = :mota,
                    quymo = :quymo,
                    linhvuc = :linhvuc";
        
        if (isset($data['logo'])) {
            $sql .= ", logo = :logo";
        }
        
        $sql .= " WHERE nguoidung_id = :nguoidung_id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':nguoidung_id', $nguoidungId);
        $stmt->bindParam(':tencongty', $data['tencongty']);
        $stmt->bindParam(':diachi_congty', $data['diachi_congty']);
        $stmt->bindParam(':website', $data['website']);
        $stmt->bindParam(':mota', $data['mota']);
        $stmt->bindParam(':quymo', $data['quymo']);
        $stmt->bindParam(':linhvuc', $data['linhvuc']);
        
        if (isset($data['logo'])) {
            $stmt->bindParam(':logo', $data['logo']);
        }
        
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

    public function layThongKe($nguoidungId) {
        $sql = "SELECT 
                (SELECT COUNT(*) FROM tintuyendung WHERE nguoidung_id = :nguoidung_id1) as tongtin,
                (SELECT COUNT(*) FROM tintuyendung WHERE nguoidung_id = :nguoidung_id2 AND trangthai = 'dangmo') as tindangmo,
                (SELECT COUNT(*) FROM donungtuyen d 
                 INNER JOIN tintuyendung t ON d.tintuyendung_id = t.id 
                 WHERE t.nguoidung_id = :nguoidung_id3) as tongdon,
                (SELECT COUNT(*) FROM donungtuyen d 
                 INNER JOIN tintuyendung t ON d.tintuyendung_id = t.id 
                 WHERE t.nguoidung_id = :nguoidung_id4 AND d.trangthai = 'dangxem') as donmoi";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nguoidung_id1', $nguoidungId);
        $stmt->bindParam(':nguoidung_id2', $nguoidungId);
        $stmt->bindParam(':nguoidung_id3', $nguoidungId);
        $stmt->bindParam(':nguoidung_id4', $nguoidungId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
