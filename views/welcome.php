<?php
$controller= new Controller();
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'Admin':
            $controller=new UserController();
            $controller->statistics();
            break;
        case 'NhanVien':
            
            break;
        case 'KyThuat':
            
            break;
        default:
           
            break;
    }
}
?>