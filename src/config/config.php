<?php

define('BASE_URL', 'http://localhost:81/timvieclam/');
define('BASE_PATH', dirname(__DIR__) . '/');

date_default_timezone_set('Asia/Ho_Chi_Minh');

define('MAX_FILE_SIZE', 5 * 1024 * 1024);
define('UPLOAD_PATH', BASE_PATH . 'public/uploads/');
define('CV_PATH', UPLOAD_PATH . 'cv/');
define('AVATAR_PATH', UPLOAD_PATH . 'avatar/');
define('LOGO_PATH', UPLOAD_PATH . 'logo/');

define('TIN_TUYENDUNG_HAN', 30);

define('ALLOWED_CV_TYPES', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/jpg']);

define('ROLE_ADMIN', 'admin');
define('ROLE_UNGVIEN', 'ungvien');
define('ROLE_TUYENDUNG', 'tuyendung');
define('ROLE_NHA_TUYEN_DUNG', 'tuyendung');
define('ROLE_UNG_VIEN', 'ungvien');
define('ROLE_CHODUYET', 'choduyet');

define('TRANGTHAI_DANGXEM', 'dangxem');
define('TRANGTHAI_PHONGVAN', 'phongvan');
define('TRANGTHAI_TUCHOI', 'tuchoi');
define('TRANGTHAI_NHANVIEC', 'nhanviec');

define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Load core classes
require_once BASE_PATH . 'app/core/Database.php';
require_once BASE_PATH . 'app/core/BaseController.php';
require_once BASE_PATH . 'app/core/Session.php';
require_once BASE_PATH . 'app/core/Auth.php';
require_once BASE_PATH . 'app/core/Validator.php';
require_once BASE_PATH . 'app/core/Router.php';
require_once BASE_PATH . 'app/core/RateLimit.php';

spl_autoload_register(function ($class_name) {
    $directories = [
        BASE_PATH . 'app/models/',
        BASE_PATH . 'app/controllers/',
        BASE_PATH . 'app/core/',
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once BASE_PATH . 'config/database.php';
?>
