<?php

class ApiModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function layThongKeVaiTro() {
        $sql = "SELECT 
            SUM(CASE WHEN vaitro = 'ungvien' THEN 1 ELSE 0 END) as ungvien,
            SUM(CASE WHEN vaitro = 'tuyendung' THEN 1 ELSE 0 END) as tuyendung,
            SUM(CASE WHEN vaitro = 'choduyet' THEN 1 ELSE 0 END) as choduyet,
            SUM(CASE WHEN vaitro = 'admin' THEN 1 ELSE 0 END) as admin
            FROM nguoidung";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        foreach ($result as $key => $value) {
            $result[$key] = (int)$value;
        }
        
        return $result;
    }

    public function layThongKeTin() {
        $sql = "SELECT 
            SUM(CASE WHEN trangthai = 'dangmo' THEN 1 ELSE 0 END) as dangmo,
            SUM(CASE WHEN trangthai = 'choduyet' THEN 1 ELSE 0 END) as choduyet,
            SUM(CASE WHEN trangthai = 'an' THEN 1 ELSE 0 END) as an,
            SUM(CASE WHEN trangthai = 'hethan' THEN 1 ELSE 0 END) as hethan
            FROM tintuyendung";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        foreach ($result as $key => $value) {
            $result[$key] = (int)$value;
        }
        
        return $result;
    }

    public function layThongKeDon() {
        $sql = "SELECT 
            SUM(CASE WHEN trangthai = 'moi' THEN 1 ELSE 0 END) as moi,
            SUM(CASE WHEN trangthai = 'dangxem' THEN 1 ELSE 0 END) as dangxem,
            SUM(CASE WHEN trangthai = 'phongvan' THEN 1 ELSE 0 END) as phongvan,
            SUM(CASE WHEN trangthai = 'nhanviec' THEN 1 ELSE 0 END) as nhanviec,
            SUM(CASE WHEN trangthai = 'tuchoi' THEN 1 ELSE 0 END) as tuchoi
            FROM donungtuyen";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        foreach ($result as $key => $value) {
            $result[$key] = (int)$value;
        }
        
        return $result;
    }

    public function layTopNganhNghe($limit = 10) {
        $sql = "SELECT nn.tennganh as ten, COUNT(t.id) as soluong
                FROM nganhnghe nn
                LEFT JOIN tintuyendung t ON nn.id = t.nganhnghe_id
                GROUP BY nn.id, nn.tennganh
                ORDER BY soluong DESC
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($result as &$item) {
            $item['soluong'] = (int)$item['soluong'];
        }
        
        return $result;
    }

    public function layXuHuongTheoNgay($days = 30) {
        $sql = "SELECT 
            DATE(ngay) as ngay,
            SUM(tinmoi) as tinmoi,
            SUM(donnop) as donnop,
            SUM(nguoidungmoi) as nguoidungmoi
            FROM (
                SELECT ngaydang as ngay, 1 as tinmoi, 0 as donnop, 0 as nguoidungmoi
                FROM tintuyendung
                WHERE ngaydang >= DATE_SUB(NOW(), INTERVAL :days DAY)
                
                UNION ALL
                
                SELECT ngaynop as ngay, 0 as tinmoi, 1 as donnop, 0 as nguoidungmoi
                FROM donungtuyen
                WHERE ngaynop >= DATE_SUB(NOW(), INTERVAL :days DAY)
                
                UNION ALL
                
                SELECT ngaytao as ngay, 0 as tinmoi, 0 as donnop, 1 as nguoidungmoi
                FROM nguoidung
                WHERE ngaytao >= DATE_SUB(NOW(), INTERVAL :days DAY)
            ) combined
            GROUP BY DATE(ngay)
            ORDER BY ngay";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($result as &$item) {
            $item['ngay'] = date('d/m', strtotime($item['ngay']));
            $item['tinmoi'] = (int)$item['tinmoi'];
            $item['donnop'] = (int)$item['donnop'];
            $item['nguoidungmoi'] = (int)$item['nguoidungmoi'];
        }
        
        return $result;
    }
}
?>
