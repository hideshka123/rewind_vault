<?php
require_once 'config.php';
require_once 'functions.php';

if (isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (empty($email) || empty($password)) {
        jsonResponse(['success' => false, 'message' => 'Заполните все поля'], 400);
    }
    
    $emailValidation = validateEmail($email);
    if (!$emailValidation['valid']) {
        jsonResponse(['success' => false, 'message' => $emailValidation['message']], 400);
    }
    
    $result = loginUser($email, $password);
    
    if ($result['success']) {
        jsonResponse(['success' => true, 'message' => 'Вход выполнен', 'redirect' => '../index.php']);
    } else {
        jsonResponse(['success' => false, 'message' => $result['message']], 400);
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rewind Vault</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-tabs">
            <a href="login.php" class="auth-tab active">Login</a>
            <a href="register.php" class="auth-tab">Sign Up</a>
        </div>
        
        <div class="auth-form-container">
            <h2>Welcome back!</h2>
            
            <form id="loginForm" class="auth-form" method="POST" action="login.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Enter your email" 
                        required
                    >
                    <span class="error-message" id="emailError"></span>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password" 
                        required
                    >
                    <span class="error-message" id="passwordError"></span>
                </div>
                
                <button type="submit" class="btn-submit">Log In</button>
            </form>
            
            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php">Sign up</a></p>
            </div>
        </div>
    </div>
    
    <script src="../js/auth.js"></script>
</body>
</html>