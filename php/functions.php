<?php
function validateEmail($email) {
    if (empty($email)) {
        return ['valid' => false, 'message' => 'Email обязателен для заполнения'];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['valid' => false, 'message' => 'Некорректный формат email'];
    }
    
    return ['valid' => true, 'message' => ''];
}

function validatePassword($password, $confirmPassword = null) {
    if (empty($password)) {
        return ['valid' => false, 'message' => 'Пароль обязателен для заполнения'];
    }
    
    if (strlen($password) < 6) {
        return ['valid' => false, 'message' => 'Пароль должен содержать не менее 6 символов'];
    }
    
    if (strlen($password) > 255) {
        return ['valid' => false, 'message' => 'Пароль слишком длинный'];
    }
    
    if ($confirmPassword !== null && $password !== $confirmPassword) {
        return ['valid' => false, 'message' => 'Пароли не совпадают'];
    }
    
    return ['valid' => true, 'message' => ''];
}

function validateUsername($username) {
    if (empty($username)) {
        return ['valid' => false, 'message' => 'Имя пользователя обязательно'];
    }
    
    if (strlen($username) < 3) {
        return ['valid' => false, 'message' => 'Имя пользователя должно содержать не менее 3 символов'];
    }
    
    if (strlen($username) > 50) {
        return ['valid' => false, 'message' => 'Имя пользователя слишком длинное'];
    }
    
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        return ['valid' => false, 'message' => 'Имя пользователя может содержать только буквы, цифры, _ и -'];
    }
    
    return ['valid' => true, 'message' => ''];
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function sanitizeInput($data) {
    global $conn;
    return $conn->real_escape_string(trim(htmlspecialchars($data)));
}

function userExistsByEmail($email) {
    global $conn;
    $email = sanitizeInput($email);
    $sql = "SELECT id_user FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

function userExistsByUsername($username) {
    global $conn;
    $username = sanitizeInput($username);
    $sql = "SELECT id_user FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}

function registerUser($username, $email, $password) {
    global $conn;
    
    $hashedPassword = hashPassword($password);
    
    $sql = "INSERT INTO users (username, email, password, join_date) VALUES (?, ?, ?, CURDATE())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    
    try {
        $result = $stmt->execute();
        $userId = $conn->insert_id;
        $stmt->close();
        return ['success' => $result, 'user_id' => $userId];
    } catch (Exception $e) {
        $stmt->close();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function loginUser($email, $password) {
    global $conn;
    
    $email = sanitizeInput($email);
    $sql = "SELECT id_user, username, email, password, avatar_url FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $stmt->close();
        return ['success' => false, 'message' => 'Пользователь с таким email не найден'];
    }
    
    $user = $result->fetch_assoc();
    $stmt->close();
    
    if (!verifyPassword($password, $user['password'])) {
        return ['success' => false, 'message' => 'Неверный пароль'];
    }
    
    $_SESSION['user_id'] = $user['id_user'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['avatar_url'] = $user['avatar_url'];
    $_SESSION['logged_in'] = true;
    
    return ['success' => true, 'user' => $user];
}

function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'],
        'email' => $_SESSION['email'],
        'avatar_url' => $_SESSION['avatar_url']
    ];
}

function logoutUser() {
    session_unset();
    session_destroy();
    session_start(); 
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>