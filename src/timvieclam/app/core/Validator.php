<?php

class Validator {
    private $errors = [];
    private $data = [];
    
    public function __construct($data) {
        $this->data = $data;
    }
    
    /**
     * Check if field is required
     */
    public function required($field, $message = null) {
        if (empty($this->data[$field])) {
            $this->errors[$field] = $message ?? "Trường {$field} là bắt buộc";
        }
        return $this;
    }
    
    /**
     * Check email format
     */
    public function email($field, $message = null) {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message ?? "Email không hợp lệ";
        }
        return $this;
    }
    
    /**
     * Check minimum length
     */
    public function min($field, $length, $message = null) {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field] = $message ?? "Trường {$field} phải có ít nhất {$length} ký tự";
        }
        return $this;
    }
    
    /**
     * Check maximum length
     */
    public function max($field, $length, $message = null) {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field] = $message ?? "Trường {$field} không được vượt quá {$length} ký tự";
        }
        return $this;
    }
    
    /**
     * Check if value matches another field
     */
    public function match($field, $matchField, $message = null) {
        if (!empty($this->data[$field]) && $this->data[$field] !== $this->data[$matchField]) {
            $this->errors[$field] = $message ?? "Trường {$field} không khớp với {$matchField}";
        }
        return $this;
    }
    
    /**
     * Check if value is numeric
     */
    public function numeric($field, $message = null) {
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = $message ?? "Trường {$field} phải là số";
        }
        return $this;
    }
    
    /**
     * Check if value is in array
     */
    public function in($field, $values, $message = null) {
        if (!empty($this->data[$field]) && !in_array($this->data[$field], $values)) {
            $this->errors[$field] = $message ?? "Giá trị của {$field} không hợp lệ";
        }
        return $this;
    }
    
    /**
     * Custom validation
     */
    public function custom($field, $callback, $message) {
        if (!empty($this->data[$field]) && !$callback($this->data[$field])) {
            $this->errors[$field] = $message;
        }
        return $this;
    }
    
    /**
     * Check if validation passed
     */
    public function passes() {
        return empty($this->errors);
    }
    
    /**
     * Check if validation failed
     */
    public function fails() {
        return !$this->passes();
    }
    
    /**
     * Get all errors
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get first error
     */
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
}
