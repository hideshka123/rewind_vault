<?php
require_once 'config.php';
require_once 'functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'get_tables':
        getTablesAndViews($conn);
        break;
    case 'get_table_data':
        getTableData($conn);
        break;
    case 'get_table_structure':
        getTableStructure($conn);
        break;
    case 'add_record':
        addRecord($conn);
        break;
    case 'update_record':
        updateRecord($conn);
        break;
    case 'delete_record':
        deleteRecord($conn);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getTablesAndViews($conn) {
    $dbName = $conn->query("SELECT DATABASE() as db")->fetch_assoc()['db'];
    
    $tablesResult = $conn->query("
        SELECT TABLE_NAME 
        FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_SCHEMA = '$dbName' 
        AND TABLE_TYPE = 'BASE TABLE'
        ORDER BY TABLE_NAME
    ");
    
    $tables = [];
    while ($row = $tablesResult->fetch_assoc()) {
        $tables[] = $row['TABLE_NAME'];
    }
    
    $viewsResult = $conn->query("
        SELECT TABLE_NAME 
        FROM INFORMATION_SCHEMA.TABLES 
        WHERE TABLE_SCHEMA = '$dbName' 
        AND TABLE_TYPE = 'VIEW'
        ORDER BY TABLE_NAME
    ");
    
    $views = [];
    while ($row = $viewsResult->fetch_assoc()) {
        $views[] = $row['TABLE_NAME'];
    }
    
    echo json_encode(['success' => true, 'tables' => $tables, 'views' => $views]);
}

function getTableStructure($conn) {
    $tableName = isset($_GET['table']) ? $conn->real_escape_string($_GET['table']) : '';
    
    if (empty($tableName)) {
        echo json_encode(['success' => false, 'message' => 'Table name required']);
        return;
    }
    
    $columns = [];
    $result = $conn->query("SHOW COLUMNS FROM `$tableName`");
    
    while ($row = $result->fetch_assoc()) {
        $columns[] = [
            'field' => $row['Field'],
            'type' => $row['Type'],
            'null' => $row['Null'],
            'key' => $row['Key'],
            'default' => $row['Default'],
            'extra' => $row['Extra'],
            'is_primary' => $row['Key'] === 'PRI',
            'is_auto_increment' => strpos($row['Extra'], 'auto_increment') !== false
        ];
    }
    
    echo json_encode(['success' => true, 'columns' => $columns]);
}

function getTableData($conn) {
    $tableName = isset($_GET['table']) ? $conn->real_escape_string($_GET['table']) : '';
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $perPage = 20;
    $offset = ($page - 1) * $perPage;
    
    if (empty($tableName)) {
        echo json_encode(['success' => false, 'message' => 'Table name required']);
        return;
    }
    
    $countResult = $conn->query("SELECT COUNT(*) as total FROM `$tableName`");
    $total = $countResult->fetch_assoc()['total'];
    $totalPages = max(1, ceil($total / $perPage));
    
    $result = $conn->query("SELECT * FROM `$tableName` LIMIT $offset, $perPage");
    $rows = [];
    
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $rows,
        'total' => $total,
        'page' => $page,
        'total_pages' => $totalPages
    ]);
}

function addRecord($conn) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }
    
    $tableName = isset($_POST['table_name']) ? $conn->real_escape_string($_POST['table_name']) : '';
    
    if (empty($tableName)) {
        echo json_encode(['success' => false, 'message' => 'Table name required']);
        return;
    }
    
    $columns = [];
    $result = $conn->query("SHOW COLUMNS FROM `$tableName`");
    while ($row = $result->fetch_assoc()) {
        if (!$row['Extra'] || strpos($row['Extra'], 'auto_increment') === false) {
            $columns[] = $row['Field'];
        }
    }
    
    $fields = [];
    $values = [];
    $types = '';
    $params = [];
    
    foreach ($columns as $col) {
        if (isset($_POST[$col])) {
            $fields[] = "`$col`";
            $values[] = '?';
            
            $colInfo = $conn->query("SHOW COLUMNS FROM `$tableName` WHERE Field = '$col'")->fetch_assoc();
            $colType = $colInfo['Type'];
            
            if (strpos($colType, 'int') !== false || strpos($colType, 'decimal') !== false) {
                $types .= 'i';
                $params[] = is_numeric($_POST[$col]) ? $_POST[$col] : 0;
            } else {
                $types .= 's';
                $params[] = $_POST[$col];
            }
        }
    }
    
    if (empty($fields)) {
        echo json_encode(['success' => false, 'message' => 'No data provided']);
        return;
    }
    
    $sql = "INSERT INTO `$tableName` (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")";
    $stmt = $conn->prepare($sql);
    
    if (!empty($types)) {
        $stmt->bind_param($types, ...$params);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Record added']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
    
    $stmt->close();
}

