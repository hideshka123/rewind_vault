<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    jsonResponse(['success' => false, 'message' => 'Требуется авторизация'], 401);
}

$user = getCurrentUser();
$userId = $user['id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Метод не разрешён'], 405);
}

if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE => 'Файл слишком большой (превышен размер в php.ini)',
        UPLOAD_ERR_FORM_SIZE => 'Файл слишком большой (превышен размер формы)',
        UPLOAD_ERR_PARTIAL => 'Файл загружен частично',
        UPLOAD_ERR_NO_FILE => 'Файл не был загружен',
        UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка',
        UPLOAD_ERR_CANT_WRITE => 'Ошибка записи на диск',
        UPLOAD_ERR_EXTENSION => 'Загрузка остановлена расширением'
    ];
    
    $errorCode = $_FILES['avatar']['error'] ?? UPLOAD_ERR_NO_FILE;
    $message = $errorMessages[$errorCode] ?? 'Ошибка загрузки файла';
    
    jsonResponse(['success' => false, 'message' => $message], 400);
}

$file = $_FILES['avatar'];

$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    jsonResponse(['success' => false, 'message' => 'Недопустимый тип файла. Разрешены: JPG, PNG, GIF, WEBP'], 400);
}

$maxSize = 5 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    jsonResponse(['success' => false, 'message' => 'Файл слишком большой. Максимум 5MB'], 400);
}

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($extension, $allowedExtensions)) {
    jsonResponse(['success' => false, 'message' => 'Недопустимое расширение файла'], 400);
}

$uploadDir = __DIR__ . '/../uploads/avatars/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$newFilename = 'user_' . $userId . '_' . time() . '.' . $extension;
$uploadPath = $uploadDir . $newFilename;

if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
    jsonResponse(['success' => false, 'message' => 'Ошибка при сохранении файла'], 500);
}

$oldAvatarPath = __DIR__ . '/../' . $user['avatar_url'];
if (!empty($user['avatar_url']) && file_exists($oldAvatarPath) && strpos($user['avatar_url'], 'uploads/avatars/') === 0) {
    unlink($oldAvatarPath);
}

$relativePath = 'uploads/avatars/' . $newFilename;
$updateSql = "UPDATE users SET avatar_url = ? WHERE id_user = ?";
$stmt = $conn->prepare($updateSql);
$stmt->bind_param("si", $relativePath, $userId);

if ($stmt->execute()) {
    $stmt->close();
    
    $_SESSION['avatar_url'] = $relativePath;
    
    jsonResponse([
        'success' => true, 
        'message' => 'Аватар успешно загружен',
        'avatar_url' => $relativePath
    ]);
} else {
    $stmt->close();
    unlink($uploadPath);
    jsonResponse(['success' => false, 'message' => 'Ошибка при обновлении базы данных'], 500);
}
?>