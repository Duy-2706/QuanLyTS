<?php
class PhieuThanhLy {
    private $conn;
    private $table_name = "phieu_thanh_ly";

    public $phieu_thanh_ly_id;
    public $ngay_tao;
    public $ghi_chu;
    public $ngay_xac_nhan;
    public $user_id;
    public $trang_thai;
    public $nguoi_duyet_id;
    public $ngay_thanh_ly;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {   // lấy tất cả 
        $query = "SELECT ptl.*, u.ten AS user_name, u.user_id
                  FROM " . $this->table_name . " ptl
                  LEFT JOIN users u ON ptl.user_id = u.user_id
                  ORDER BY ptl.ngay_tao DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readAllPaginated($page = 1, $recordsPerPage = 10) {
        $start = ($page - 1) * $recordsPerPage;
        $query = "SELECT pn.*, u.ten AS user_name 
                  FROM " . $this->table_name . " pn
                  LEFT JOIN users u ON pn.user_id = u.user_id
                  ORDER BY pn.ngay_tao DESC
                  LIMIT :start, :records";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":start", $start, PDO::PARAM_INT);
        $stmt->bindParam(":records", $recordsPerPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readById($id) {
        $query = "SELECT ptl.*, u.ten AS user_name , usr.ten AS nguoi_duyet_name
                  FROM " . $this->table_name ." ptl
                  LEFT JOIN users u ON ptl.user_id = u.user_id
                  LEFT JOIN users usr ON ptl.user_duyet_id = usr.user_id
                  WHERE ptl.phieu_thanh_ly_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function readTai_San() {
        $query = "SELECT *
                  FROM tai_san ts
                  INNER JOIN vi_tri_chi_tiet vtct ON vtct.tai_san_id = ts.tai_san_id
                  WHERE vi_tri_id = ?";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute([1]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function create() {
       $query = "INSERT INTO " . $this->table_name . " 
                  SET ngay_tao=:ngay_tao, ghi_chu=:ghi_chu, user_id=:user_id, trang_thai=:trang_thai";

        $stmt = $this->conn->prepare($query);

        $this->ngay_tao = htmlspecialchars(strip_tags($this->ngay_tao));
        // $this->ngay_xac_nhan = htmlspecialchars(strip_tags($this->ngay_xac_nhan));
        $this->ghi_chu = htmlspecialchars(strip_tags($this->ghi_chu));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));

        $stmt->bindParam(':ngay_tao', $this->ngay_tao);
        // $stmt->bindParam(':ngay_xac_nhan', $this->ngay_xac_nhan);
        $stmt->bindParam(':ghi_chu', $this->ghi_chu);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':trang_thai', $this->trang_thai);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET ghi_chu=:ghi_chu, trang_thai=:trang_thai 
                  WHERE phieu_thanh_ly_id=:phieu_thanh_ly_id";

        $stmt = $this->conn->prepare($query);

        // $this->ngay_tao = htmlspecialchars(strip_tags($this->ngay_tao));
        $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));
        $this->phieu_thanh_ly_id= htmlspecialchars(strip_tags($this->phieu_thanh_ly_id));

        // $stmt->bindParam(':ngay_tao', $this->ngay_tao);
        // $stmt->bindParam(':ngay_xac_nhan', $this->ngay_xac_nhan);
        $stmt->bindParam(':ghi_chu', $this->ghi_chu);
        $stmt->bindParam(':trang_thai', $this->trang_thai);
        $stmt->bindParam(':phieu_thanh_ly_id', $this->phieu_thanh_ly_id);

