<?php

class BaseController {
    
    public function __construct() {
        // Constructor for child classes
    }
    
    /**
     * Load view file
     */
    protected function view($viewPath, $data = []) {
        extract($data);
        
        // Load header
        require_once BASE_PATH . 'app/views/layouts/header.php';
        
        // Load main view
        $viewFile = BASE_PATH . 'app/views/' . $viewPath . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: " . $viewPath);
        }
        
        // Load footer
        require_once BASE_PATH . 'app/views/layouts/footer.php';
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit;
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Check if user is logged in
     */
    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Check if user has specific role
     */
    protected function hasRole($role) {
        return isset($_SESSION['vaitro']) && $_SESSION['vaitro'] === $role;
    }
    
    /**
     * Require login
     */
    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->redirect('dangnhap');
        }
    }
    
    /**
     * Require specific role
     */
    protected function requireRole($role) {
        $this->requireLogin();
        
        if (!$this->hasRole($role)) {
            http_response_code(403);
            die("Access denied");
        }
    }
    
    /**
     * Get current user ID
     */
    protected function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role
     */
    protected function getUserRole() {
        return $_SESSION['vaitro'] ?? null;
    }
    
    /**
     * Set flash message
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }
    
    /**
     * Get and clear flash message
     */
    protected function getFlash($type) {
        if (isset($_SESSION['flash'][$type])) {
            $message = $_SESSION['flash'][$type];
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        return null;
    }
    
    /**
     * Upload file with security validation
     * @param array $file - $_FILES['field_name']
     * @param string $uploadPath - Full path to upload directory
     * @param array $allowedTypes - MIME types array
     * @param int $maxSize - Max file size in bytes (default 5MB)
     * @return array - ['success' => bool, 'filename' => string, 'error' => string]
     */
    protected function uploadFile($file, $uploadPath, $allowedTypes, $maxSize = 5242880) {
        // Kiểm tra upload error
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Lỗi upload file'];
        }
        
        // Kiểm tra file size
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'File vượt quá dung lượng cho phép'];
        }
        
        // Kiểm tra MIME type thực tế (không tin tưởng client)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'error' => 'Định dạng file không được phép'];
        }
        
        // Sanitize filename - chống path traversal
        $originalName = basename($file['name']);
        $originalName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
        $fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        
        // Tạo tên file unique
        $newFileName = uniqid() . '_' . time() . '.' . $fileExt;
        
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        $fullPath = $uploadPath . $newFileName;
        
        // Move file và verify
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            // Set permissions
            chmod($fullPath, 0644);
            return ['success' => true, 'filename' => $newFileName];
        }
        
        return ['success' => false, 'error' => 'Không thể lưu file'];
    }
    
    /**
     * Sanitize input - prevent XSS
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Check if user has required role
     */
    protected function checkRole($allowedRoles) {
        if (!isset($_SESSION['nguoidung_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập';
            $this->redirect('dangnhap');
        }
        
        $allowedRoles = is_array($allowedRoles) ? $allowedRoles : [$allowedRoles];
        
        if (!in_array($_SESSION['vaitro'], $allowedRoles)) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập';
            $this->redirect('');
        }
    }
    
    /**
     * Validate email
     */
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate phone number (Vietnam format)
     */
    protected function validatePhone($phone) {
        return preg_match('/^(0|\+84)[0-9]{9,10}$/', $phone);
    }
    
    /**
     * Validate URL
     */
    protected function validateUrl($url) {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
    
    /**
     * Validate date format
     */
    protected function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Generate random token
     */
    protected function generateToken($length = 32) {
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Generate pagination data
     */
    protected function paginate($totalRecords, $currentPage = 1, $recordsPerPage = 10) {
        $totalPages = ceil($totalRecords / $recordsPerPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        
        return [
            'total_records' => $totalRecords,
            'total_pages' => $totalPages,
            'current_page' => $currentPage,
            'records_per_page' => $recordsPerPage,
            'has_previous' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages,
            'previous_page' => $currentPage > 1 ? $currentPage - 1 : null,
            'next_page' => $currentPage < $totalPages ? $currentPage + 1 : null
        ];
    }
    
    /**
     * Delete a file safely
     */
    protected function deleteFile($filePath) {
        if (file_exists($filePath) && is_file($filePath)) {
            return @unlink($filePath);
        }
        return false;
    }
}
