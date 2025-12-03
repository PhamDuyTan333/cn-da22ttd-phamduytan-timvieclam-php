<?php

class Session {
    
    /**
     * Start session if not started with security settings
     */
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            // Session security settings
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_samesite', 'Strict');
            
            // Enable strict mode
            ini_set('session.use_strict_mode', 1);
            
            // Only enable secure flag in production with HTTPS
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', 1);
            }
            
            session_start();
            
            // Regenerate session ID periodically (every 5 minutes)
            if (!isset($_SESSION['last_regeneration'])) {
                $_SESSION['last_regeneration'] = time();
            } elseif (time() - $_SESSION['last_regeneration'] > 300) {
                session_regenerate_id(true);
                $_SESSION['last_regeneration'] = time();
            }
            
            // Validate session fingerprint
            self::validateFingerprint();
        }
    }
    
    /**
     * Validate session fingerprint to prevent session hijacking
     */
    private static function validateFingerprint() {
        $fingerprint = md5(
            $_SERVER['HTTP_USER_AGENT'] ?? '' .
            $_SERVER['REMOTE_ADDR'] ?? ''
        );
        
        if (!isset($_SESSION['fingerprint'])) {
            $_SESSION['fingerprint'] = $fingerprint;
        } elseif ($_SESSION['fingerprint'] !== $fingerprint) {
            // Session hijacking detected
            self::destroy();
            http_response_code(403);
            die('Session validation failed');
        }
    }
    
    /**
     * Set session value
     */
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session value
     */
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session key exists
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session key
     */
    public static function remove($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Destroy session
     */
    public static function destroy() {
        self::start();
        session_unset();
        session_destroy();
    }
    
    /**
     * Set flash message
     */
    public static function flash($key, $value = null) {
        self::start();
        
        if ($value === null) {
            // Get flash message
            if (isset($_SESSION['flash'][$key])) {
                $message = $_SESSION['flash'][$key];
                unset($_SESSION['flash'][$key]);
                return $message;
            }
            return null;
        } else {
            // Set flash message
            $_SESSION['flash'][$key] = $value;
        }
    }
    
    /**
     * Regenerate session ID
     */
    public static function regenerate() {
        self::start();
        session_regenerate_id(true);
    }
}