        return $stmt->execute();
    }

     public function delete($id) {
            $deleteHoaDonQuery = "DELETE FROM " . $this->table_name . " WHERE phieu_thanh_ly_id = ?";
            $stmtDeleteHoaDon = $this->conn->prepare($deleteHoaDonQuery);
            $stmtDeleteHoaDon->bindParam(1, $id);
            $stmtDeleteHoaDon->execute();    
    }

    public function updateStatusPheDuyet() {
        $query = "UPDATE " . $this->table_name . " 
                  SET trang_thai=:trang_thai, ghi_chu=:ghi_chu, user_duyet_id=:nguoi_duyet_id, ngay_xac_nhan=:ngay_xac_nhan 
                  WHERE phieu_thanh_ly_id=:phieu_thanh_ly_id";

        $stmt = $this->conn->prepare($query);

        $this->ghi_chu = htmlspecialchars(strip_tags($this->ghi_chu));
        $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));
        $this->phieu_thanh_ly_id= htmlspecialchars(strip_tags($this->phieu_thanh_ly_id));
        $this->nguoi_duyet_id= htmlspecialchars(strip_tags($this->nguoi_duyet_id));
        $this->ngay_xac_nhan = htmlspecialchars($this->ngay_xac_nhan);


        $stmt->bindParam(':ghi_chu', $this->ghi_chu);
        $stmt->bindParam(':trang_thai', $this->trang_thai);
        $stmt->bindParam(':phieu_thanh_ly_id', $this->phieu_thanh_ly_id);
        $stmt->bindParam(':nguoi_duyet_id', $this->nguoi_duyet_id);
        $stmt->bindParam(':ngay_xac_nhan', $this->ngay_xac_nhan);

        return $stmt->execute();
    }
   
    public function updateStatusThanhLy() {
        $query = "UPDATE " . $this->table_name . " 
                  SET trang_thai=:trang_thai, ghi_chu=:ghi_chu, user_duyet_id=:nguoi_duyet_id, ngay_thanh_ly=:ngay_thanh_ly 
                  WHERE phieu_thanh_ly_id=:phieu_thanh_ly_id";

        $stmt = $this->conn->prepare($query);

        $this->ghi_chu = htmlspecialchars(strip_tags($this->ghi_chu));
        $this->trang_thai = htmlspecialchars(strip_tags($this->trang_thai));
        $this->phieu_thanh_ly_id= htmlspecialchars(strip_tags($this->phieu_thanh_ly_id));
        $this->nguoi_duyet_id= htmlspecialchars(strip_tags($this->nguoi_duyet_id));
        $this->ngay_thanh_ly = htmlspecialchars($this->ngay_thanh_ly);


        $stmt->bindParam(':ghi_chu', $this->ghi_chu);
        $stmt->bindParam(':trang_thai', $this->trang_thai);
        $stmt->bindParam(':phieu_thanh_ly_id', $this->phieu_thanh_ly_id);
        $stmt->bindParam(':nguoi_duyet_id', $this->nguoi_duyet_id);
        $stmt->bindParam(':ngay_thanh_ly', $this->ngay_thanh_ly);

        return $stmt->execute();
    }

    // public function getTotalRecords() {
    //     $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    //     $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //     return $row['total'];
    // }

    // public function search($searchTerm, $page = 1, $recordsPerPage = 10) {
    //     $start = ($page - 1) * $recordsPerPage;
    //     $query = "SELECT pn.*, u.ten AS user_name 
    //               FROM " . $this->table_name . " pn
    //               LEFT JOIN users u ON pn.user_id = u.user_id
    //               WHERE pn.ngay_tao LIKE :search 
    //                  OR u.ten LIKE :search
    //               ORDER BY pn.ngay_tao DESC
    //               LIMIT :start, :records";
        
    //     $stmt = $this->conn->prepare($query);
    //     $searchTerm = "%{$searchTerm}%";
    //     $stmt->bindParam(":search", $searchTerm);
    //     $stmt->bindParam(":start", $start, PDO::PARAM_INT);
    //     $stmt->bindParam(":records", $recordsPerPage, PDO::PARAM_INT);
    //     $stmt->execute();

    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    // public function generateReport($startDate, $endDate) {
    //     $query = "SELECT pn.*, u.ten AS user_name 
    //               FROM " . $this->table_name . " pn
    //               LEFT JOIN users u ON pn.user_id = u.user_id
    //               WHERE pn.ngay_tao BETWEEN :start_date AND :end_date
    //               ORDER BY pn.ngay_tao ASC";
        
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(":start_date", $startDate);
    //     $stmt->bindParam(":end_date", $endDate);
    //     $stmt->execute();

    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    // public function getTotalInvoices() {
    //     $query = "SELECT COUNT(*) as total FROM phieu_nhap_tai_san";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    // }

    // public function getTotalValue() {
    //     $query = "SELECT SUM(tong_gia_tri) as total FROM phieu_nhap_tai_san";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    // }
}
?>
