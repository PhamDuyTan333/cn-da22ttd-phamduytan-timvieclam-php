<?php

class TinTuyenDungModel {
    private $db;
    private $table = 'tintuyendung';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function taoTin($data) {
        $sql = "INSERT INTO " . $this->table . " 
                (nguoidung_id, tieude, nganhnghe_id, mucluong_id, loaicongviec_id, 
                 tinhthanh_id, diachilamviec, soluong, gioitinh_yc, mota, yeucau, 
                 quyenloi, ngayhethan, trangthai)
                VALUES (:nguoidung_id, :tieude, :nganhnghe_id, :mucluong_id, :loaicongviec_id,
                        :tinhthanh_id, :diachilamviec, :soluong, :gioitinh_yc, :mota, :yeucau,
                        :quyenloi, :ngayhethan, 'choduyet')";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':nguoidung_id', $data['nguoidung_id']);
        $stmt->bindParam(':tieude', $data['tieude']);
        $stmt->bindParam(':nganhnghe_id', $data['nganhnghe_id']);
        $stmt->bindParam(':mucluong_id', $data['mucluong_id']);
        $stmt->bindParam(':loaicongviec_id', $data['loaicongviec_id']);
        $stmt->bindParam(':tinhthanh_id', $data['tinhthanh_id']);
        $stmt->bindParam(':diachilamviec', $data['diachilamviec']);
        $stmt->bindParam(':soluong', $data['soluong']);
        $stmt->bindParam(':gioitinh_yc', $data['gioitinh_yc']);
        $stmt->bindParam(':mota', $data['mota']);
        $stmt->bindParam(':yeucau', $data['yeucau']);
        $stmt->bindParam(':quyenloi', $data['quyenloi']);
        $stmt->bindParam(':ngayhethan', $data['ngayhethan']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function layDanhSachTheoNguoiDung($nguoidungId, $limit = null, $offset = 0) {
        $sql = "SELECT t.*, n.tennganh, tt.tentinh, m.tenmucluong, l.tenloai,
                (SELECT COUNT(*) FROM donungtuyen WHERE tintuyendung_id = t.id) as sodon
                FROM " . $this->table . " t
                LEFT JOIN nganhnghe n ON t.nganhnghe_id = n.id
                LEFT JOIN tinhthanh tt ON t.tinhthanh_id = tt.id
                LEFT JOIN mucluong m ON t.mucluong_id = m.id
                LEFT JOIN loaicongviec l ON t.loaicongviec_id = l.id
                WHERE t.nguoidung_id = :nguoidung_id
                ORDER BY t.ngaydang DESC";
        
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

    public function layChiTiet($id) {
        $sql = "SELECT t.*, n.tennganh, tt.tentinh, m.tenmucluong, l.tenloai,
                nd.hoten, nd.email, nd.sodienthoai, nd.xacminh,
                nhatd.tencongty, nhatd.diachi_congty, nhatd.website, nhatd.logo, 
                nhatd.mota as motacongty, nhatd.quymo, nhatd.linhvuc
                FROM " . $this->table . " t
                LEFT JOIN nganhnghe n ON t.nganhnghe_id = n.id
                LEFT JOIN tinhthanh tt ON t.tinhthanh_id = tt.id
                LEFT JOIN mucluong m ON t.mucluong_id = m.id
                LEFT JOIN loaicongviec l ON t.loaicongviec_id = l.id
                LEFT JOIN nguoidung nd ON t.nguoidung_id = nd.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id
                WHERE t.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function capNhat($id, $data) {
        $sql = "UPDATE " . $this->table . " 
                SET tieude = :tieude,
                    nganhnghe_id = :nganhnghe_id,
                    mucluong_id = :mucluong_id,
                    loaicongviec_id = :loaicongviec_id,
                    tinhthanh_id = :tinhthanh_id,
                    diachilamviec = :diachilamviec,
                    soluong = :soluong,
                    gioitinh_yc = :gioitinh_yc,
                    mota = :mota,
                    yeucau = :yeucau,
                    quyenloi = :quyenloi,
                    ngayhethan = :ngayhethan
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':tieude', $data['tieude']);
        $stmt->bindParam(':nganhnghe_id', $data['nganhnghe_id']);
        $stmt->bindParam(':mucluong_id', $data['mucluong_id']);
        $stmt->bindParam(':loaicongviec_id', $data['loaicongviec_id']);
        $stmt->bindParam(':tinhthanh_id', $data['tinhthanh_id']);
        $stmt->bindParam(':diachilamviec', $data['diachilamviec']);
        $stmt->bindParam(':soluong', $data['soluong']);
        $stmt->bindParam(':gioitinh_yc', $data['gioitinh_yc']);
        $stmt->bindParam(':mota', $data['mota']);
        $stmt->bindParam(':yeucau', $data['yeucau']);
        $stmt->bindParam(':quyenloi', $data['quyenloi']);
        $stmt->bindParam(':ngayhethan', $data['ngayhethan']);
        
        return $stmt->execute();
    }

    public function capNhatTrangThai($id, $trangthai) {
        $sql = "UPDATE " . $this->table . " SET trangthai = :trangthai WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':trangthai', $trangthai);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function xoa($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function tangLuotXem($id) {
        $sql = "UPDATE " . $this->table . " SET luotxem = luotxem + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }

    public function timKiem($tukhoa = '', $nganhnghe = null, $tinhthanh = null, $mucluong = null, $loaicongviec = null, $limit = 20, $offset = 0) {
        $sql = "SELECT t.*, n.tennganh, tt.tentinh, m.tenmucluong, l.tenloai,
                nhatd.tencongty, nhatd.logo
                FROM " . $this->table . " t
                LEFT JOIN nganhnghe n ON t.nganhnghe_id = n.id
                LEFT JOIN tinhthanh tt ON t.tinhthanh_id = tt.id
                LEFT JOIN mucluong m ON t.mucluong_id = m.id
                LEFT JOIN loaicongviec l ON t.loaicongviec_id = l.id
                LEFT JOIN nguoidung nd ON t.nguoidung_id = nd.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id
                WHERE t.trangthai = 'dangmo' AND t.ngayhethan >= CURDATE()";
        
        if (!empty($tukhoa)) {
            $sql .= " AND (t.tieude LIKE :tukhoa1 OR nhatd.tencongty LIKE :tukhoa2 OR n.tennganh LIKE :tukhoa3)";
        }
        
        if ($nganhnghe) {
            $sql .= " AND t.nganhnghe_id = :nganhnghe";
        }
        
        if ($tinhthanh) {
            $sql .= " AND t.tinhthanh_id = :tinhthanh";
        }
        
        if ($mucluong) {
            $sql .= " AND t.mucluong_id = :mucluong";
        }
        
        if ($loaicongviec) {
            $sql .= " AND t.loaicongviec_id = :loaicongviec";
        }
        
        $sql .= " ORDER BY t.ngaydang DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($tukhoa)) {
            $searchTerm = "%{$tukhoa}%";
            $stmt->bindParam(':tukhoa1', $searchTerm);
            $stmt->bindParam(':tukhoa2', $searchTerm);
            $stmt->bindParam(':tukhoa3', $searchTerm);
        }
        
        if ($nganhnghe) {
            $stmt->bindParam(':nganhnghe', $nganhnghe, PDO::PARAM_INT);
        }
        
        if ($tinhthanh) {
            $stmt->bindParam(':tinhthanh', $tinhthanh, PDO::PARAM_INT);
        }
        
        if ($mucluong) {
            $stmt->bindParam(':mucluong', $mucluong, PDO::PARAM_INT);
        }
        
        if ($loaicongviec) {
            $stmt->bindParam(':loaicongviec', $loaicongviec, PDO::PARAM_INT);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function demTimKiem($tukhoa = '', $nganhnghe = null, $tinhthanh = null, $mucluong = null, $loaicongviec = null) {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table . " t
                LEFT JOIN nganhnghe n ON t.nganhnghe_id = n.id
                LEFT JOIN thongtinnhatuyendung nhatd ON t.nguoidung_id = nhatd.nguoidung_id
                WHERE t.trangthai = 'dangmo' AND t.ngayhethan >= CURDATE()";
        
        if (!empty($tukhoa)) {
            $sql .= " AND (t.tieude LIKE :tukhoa1 OR nhatd.tencongty LIKE :tukhoa2 OR n.tennganh LIKE :tukhoa3)";
        }
        
        if ($nganhnghe) {
            $sql .= " AND t.nganhnghe_id = :nganhnghe";
        }
        
        if ($tinhthanh) {
            $sql .= " AND t.tinhthanh_id = :tinhthanh";
        }
        
        if ($mucluong) {
            $sql .= " AND t.mucluong_id = :mucluong";
        }
        
        if ($loaicongviec) {
            $sql .= " AND t.loaicongviec_id = :loaicongviec";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if (!empty($tukhoa)) {
            $searchTerm = "%{$tukhoa}%";
            $stmt->bindParam(':tukhoa1', $searchTerm);
            $stmt->bindParam(':tukhoa2', $searchTerm);
            $stmt->bindParam(':tukhoa3', $searchTerm);
        }
        
        if ($nganhnghe) {
            $stmt->bindParam(':nganhnghe', $nganhnghe, PDO::PARAM_INT);
        }
        
        if ($tinhthanh) {
            $stmt->bindParam(':tinhthanh', $tinhthanh, PDO::PARAM_INT);
        }
        
        if ($mucluong) {
            $stmt->bindParam(':mucluong', $mucluong, PDO::PARAM_INT);
        }
        
        if ($loaicongviec) {
            $stmt->bindParam(':loaicongviec', $loaicongviec, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result['total'];
    }

    public function layDanhSachMoiNhat($limit = 12) {
        $sql = "SELECT t.*, n.tennganh, tt.tentinh, m.tenmucluong, 
                nd.hoten as tentuyendung, nhatd.tencongty, nhatd.logo
                FROM " . $this->table . " t
                LEFT JOIN nganhnghe n ON t.nganhnghe_id = n.id
                LEFT JOIN tinhthanh tt ON t.tinhthanh_id = tt.id
                LEFT JOIN mucluong m ON t.mucluong_id = m.id
                LEFT JOIN nguoidung nd ON t.nguoidung_id = nd.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id
                WHERE t.trangthai = 'dangmo' AND t.ngayhethan >= CURDATE()
                ORDER BY t.ngaydang DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function layTatCa($limit = null, $offset = 0, $trangthai = null) {
        $sql = "SELECT t.*, n.tennganh, tt.tentinh, m.tenmucluong,
                nd.hoten, nhatd.tencongty,
                (SELECT COUNT(*) FROM donungtuyen WHERE tintuyendung_id = t.id) as sodon
                FROM " . $this->table . " t
                LEFT JOIN nganhnghe n ON t.nganhnghe_id = n.id
                LEFT JOIN tinhthanh tt ON t.tinhthanh_id = tt.id
                LEFT JOIN mucluong m ON t.mucluong_id = m.id
                LEFT JOIN nguoidung nd ON t.nguoidung_id = nd.id
                LEFT JOIN thongtinnhatuyendung nhatd ON nd.id = nhatd.nguoidung_id";
        
        if ($trangthai) {
            $sql .= " WHERE t.trangthai = :trangthai";
        }
        
        $sql .= " ORDER BY t.ngaydang DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($trangthai) {
            $stmt->bindParam(':trangthai', $trangthai);
        }
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function demTatCa($trangthai = null) {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;
        
        if ($trangthai) {
            $sql .= " WHERE trangthai = :trangthai";
        }
        
        $stmt = $this->db->prepare($sql);
        
        if ($trangthai) {
            $stmt->bindParam(':trangthai', $trangthai);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function demTheoNguoiDung($nguoidungId) {
        $sql = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE nguoidung_id = :nguoidung_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nguoidung_id', $nguoidungId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function giaHan($id, $ngayHetHanMoi) {
        $sql = "UPDATE " . $this->table . " 
                SET ngayhethan = :ngayhethan, 
                    trangthai = 'choduyet'
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':ngayhethan', $ngayHetHanMoi);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
?>