function updateRecord($conn) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }
    
    $tableName = isset($_POST['table_name']) ? $conn->real_escape_string($_POST['table_name']) : '';
    $primaryKey = isset($_POST['primary_key']) ? $conn->real_escape_string($_POST['primary_key']) : '';
    $primaryKeyValue = isset($_POST['primary_key_value']) ? $_POST['primary_key_value'] : '';
    
    if (empty($tableName) || empty($primaryKey) || $primaryKeyValue === '') {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
        return;
    }
    
    $columns = [];
    $result = $conn->query("SHOW COLUMNS FROM `$tableName`");
    while ($row = $result->fetch_assoc()) {
        if ($row['Field'] !== $primaryKey) {
            $columns[] = $row['Field'];
        }
    }
    
    $sets = [];
    $types = '';
    $params = [];
    
    foreach ($columns as $col) {
        if (isset($_POST[$col])) {
            $sets[] = "`$col` = ?";
            
            $colInfo = $conn->query("SHOW COLUMNS FROM `$tableName` WHERE Field = '$col'")->fetch_assoc();
            $colType = $colInfo['Type'];
            
            if (strpos($colType, 'int') !== false || strpos($colType, 'decimal') !== false) {
                $types .= 'i';
                $params[] = is_numeric($_POST[$col]) ? $_POST[$col] : 0;
            } else {
                $types .= 's';
                $params[] = $_POST[$col];
            }
        }
    }
    
    if (empty($sets)) {
        echo json_encode(['success' => false, 'message' => 'No data to update']);
        return;
    }
    
    $colInfo = $conn->query("SHOW COLUMNS FROM `$tableName` WHERE Field = '$primaryKey'")->fetch_assoc();
    if (strpos($colInfo['Type'], 'int') !== false) {
        $types .= 'i';
        $params[] = intval($primaryKeyValue);
    } else {
        $types .= 's';
        $params[] = $primaryKeyValue;
    }
    
    $sql = "UPDATE `$tableName` SET " . implode(',', $sets) . " WHERE `$primaryKey` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Record updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }
    
    $stmt->close();
}

function deleteRecord($conn) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }
    
    $tableName = isset($_POST['table_name']) ? $conn->real_escape_string($_POST['table_name']) : '';
    $primaryKey = isset($_POST['primary_key']) ? $conn->real_escape_string($_POST['primary_key']) : '';
    $primaryKeyValue = isset($_POST['primary_key_value']) ? $_POST['primary_key_value'] : '';
    
    error_log("Delete: table=$tableName, key=$primaryKey, value=$primaryKeyValue");
    
    if (empty($tableName) || empty($primaryKey) || $primaryKeyValue === null || $primaryKeyValue === '') {
        echo json_encode(['success' => false, 'message' => 'Invalid data: ' . json_encode($_POST)]);
        return;
    }
    
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    
    switch ($tableName) {
        case 'forum_topics':
            $conn->query("DELETE FROM forum_posts WHERE id_topic = " . intval($primaryKeyValue));
            break;
        case 'movies':
            $conn->query("DELETE FROM movie_actors WHERE id_movie = " . intval($primaryKeyValue));
            $conn->query("DELETE FROM user_movie_lists WHERE id_movie = " . intval($primaryKeyValue));
            $conn->query("DELETE FROM reviews WHERE id_movie = " . intval($primaryKeyValue));
            $conn->query("DELETE FROM forum_topics WHERE id_movie = " . intval($primaryKeyValue));
            $conn->query("DELETE FROM collection_movies WHERE id_movie = " . intval($primaryKeyValue));
            break;
        case 'users':
            $conn->query("DELETE FROM reviews WHERE id_user = " . intval($primaryKeyValue));
            $conn->query("DELETE FROM user_movie_lists WHERE id_user = " . intval($primaryKeyValue));
            $conn->query("DELETE FROM forum_posts WHERE id_user = " . intval($primaryKeyValue));
            $conn->query("DELETE FROM forum_topics WHERE id_user = " . intval($primaryKeyValue));
            $conn->query("DELETE FROM collections WHERE id_user = " . intval($primaryKeyValue));
            break;
        case 'actors':
            $conn->query("DELETE FROM movie_actors WHERE id_actor = " . intval($primaryKeyValue));
            break;
        case 'forum_categories':
            $conn->query("DELETE FROM forum_posts WHERE id_topic IN (SELECT id_topic FROM forum_topics WHERE id_category = " . intval($primaryKeyValue) . ")");
            $conn->query("DELETE FROM forum_topics WHERE id_category = " . intval($primaryKeyValue));
            break;
        case 'collections':
            $conn->query("DELETE FROM collection_movies WHERE id_collection = " . intval($primaryKeyValue));
            break;
    }
    
    $colInfo = $conn->query("SHOW COLUMNS FROM `$tableName` WHERE Field = '$primaryKey'")->fetch_assoc();
    
    if (!$colInfo) {
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");
        echo json_encode(['success' => false, 'message' => 'Primary key not found']);
        return;
    }
    
    if (strpos($colInfo['Type'], 'int') !== false) {
        $sql = "DELETE FROM `$tableName` WHERE `$primaryKey` = " . intval($primaryKeyValue);
    } else {
        $sql = "DELETE FROM `$tableName` WHERE `$primaryKey` = '" . $conn->real_escape_string($primaryKeyValue) . "'";
    }
    
    if ($conn->query($sql)) {
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");
        echo json_encode(['success' => true, 'message' => 'Record deleted']);
    } else {
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
}
?>