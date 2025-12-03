<?php
/**
 * Helper Functions
 */

/**
 * Escape output for security
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate URL
 */
function url($path = '') {
    return BASE_URL . ltrim($path, '/');
}

/**
 * Redirect to URL
 */
function redirect($path = '') {
    header('Location: ' . url($path));
    exit;
}

/**
 * Get old input value
 */
function old($key, $default = '') {
    return $_SESSION['old_input'][$key] ?? $default;
}

/**
 * Set old input
 */
function setOldInput($data) {
    $_SESSION['old_input'] = $data;
}

/**
 * Clear old input
 */
function clearOldInput() {
    unset($_SESSION['old_input']);
}

/**
 * Get asset URL
 */
function asset($path) {
    return BASE_URL . 'public/' . ltrim($path, '/');
}

/**
 * Format date
 */
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}

/**
 * Format datetime
 */
function formatDateTime($datetime, $format = 'd/m/Y H:i') {
    return date($format, strtotime($datetime));
}

/**
 * Time ago
 */
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return $diff . ' giây trước';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' phút trước';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' giờ trước';
    } elseif ($diff < 2592000) {
        return floor($diff / 86400) . ' ngày trước';
    } elseif ($diff < 31536000) {
        return floor($diff / 2592000) . ' tháng trước';
    } else {
        return floor($diff / 31536000) . ' năm trước';
    }
}

/**
 * Format currency VND
 */
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}

/**
 * Truncate string
 */
function truncate($string, $length = 100, $append = '...') {
    if (mb_strlen($string) <= $length) {
        return $string;
    }
    
    return mb_substr($string, 0, $length) . $append;
}

/**
 * Check if current page is active
 */
function isActive($path) {
    $currentPath = $_GET['url'] ?? '';
    return strpos($currentPath, $path) === 0 ? 'active' : '';
}

/**
 * CSRF Token generation
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF Token field for forms
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Verify CSRF Token
 */
function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die('CSRF token validation failed');
        }
    }
}

/**
 * Set security headers
 */
function set_security_headers() {
    // Prevent clickjacking attacks
    header('X-Frame-Options: SAMEORIGIN');
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Enable XSS protection in older browsers
    header('X-XSS-Protection: 1; mode=block');
    
    // Strict transport security (uncomment if using HTTPS)
    // header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    
    // Content Security Policy (adjust as needed)
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: https:;");
    
    // Referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Permissions policy
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

/**
 * Dump and die (for debugging)
 */
function dd(...$vars) {
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}
?>

