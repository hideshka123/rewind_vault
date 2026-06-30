<?php
require_once 'config.php';
require_once 'functions.php';

if (isLoggedIn()) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    $usernameValidation = validateUsername($username);
    if (!$usernameValidation['valid']) {
        jsonResponse(['success' => false, 'message' => $usernameValidation['message']], 400);
    }
    
    $emailValidation = validateEmail($email);
    if (!$emailValidation['valid']) {
        jsonResponse(['success' => false, 'message' => $emailValidation['message']], 400);
    }
    
    $passwordValidation = validatePassword($password, $confirmPassword);
    if (!$passwordValidation['valid']) {
        jsonResponse(['success' => false, 'message' => $passwordValidation['message']], 400);
    }
    
    if (userExistsByEmail($email)) {
        jsonResponse(['success' => false, 'message' => 'Пользователь с таким email уже существует'], 400);
    }
    
    if (userExistsByUsername($username)) {
        jsonResponse(['success' => false, 'message' => 'Пользователь с таким именем уже существует'], 400);
    }

    $result = registerUser($username, $email, $password);
    
    if ($result['success']) {
        loginUser($email, $password);
        jsonResponse(['success' => true, 'message' => 'Регистрация успешна', 'redirect' => '../index.php']);
    } else {
        jsonResponse(['success' => false, 'message' => 'Ошибка при регистрации. Попробуйте позже.'], 500);
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Rewind Vault</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-tabs">
            <a href="login.php" class="auth-tab">Login</a>
            <a href="register.php" class="auth-tab active">Sign Up</a>
        </div>
        
        <div class="auth-form-container">
            <h2>Create your account</h2>
            
            <form id="registerForm" class="auth-form" method="POST" action="register.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Choose a username" 
                        required
                        pattern="[a-zA-Z0-9_-]{3,50}"
                        title="Только буквы, цифры, _ и -, от 3 до 50 символов"
                    >
                    <span class="error-message" id="usernameError"></span>
                </div>
                
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
                        placeholder="Create a password" 
                        required
                        minlength="6"
                        maxlength="255"
                    >
                    <span class="error-message" id="passwordError"></span>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        placeholder="Confirm your password" 
                        required
                    >
                    <span class="error-message" id="confirmPasswordError"></span>
                </div>
                
                <button type="submit" class="btn-submit">Sign Up</button>
            </form>
            
            <div class="auth-footer">
                <p>Already have an account? <a href="login.php">Log in</a></p>
            </div>
        </div>
    </div>
    
    <script src="../js/auth.js"></script>
</body>
</html>