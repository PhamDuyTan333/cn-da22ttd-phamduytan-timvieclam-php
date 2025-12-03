<?php

class Router {
    private $routes = [];
    private $namedRoutes = [];
    
    /**
     * Add GET route
     */
    public function get($path, $callback, $name = null) {
        $this->addRoute('GET', $path, $callback, $name);
        return $this;
    }
    
    /**
     * Add POST route
     */
    public function post($path, $callback, $name = null) {
        $this->addRoute('POST', $path, $callback, $name);
        return $this;
    }
    
    /**
     * Add PUT route
     */
    public function put($path, $callback, $name = null) {
        $this->addRoute('PUT', $path, $callback, $name);
        return $this;
    }
    
    /**
     * Add DELETE route
     */
    public function delete($path, $callback, $name = null) {
        $this->addRoute('DELETE', $path, $callback, $name);
        return $this;
    }
    
    /**
     * Add route
     */
    private function addRoute($method, $path, $callback, $name = null) {
        $pattern = $this->convertPathToPattern($path);
        
        $this->routes[$method][] = [
            'path' => $path,
            'pattern' => $pattern,
            'callback' => $callback,
            'name' => $name
        ];
        
        if ($name) {
            $this->namedRoutes[$name] = $path;
        }
    }
    
    /**
     * Convert path to regex pattern
     */
    private function convertPathToPattern($path) {
        // Convert {param} to regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
    
    /**
     * Dispatch request
     */
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_GET['url'] ?? '';
        $uri = trim($uri, '/');
        
        if (!isset($this->routes[$method])) {
            $this->notFound();
            return;
        }
        
        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                return $this->executeCallback($route['callback'], $params);
            }
        }
        
        $this->notFound();
    }
    
    /**
     * Execute callback
     */
    private function executeCallback($callback, $params) {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }
        
        if (is_string($callback)) {
            list($controller, $method) = explode('@', $callback);
            
            $controllerPath = BASE_PATH . 'app/controllers/' . $controller . '.php';
            
            if (file_exists($controllerPath)) {
                require_once $controllerPath;
                
                if (class_exists($controller)) {
                    $controllerInstance = new $controller();
                    
                    if (method_exists($controllerInstance, $method)) {
                        return call_user_func_array([$controllerInstance, $method], $params);
                    }
                }
            }
        }
        
        $this->notFound();
    }
    
    /**
     * 404 Not Found
     */
    private function notFound() {
        http_response_code(404);
        require_once BASE_PATH . 'app/views/errors/404.php';
        exit;
    }
    
    /**
     * Get URL by route name
     */
    public function url($name, $params = []) {
        if (!isset($this->namedRoutes[$name])) {
            return '#';
        }
        
        $path = $this->namedRoutes[$name];
        
        foreach ($params as $key => $value) {
            $path = str_replace('{' . $key . '}', $value, $path);
        }
        
        return BASE_URL . trim($path, '/');
    }
}
