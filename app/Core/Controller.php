<?php

class Controller {
    protected $data = [];
    
    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    protected function view($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        
        // Extract data to make variables available in views
        extract($this->data);
        
        // Construct view path
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new Exception("View not found: $view");
        }
        
        // Include the view
        require $viewPath;
    }
    
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    protected function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/users/login');
        }
    }
    
    protected function requireGuest() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
    }
    
    protected function getPostData($key = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? null;
    }
    
    protected function getQueryData($key = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? null;
    }
    
    protected function setFlashMessage($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }
    
    protected function getFlashMessage($type) {
        $message = $_SESSION['flash'][$type] ?? null;
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            foreach ($fieldRules as $rule => $ruleValue) {
                $value = $data[$field] ?? null;
                
                switch ($rule) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$field][] = "$field is required";
                        }
                        break;
                    case 'email':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = "$field must be a valid email";
                        }
                        break;
                    case 'min':
                        if (!empty($value) && strlen($value) < $ruleValue) {
                            $errors[$field][] = "$field must be at least $ruleValue characters";
                        }
                        break;
                    case 'max':
                        if (!empty($value) && strlen($value) > $ruleValue) {
                            $errors[$field][] = "$field must not exceed $ruleValue characters";
                        }
                        break;
                }
            }
        }
        
        return $errors;
    }
}
