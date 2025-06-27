<?php
require_once 'database.php';

class Setup {
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
            echo "Các câu lệnh SQL đã được thực thi thành công!";
        } catch(PDOException $e) {
            echo "Lỗi thực thi SQL: " . $e->getMessage();
        } catch(Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
}

$setup = new Setup();
$setup->runSQLFromFile('D:\xampp\htdocs\QuanLyTS\database\schema.sql'); // Đường dẫn tới file schema.sql
?>
