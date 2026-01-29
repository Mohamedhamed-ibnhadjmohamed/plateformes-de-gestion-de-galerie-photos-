<?php

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define root path
define('ROOT_PATH', dirname(__DIR__));

// Load configuration
require_once ROOT_PATH . '/config/config.php';

// Load core classes
require_once ROOT_PATH . '/app/Core/Database.php';
require_once ROOT_PATH . '/app/Core/Model.php';
require_once ROOT_PATH . '/app/Core/Controller.php';

// Load helper functions
require_once ROOT_PATH . '/app/Helpers/auth.php';
require_once ROOT_PATH . '/app/Helpers/upload.php';
require_once ROOT_PATH . '/app/Helpers/image.php';
require_once ROOT_PATH . '/app/Helpers/view_helpers.php';

// Load controllers
require_once ROOT_PATH . '/app/Controllers/AlbumController.php';
require_once ROOT_PATH . '/app/Controllers/PhotoController.php';
require_once ROOT_PATH . '/app/Controllers/UserController.php';
require_once ROOT_PATH . '/app/Controllers/FavoriteController.php';
require_once ROOT_PATH . '/app/Controllers/TagController.php';
require_once ROOT_PATH . '/app/Controllers/CommentController.php';
require_once ROOT_PATH . '/app/Controllers/AdminController.php';
require_once ROOT_PATH . '/app/Controllers/ApiController.php';

// Load models
require_once ROOT_PATH . '/app/Models/Album.php';
require_once ROOT_PATH . '/app/Models/Photo.php';
require_once ROOT_PATH . '/app/Models/User.php';
require_once ROOT_PATH . '/app/Models/Favorite.php';
require_once ROOT_PATH . '/app/Models/Tag.php';
require_once ROOT_PATH . '/app/Models/Comment.php';
require_once ROOT_PATH . '/app/Models/ActivityLog.php';

// Simple router
class Router {
    private $routes;
    
    public function __construct($routes) {
        $this->routes = $routes;
    }
    
    public function dispatch($uri) {
        // Remove query string from URI
        $uri = strtok($uri, '?');
        
        // Check for exact route match
        if (isset($this->routes[$uri])) {
            return $this->callController($this->routes[$uri]);
        }
        
        // Check for parameterized routes
        foreach ($this->routes as $route => $handler) {
            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
            $pattern = "#^$pattern$#";
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                return $this->callController($handler, $matches);
            }
        }
        
        // 404 - Not found
        http_response_code(404);
        echo "404 - Page not found";
        return;
    }
    
    private function callController($handler, $params = []) {
        list($controllerName, $methodName) = explode('@', $handler);
        
        $controllerClass = $controllerName;
        
        if (!class_exists($controllerClass)) {
            throw new Exception("Controller not found: $controllerClass");
        }
        
        $controller = new $controllerClass();
        
        if (!method_exists($controller, $methodName)) {
            throw new Exception("Method not found: $methodName in $controllerClass");
        }
        
        return call_user_func_array([$controller, $methodName], $params);
    }
}

// Get the request URI
$uri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

// Remove script name from URI if present
if (strpos($uri, $scriptName) === 0) {
    $uri = substr($uri, strlen($scriptName));
}

// Remove leading slash for consistency
$uri = '/' . ltrim($uri, '/');

// Load routes
$routes = require_once ROOT_PATH . '/config/routes.php';

// Dispatch the request
$router = new Router($routes);
$router->dispatch($uri);
