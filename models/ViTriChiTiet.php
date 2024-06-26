<?php
// models/ViTriChiTiet.php

class ViTriChiTiet {
    private $conn;
    private $table_name = "vi_tri_chi_tiet";

    public $vi_tri_chi_tiet_id;
    public $vi_tri_id;
    public $chi_tiet_id;
    public $so_luong;
    public $trong_kho;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Đọc tất cả chi tiết vị trí
    public function read() {
        $query = "SELECT vi_tri_chi_tiet.*, tai_san.ten_tai_san, vi_tri.ten_vi_tri 
                 FROM (( " . $this->table_name . "
                 INNER JOIN chi_tiet_hoa_don_mua ON vi_tri_chi_tiet.chi_tiet_id = chi_tiet_hoa_don_mua.chi_tiet_id )
                 INNER JOIN tai_san ON chi_tiet_hoa_don_mua.tai_san_id = tai_san.tai_san_id)
                 INNER JOIN vi_tri ON vi_tri.vi_tri_id = vi_tri_chi_tiet.vi_tri_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readNotKho() {
        $query = "SELECT vi_tri_chi_tiet.*, tai_san.ten_tai_san, vi_tri.ten_vi_tri 
                 FROM (( " . $this->table_name . "
                 INNER JOIN chi_tiet_hoa_don_mua ON vi_tri_chi_tiet.chi_tiet_id = chi_tiet_hoa_don_mua.chi_tiet_id )
                 INNER JOIN tai_san ON chi_tiet_hoa_don_mua.tai_san_id = tai_san.tai_san_id)
                 INNER JOIN vi_tri ON vi_tri.vi_tri_id = vi_tri_chi_tiet.vi_tri_id
                 WHERE vi_tri_chi_tiet.vi_tri_id > 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Tạo chi tiết vị trí mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET vi_tri_id=:vi_tri_id, chi_tiet_id=:chi_tiet_id, so_luong=:so_luong";

        $stmt = $this->conn->prepare($query);
        
        // sanitize
        $this->vi_tri_id = htmlspecialchars(strip_tags($this->vi_tri_id));
        $this->chi_tiet_id = htmlspecialchars(strip_tags($this->chi_tiet_id));
        $this->so_luong = htmlspecialchars(strip_tags($this->so_luong));

        // bind values
        $stmt->bindParam(':vi_tri_id', $this->vi_tri_id);
        $stmt->bindParam(':chi_tiet_id', $this->chi_tiet_id);
        $stmt->bindParam(':so_luong', $this->so_luong);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Đọc thông tin chi tiết vị trí theo ID
    public function readById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE vi_tri_chi_tiet_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Đọc thông tin chi tiết vị trí theo ID vị trí
    public function readByViTriId($vi_tri_id) {
        $query = "SELECT vi_tri_chi_tiet.*, tai_san.*, hoa_don_mua.ngay_mua
                 FROM (( " . $this->table_name . "
                 INNER JOIN chi_tiet_hoa_don_mua ON vi_tri_chi_tiet.chi_tiet_id = chi_tiet_hoa_don_mua.chi_tiet_id )
                 INNER JOIN tai_san ON chi_tiet_hoa_don_mua.tai_san_id = tai_san.tai_san_id)
                 INNER JOIN hoa_don_mua ON chi_tiet_hoa_don_mua.hoa_don_id = hoa_don_mua.hoa_don_id
                 WHERE vi_tri_chi_tiet.vi_tri_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $vi_tri_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cập nhật thông tin chi tiết vị trí
    public function update($chi_tiet_id, $vi_tri_id, $so_luong) {
        $query = "UPDATE " . $this->table_name . " SET so_luong = :so_luong WHERE chi_tiet_id = :chi_tiet_id AND vi_tri_id = :vi_tri_id";
    
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $chi_tiet_id = htmlspecialchars(strip_tags($chi_tiet_id));
        $vi_tri_id = htmlspecialchars(strip_tags($vi_tri_id));
        $so_luong = htmlspecialchars(strip_tags($so_luong));
    
        // bind values
        $stmt->bindParam(':chi_tiet_id', $chi_tiet_id);
        $stmt->bindParam(':vi_tri_id', $vi_tri_id);
        $stmt->bindParam(':so_luong', $so_luong);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Trong lớp viTriChiTiet hoặc một lớp quản lý tương ứng
    public function updateKho($chiTietID, $soLuongThayDoi) {
        // Assume there is a table or data structure for kho where vi_tri_id = 0
        $sql = "UPDATE ". $this->table_name ." SET so_luong = so_luong + :soLuongThayDoi WHERE chi_tiet_id = :chiTietID AND vi_tri_id = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':soLuongThayDoi', $soLuongThayDoi, PDO::PARAM_INT);
        $stmt->bindParam(':chiTietID', $chiTietID, PDO::PARAM_INT);
        $stmt->execute();
    }


    // Xóa chi tiết vị trí
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE vi_tri_chi_tiet_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function getQuantityByChiTietIdAndViTriId($chiTietId, $viTriId)
    {
        $query = "SELECT so_luong FROM " . $this->table_name . " WHERE chi_tiet_id = :chi_tiet_id AND vi_tri_id = :vi_tri_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':chi_tiet_id', $chiTietId);
        $stmt->bindParam(':vi_tri_id', $viTriId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['so_luong'] : null;
    }
    public function kiemTraKho($chi_tiet_id, $soLuongCanThem) {
        // Assume there is a table or data structure for kho where vi_tri_id = 0
        $sql = "SELECT so_luong FROM ". $this->table_name ." WHERE chi_tiet_id = :chi_tiet_id AND vi_tri_id = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':chi_tiet_id', $chi_tiet_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $soLuongTrongKho = $row ? $row['so_luong'] : 0;
    
        // Kiểm tra nếu số lượng trong kho đủ để thực hiện thay đổi
        return ($soLuongTrongKho + $soLuongCanThem >= 0); // Nếu cần giảm số lượng, hãy thay đổi điều kiện này phù hợp
    }

    public function checkExist($id){
        $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE vi_tri_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        if ($stmt->fetchColumn() > 0) {
            return true;
        }
        return false;
    }
}
?>
