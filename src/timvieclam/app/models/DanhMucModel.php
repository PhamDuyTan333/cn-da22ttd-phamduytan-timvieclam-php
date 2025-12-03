<?php

class DanhMucModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function layNganhNghe($limit = null) {
        $sql = "SELECT * FROM nganhnghe ORDER BY tennganh ASC";
        if ($limit) {
            $sql .= " LIMIT :limit";
        }
        
        $stmt = $this->db->prepare($sql);
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layTatCaNganhNghe() {
        $sql = "SELECT * FROM nganhnghe ORDER BY tennganh ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function demDanhMuc() {
        return [
            'nganhnghe' => $this->db->query("SELECT COUNT(*) FROM nganhnghe")->fetchColumn(),
            'mucluong' => $this->db->query("SELECT COUNT(*) FROM mucluong")->fetchColumn(),
            'loaicv' => $this->db->query("SELECT COUNT(*) FROM loaicongviec")->fetchColumn(),
            'tinhthanh' => $this->db->query("SELECT COUNT(*) FROM tinhthanh")->fetchColumn(),
        ];
    }

    public function kiemTraNganhNgheDangDung($id) {
        $sql = "SELECT COUNT(*) FROM tintuyendung WHERE nganhnghe_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function layTinhThanh() {
        $sql = "SELECT * FROM tinhthanh ORDER BY tentinh ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layMucLuong() {
        $sql = "SELECT * FROM mucluong ORDER BY id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layLoaiCongViec() {
        $sql = "SELECT * FROM loaicongviec ORDER BY tenloai ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layThongKe() {
        $sql = "SELECT 
            (SELECT COUNT(*) FROM tintuyendung WHERE trangthai = 'dangmo' AND ngayhethan >= CURDATE()) as tongtin,
            (SELECT COUNT(*) FROM nguoidung WHERE vaitro = 'tuyendung') as tongnhatd,
            (SELECT COUNT(*) FROM nguoidung WHERE vaitro = 'ungvien') as tongungvien,
            (SELECT COUNT(*) FROM donungtuyen) as tongdon";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return [
                'tongtin' => 0,
                'tongnhatd' => 0,
                'tongungvien' => 0,
                'tongdon' => 0
            ];
        }
        
        return $result;
    }

    public function layCauHinh() {
        $sql = "SELECT ten_config, gia_tri, kieu_dulieu FROM cauhinh ORDER BY id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $configs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $config = [];
        foreach ($configs as $item) {
            $value = $item['gia_tri'];
            
            if ($item['kieu_dulieu'] == 'int') {
                if ($item['ten_config'] == 'max_cv_size') {
                    $value = (int)($value / 1048576);
                } elseif (in_array($item['ten_config'], ['max_logo_size', 'max_avatar_size'])) {
                    $value = (int)($value / 1048576);
                } else {
                    $value = (int)$value;
                }
            } elseif ($item['kieu_dulieu'] == 'bool') {
                $value = (int)$value;
            }
            
            $config[$item['ten_config']] = $value;
        }
        
        return $config;
    }

    public function capNhatCauHinh($tenConfig, $giaTri) {
        $sql = "UPDATE cauhinh SET gia_tri = :gia_tri WHERE ten_config = :ten_config";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':gia_tri', $giaTri);
        $stmt->bindParam(':ten_config', $tenConfig);
        return $stmt->execute();
    }

    public function capNhatNhieuCauHinh($configs) {
        try {
            $this->db->beginTransaction();
            
            foreach ($configs as $tenConfig => $giaTri) {
                $sql = "UPDATE cauhinh SET gia_tri = :value WHERE ten_config = :ten_config";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(['value' => $giaTri, 'ten_config' => $tenConfig]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function themNganhNghe($tennganh, $mota = null) {
        $sql = "INSERT INTO nganhnghe (tennganh, mota) VALUES (:tennganh, :mota)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tennganh', $tennganh);
        $stmt->bindParam(':mota', $mota);
        return $stmt->execute();
    }

    public function capNhatNganhNghe($id, $tennganh, $mota = null) {
        $sql = "UPDATE nganhnghe SET tennganh = :tennganh, mota = :mota WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tennganh', $tennganh);
        $stmt->bindParam(':mota', $mota);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function xoaNganhNghe($id) {
        $sql = "DELETE FROM nganhnghe WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function themTinhThanh($tentinh) {
        $sql = "INSERT INTO tinhthanh (tentinh) VALUES (:tentinh)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tentinh', $tentinh);
        return $stmt->execute();
    }

    public function capNhatTinhThanh($id, $tentinh) {
        $sql = "UPDATE tinhthanh SET tentinh = :tentinh WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tentinh', $tentinh);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function xoaTinhThanh($id) {
        $sql = "DELETE FROM tinhthanh WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function themMucLuong($tenmucluong) {
        $sql = "INSERT INTO mucluong (tenmucluong) VALUES (:tenmucluong)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tenmucluong', $tenmucluong);
        return $stmt->execute();
    }

    public function capNhatMucLuong($id, $tenmucluong) {
        $sql = "UPDATE mucluong SET tenmucluong = :tenmucluong WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tenmucluong', $tenmucluong);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function xoaMucLuong($id) {
        $sql = "DELETE FROM mucluong WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function themLoaiCongViec($tenloai) {
        $sql = "INSERT INTO loaicongviec (tenloai) VALUES (:tenloai)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tenloai', $tenloai);
        return $stmt->execute();
    }

    public function capNhatLoaiCongViec($id, $tenloai) {
        $sql = "UPDATE loaicongviec SET tenloai = :tenloai WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':tenloai', $tenloai);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function xoaLoaiCongViec($id) {
        $sql = "DELETE FROM loaicongviec WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>
