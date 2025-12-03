<?php

class AdminModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function layThongKeTongQuan() {
        $sql = "SELECT 
            (SELECT COUNT(*) FROM nguoidung WHERE vaitro = 'ungvien') as tongungvien,
            (SELECT COUNT(*) FROM nguoidung WHERE vaitro = 'tuyendung') as tongnhatd,
            (SELECT COUNT(*) FROM nguoidung WHERE vaitro = 'choduyet') as choduyet,
            (SELECT COUNT(*) FROM tintuyendung WHERE trangthai = 'dangmo') as tindangmo,
            (SELECT COUNT(*) FROM tintuyendung WHERE trangthai = 'choduyet') as tinchoduyet,
            (SELECT COUNT(*) FROM donungtuyen) as tongdon";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$stats) {
            return [
                'tongungvien' => 0,
                'tongnhatd' => 0,
                'choduyet' => 0,
                'tindangmo' => 0,
                'tinchoduyet' => 0,
                'tongdon' => 0
            ];
        }
        
        return $stats;
    }

    public function layThongKeTheoThang() {
        $sql = "SELECT 
            DATE_FORMAT(ngaytao, '%Y-%m') as thang,
            COUNT(*) as soluong
            FROM nguoidung
            WHERE ngaytao >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(ngaytao, '%Y-%m')
            ORDER BY thang";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layTinMoiChoDuyet($limit = 5) {
        $sql = "SELECT t.*, nd.hoten, nhatd.tencongty
                FROM tintuyendung t
                LEFT JOIN nguoidung nd ON t.nguoidung_id = nd.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id
                WHERE t.trangthai = 'choduyet'
                ORDER BY t.ngaydang DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layYeuCauNhaTuyenDung() {
        $sql = "SELECT nd.id, nd.email, nd.hoten, nd.sodienthoai, nd.diachi, nd.avatar, nd.vaitro, nd.xacminh,
                       nhatd.tencongty, nhatd.masothue, nhatd.diachi_congty, nhatd.quymo, 
                       nhatd.linhvuc, nhatd.mota, nhatd.website, nhatd.logo, nhatd.email_congty, nhatd.ngaygui
                FROM nguoidung nd
                INNER JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id
                WHERE nd.vaitro = 'choduyet'
                ORDER BY nhatd.ngaygui DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function duyetNhaTuyenDung($nguoidungId) {
        try {
            $this->db->beginTransaction();
            
            $sqlVaitro = "UPDATE nguoidung SET vaitro = :vaitro WHERE id = :id";
            $stmtVaitro = $this->db->prepare($sqlVaitro);
            $stmtVaitro->bindValue(':vaitro', 'tuyendung', PDO::PARAM_STR);
            $stmtVaitro->bindValue(':id', $nguoidungId, PDO::PARAM_INT);
            $resultVaitro = $stmtVaitro->execute();
            
            $sqlXacminh = "UPDATE nguoidung SET xacminh = :xacminh WHERE id = :id";
            $stmtXacminh = $this->db->prepare($sqlXacminh);
            $stmtXacminh->bindValue(':xacminh', 1, PDO::PARAM_INT);
            $stmtXacminh->bindValue(':id', $nguoidungId, PDO::PARAM_INT);
            $resultXacminh = $stmtXacminh->execute();
            
            $this->db->commit();
            return $resultVaitro && $resultXacminh;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function tuChoiNhaTuyenDung($nguoidungId) {
        try {
            $this->db->beginTransaction();
            
            $sqlVaitro = "UPDATE nguoidung SET vaitro = :vaitro WHERE id = :id";
            $stmtVaitro = $this->db->prepare($sqlVaitro);
            $stmtVaitro->bindValue(':vaitro', 'ungvien', PDO::PARAM_STR);
            $stmtVaitro->bindValue(':id', $nguoidungId, PDO::PARAM_INT);
            $updateVaitro = $stmtVaitro->execute();
            
            $sqlXoa = "DELETE FROM thongtinnhatuyendung WHERE nguoidung_id = :id";
            $stmtXoa = $this->db->prepare($sqlXoa);
            $stmtXoa->bindValue(':id', $nguoidungId, PDO::PARAM_INT);
            $deleteResult = $stmtXoa->execute();
            
            $this->db->commit();
            return $updateVaitro && $deleteResult;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function layDanhSachTinTuyenDung($trangthai, $limit, $offset) {
        $sql = "SELECT t.*, nd.hoten, nhatd.tencongty
                FROM tintuyendung t
                LEFT JOIN nguoidung nd ON t.nguoidung_id = nd.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id";
        
        if ($trangthai) {
            $sql .= " WHERE t.trangthai = :trangthai";
        }
        
        $sql .= " ORDER BY t.ngaydang DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        if ($trangthai) {
            $stmt->bindParam(':trangthai', $trangthai);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function demTinTuyenDung($trangthai = null) {
        $sql = "SELECT COUNT(*) as total FROM tintuyendung";
        if ($trangthai) {
            $sql .= " WHERE trangthai = :trangthai";
        }
        
        $stmt = $this->db->prepare($sql);
        if ($trangthai) {
            $stmt->bindParam(':trangthai', $trangthai);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function layDanhSachDonUngTuyen($limit, $offset) {
        $sql = "SELECT d.*, 
                nd.hoten as tenungvien, nd.email,
                t.tieude,
                nhatd.tencongty
                FROM donungtuyen d
                INNER JOIN nguoidung nd ON d.nguoidung_id = nd.id
                INNER JOIN tintuyendung t ON d.tintuyendung_id = t.id
                LEFT JOIN nguoidung nd2 ON t.nguoidung_id = nd2.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd2.id = nhatd.nguoidung_id
                ORDER BY d.ngaynop DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function demDonUngTuyen() {
        $sql = "SELECT COUNT(*) as total FROM donungtuyen";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
