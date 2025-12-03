<?php

class RateLimit {
    
    /**
     * Check if action is rate limited
     * 
     * @param string $action - Action name (e.g., 'login', 'register')
     * @param int $maxAttempts - Maximum attempts allowed
     * @param int $timeWindow - Time window in seconds
     * @return bool - True if allowed, false if rate limited
     */
    public static function check($action, $maxAttempts = 5, $timeWindow = 300) {
        Session::start();
        
        $key = $action . '_' . self::getIdentifier();
        
        if (!isset($_SESSION['rate_limit'][$key])) {
            $_SESSION['rate_limit'][$key] = [
                'attempts' => 0,
                'first_attempt' => time(),
                'blocked_until' => 0
            ];
        }
        
        $data = &$_SESSION['rate_limit'][$key];
        
        // Check if currently blocked
        if ($data['blocked_until'] > time()) {
            return false;
        }
        
        // Reset if time window expired
        if (time() - $data['first_attempt'] > $timeWindow) {
            $data['attempts'] = 0;
            $data['first_attempt'] = time();
            $data['blocked_until'] = 0;
        }
        
        $data['attempts']++;
        
        // Block if exceeded
        if ($data['attempts'] > $maxAttempts) {
            $data['blocked_until'] = time() + $timeWindow;
            return false;
        }
        
        return true;
    }
    
    /**
     * Reset rate limit for specific action
     */
    public static function reset($action) {
        Session::start();
        $key = $action . '_' . self::getIdentifier();
        
        if (isset($_SESSION['rate_limit'][$key])) {
            unset($_SESSION['rate_limit'][$key]);
        }
    }
    
    /**
     * Get remaining attempts
     */
    public static function getRemainingAttempts($action, $maxAttempts = 5) {
        Session::start();
        $key = $action . '_' . self::getIdentifier();
        
        if (!isset($_SESSION['rate_limit'][$key])) {
            return $maxAttempts;
        }
        
        $attempts = $_SESSION['rate_limit'][$key]['attempts'];
        return max(0, $maxAttempts - $attempts);
    }
    
    /**
     * Get time until unblocked
     */
    public static function getBlockedTime($action) {
        Session::start();
        $key = $action . '_' . self::getIdentifier();
        
        if (!isset($_SESSION['rate_limit'][$key])) {
            return 0;
        }
        
        $blockedUntil = $_SESSION['rate_limit'][$key]['blocked_until'];
        return max(0, $blockedUntil - time());
    }
    
    /**
     * Get unique identifier for user
     */
    private static function getIdentifier() {
        return md5(
            ($_SERVER['REMOTE_ADDR'] ?? '') .
            ($_SERVER['HTTP_USER_AGENT'] ?? '')
        );
    }
}
