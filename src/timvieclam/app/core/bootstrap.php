<?php
/**
 * Bootstrap file - Initialize application
 */

// Load configuration
require_once dirname(__DIR__, 2) . '/config/config.php';

// Load helper functions
require_once __DIR__ . '/helpers.php';

// Initialize session
Session::start();

// Error handling
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (DEBUG_MODE) {
        echo "<b>Error:</b> [$errno] $errstr in $errfile on line $errline<br>";
    }
    error_log("Error: [$errno] $errstr in $errfile on line $errline");
    return false;
});

set_exception_handler(function($exception) {
    if (DEBUG_MODE) {
        echo "<b>Exception:</b> " . $exception->getMessage() . "<br>";
        echo "<b>File:</b> " . $exception->getFile() . "<br>";
        echo "<b>Line:</b> " . $exception->getLine() . "<br>";
        echo "<pre>" . $exception->getTraceAsString() . "</pre>";
    } else {
        http_response_code(500);
        echo "Có lỗi xảy ra. Vui lòng thử lại sau.";
    }
    error_log($exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
});
