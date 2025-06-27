<?php
require_once 'database.php';

class LoadData {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function runSQLFromFile($filePath) {
        try {
            $sql = file_get_contents($filePath);
            if ($sql === false) {
                throw new Exception("Không thể đọc file SQL: $filePath");
            }
            $this->db->exec($sql);
            echo "Dữ liệu đã được nhập thành công!";
        } catch(PDOException $e) {
            echo "Lỗi thực thi SQL: " . $e->getMessage();
        } catch(Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
}

$loader = new LoadData();
$loader->runSQLFromFile('D:\xampp\htdocs\QuanLyTS\database\data.sql'); // Đường dẫn tới file data.sql
?>