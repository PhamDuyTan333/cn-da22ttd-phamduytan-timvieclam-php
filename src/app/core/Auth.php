<?php

class Auth {
    
    /**
     * Check if user is authenticated
     */
    public static function check() {
        return Session::has('user_id');
    }
    
    /**
     * Get current user ID
     */
    public static function id() {
        return Session::get('user_id');
    }
    
    /**
     * Get current user role
     */
    public static function role() {
        return Session::get('vaitro');
    }
    
    /**
     * Check if user is admin
     */
    public static function isAdmin() {
        return self::role() === ROLE_ADMIN;
    }
    
    /**
     * Check if user is employer
     */
    public static function isNhaTuyenDung() {
        return self::role() === ROLE_NHA_TUYEN_DUNG;
    }
    
    /**
     * Check if user is candidate
     */
    public static function isUngVien() {
        return self::role() === ROLE_UNG_VIEN;
    }
    
    /**
     * Login user
     */
    public static function login($userId, $role, $email = null) {
        Session::regenerate();
        Session::set('user_id', $userId);
        Session::set('vaitro', $role);
        if ($email) {
            Session::set('email', $email);
        }
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        Session::destroy();
    }
    
    /**
     * Check if user has permission
     */
    public static function hasRole($role) {
        return self::role() === $role;
    }
    
    /**
     * Require authentication
     */
    public static function requireLogin() {
        if (!self::check()) {
            header('Location: ' . BASE_URL . 'dangnhap');
            exit;
        }
    }
    
    /**
     * Require specific role
     */
    public static function requireRole($role) {
        self::requireLogin();
        
        if (!self::hasRole($role)) {
            http_response_code(403);
            die("Bạn không có quyền truy cập");
        }
    }
    
    /**
     * Require admin role
     */
    public static function requireAdmin() {
        self::requireRole(ROLE_ADMIN);
    }
}
