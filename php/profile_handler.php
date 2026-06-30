<?php
require_once 'config.php';
require_once 'functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit;
}

$user = getCurrentUser();
$userId = $user['id'] ?? 0;

error_log("Current user ID: " . $userId);
error_log("Current user data: " . print_r($user, true));

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'get_lists':
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
            exit;
        }
        getMovieLists($conn, $userId);
        break;
    case 'get_reviews':
        getReviews($conn);
        break;
    case 'get_user_reviews':
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
            exit;
        }
        getUserReviews($conn, $userId);
        break;
    case 'add_review':
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
            exit;
        }
        addReview($conn, $userId);
        break;
    case 'toggle_review_vote':
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
            exit;
        }
        toggleReviewVote($conn, $userId);
        break;
    case 'add_to_list':
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
            exit;
        }
        addToMovieList($conn, $userId);
        break;
    case 'remove_from_list':
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
            exit;
        }
        removeFromMovieList($conn, $userId);
        break;
    case 'delete_review':
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
            exit;
        }
        deleteReview($conn, $userId);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getReviews($conn) {
    $sql = "
        SELECT 
            r.id_review,
            r.id_user,
            r.id_movie,
            r.rating,
            r.review_text,
            r.review_date,
            r.likes,
            r.dislikes,
            u.username,
            u.avatar_url,
            m.title as movie_title,
            m.year as movie_year,
            m.poster_url as movie_poster
        FROM reviews r
        JOIN users u ON r.id_user = u.id_user
        JOIN movies m ON r.id_movie = m.id_movie
        ORDER BY r.review_date DESC
    ";
    
    $result = $conn->query($sql);
    $reviews = [];
    
    while ($row = $result->fetch_assoc()) {
        $avatar_url = $row['avatar_url'];
        if (empty($avatar_url) || strpos($avatar_url, 'example.com') !== false) {
            $avatar_url = 'assets/images/avatars/default.png';
        }
        
        $movie_poster = $row['movie_poster'];
        if (empty($movie_poster) || strpos($movie_poster, 'example.com') !== false) {
            $movie_poster = 'assets/images/placeholders/movie' . $row['id_movie'] . '.png';
        }
        
        $reviews[] = [
            'id_review' => $row['id_review'],
            'id_user' => $row['id_user'],
            'id_movie' => $row['id_movie'],
            'rating' => $row['rating'],
            'review_text' => $row['review_text'],
            'review_date' => $row['review_date'],
            'likes' => $row['likes'],
            'dislikes' => $row['dislikes'],
            'username' => $row['username'],
            'avatar_url' => $avatar_url,
            'movie_title' => $row['movie_title'],
            'movie_year' => $row['movie_year'],
            'movie_poster' => $movie_poster
        ];
    }
    
    echo json_encode(['success' => true, 'reviews' => $reviews]);
}

function getUserReviews($conn, $userId) {
    $sql = "
        SELECT 
            r.id_review,
            r.id_user,
            r.id_movie,
            r.rating,
            r.review_text,
            r.review_date,
            r.likes,
            r.dislikes,
            m.title as movie_title,
            m.year as movie_year,
            m.poster_url as movie_poster
        FROM reviews r
        JOIN movies m ON r.id_movie = m.id_movie
        WHERE r.id_user = ?
        ORDER BY r.review_date DESC
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $reviews = [];
    while ($row = $result->fetch_assoc()) {
        $movie_poster = $row['movie_poster'];
        if (empty($movie_poster) || strpos($movie_poster, 'example.com') !== false) {
            $movie_poster = 'assets/images/placeholders/movie' . $row['id_movie'] . '.png';
        }
        
        $reviews[] = [
            'id_review' => $row['id_review'],
            'id_user' => $row['id_user'],
            'id_movie' => $row['id_movie'],
            'rating' => $row['rating'],
            'review_text' => $row['review_text'],
            'review_date' => $row['review_date'],
            'likes' => $row['likes'],
            'dislikes' => $row['dislikes'],
            'movie_title' => $row['movie_title'],
            'movie_year' => $row['movie_year'],
            'movie_poster' => $movie_poster
        ];
    }
    
    $stmt->close();
    echo json_encode(['success' => true, 'reviews' => $reviews]);
}

function addReview($conn, $userId) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }
    
    $movieId = isset($_POST['movie_id']) ? intval($_POST['movie_id']) : 0;
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
    $reviewText = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';
    
    if ($movieId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Выберите фильм']);
        return;
    }
    
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Рейтинг должен быть от 1 до 5']);
        return;
    }
    
    if (empty($reviewText)) {
        echo json_encode(['success' => false, 'message' => 'Введите текст рецензии']);
        return;
    }
    
    $checkMovieSql = "SELECT id_movie FROM movies WHERE id_movie = ?";
    $stmt = $conn->prepare($checkMovieSql);
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        $stmt->close();
        echo json_encode(['success' => false, 'message' => 'Фильм не найден']);
        return;
    }
    $stmt->close();
    
    $checkReviewSql = "SELECT id_review FROM reviews WHERE id_user = ? AND id_movie = ?";
    $stmt = $conn->prepare($checkReviewSql);
    $stmt->bind_param("ii", $userId, $movieId);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        echo json_encode(['success' => false, 'message' => 'Вы уже оставляли рецензию на этот фильм']);
        return;
    }
    $stmt->close();
    
    $insertSql = "INSERT INTO reviews (id_user, id_movie, rating, review_text, review_date, likes, dislikes) VALUES (?, ?, ?, ?, CURDATE(), 0, 0)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("iiss", $userId, $movieId, $rating, $reviewText);
    
    if ($stmt->execute()) {
        $reviewId = $conn->insert_id;
        $stmt->close();
        echo json_encode(['success' => true, 'message' => 'Рецензия добавлена', 'review_id' => $reviewId]);
    } else {
        $stmt->close();
        echo json_encode(['success' => false, 'message' => 'Ошибка при добавлении рецензии']);
    }
}

function toggleReviewVote($conn, $userId) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }
    
    $reviewId = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    if ($reviewId <= 0 || !in_array($action, ['like', 'dislike'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        return;
    }
    
    $checkSql = "SELECT id_review, likes, dislikes FROM reviews WHERE id_review = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("i", $reviewId);
    $stmt->execute();
    $review = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$review) {
        echo json_encode(['success' => false, 'message' => 'Review not found']);
        return;
    }
    
    if ($action === 'like') {
        $newLikes = $review['likes'] + 1;
        $updateSql = "UPDATE reviews SET likes = ? WHERE id_review = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ii", $newLikes, $reviewId);
        $stmt->execute();
        $stmt->close();
        
        echo json_encode(['success' => true, 'likes' => $newLikes, 'dislikes' => $review['dislikes']]);
    } else {
        $newDislikes = $review['dislikes'] + 1;
        $updateSql = "UPDATE reviews SET dislikes = ? WHERE id_review = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ii", $newDislikes, $reviewId);
        $stmt->execute();
        $stmt->close();
        
        echo json_encode(['success' => true, 'likes' => $review['likes'], 'dislikes' => $newDislikes]);
    }
}

function getMovieLists($conn, $userId) {
    $sql = "
        SELECT 
            uml.id_list_item,
            uml.id_movie,
            uml.list_type,
            m.title,
            m.year,
            m.rating,
            m.poster_url
        FROM user_movie_lists uml
        JOIN movies m ON uml.id_movie = m.id_movie
        WHERE uml.id_user = ?
        ORDER BY uml.id_list_item DESC
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $lists = ['watchlist' => [], 'favorites' => [], 'watched' => []];
    
    while ($row = $result->fetch_assoc()) {
        $movie = [
            'id' => $row['id_movie'],
            'title' => $row['title'],
            'year' => $row['year'],
            'rating' => $row['rating'],
            'poster' => $row['poster_url'] ?: 'movie1.png',
            'list_item_id' => $row['id_list_item']
        ];
        $lists[$row['list_type']][] = $movie;
    }
    
    $stmt->close();
    echo json_encode(['success' => true, 'lists' => $lists]);
}

function addToMovieList($conn, $userId) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }
    
    $movieId = isset($_POST['movie_id']) ? intval($_POST['movie_id']) : 0;
    $listType = isset($_POST['list_type']) ? trim($_POST['list_type']) : '';
    
    if ($movieId <= 0 || !in_array($listType, ['watchlist', 'favorites', 'watched'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        return;
    }
    
    $checkSql = "SELECT id_movie FROM movies WHERE id_movie = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $movieId);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows === 0) {
        $checkStmt->close();
        echo json_encode(['success' => false, 'message' => 'Movie not found']);
        return;
    }
    $checkStmt->close();
    
    $existsSql = "SELECT id_list_item FROM user_movie_lists WHERE id_user = ? AND id_movie = ? AND list_type = ?";
    $existsStmt = $conn->prepare($existsSql);
    $existsStmt->bind_param("iis", $userId, $movieId, $listType);
    $existsStmt->execute();
    if ($existsStmt->get_result()->num_rows > 0) {
        $existsStmt->close();
        echo json_encode(['success' => false, 'message' => 'Already in list']);
        return;
    }
    $existsStmt->close();
    
    $insertSql = "INSERT INTO user_movie_lists (id_user, id_movie, list_type) VALUES (?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("iis", $userId, $movieId, $listType);
    
    if ($insertStmt->execute()) {
        $listItemId = $conn->insert_id;
        $insertStmt->close();
        echo json_encode(['success' => true, 'message' => 'Added to list', 'list_item_id' => $listItemId]);
    } else {
        $insertStmt->close();
        echo json_encode(['success' => false, 'message' => 'Error adding to list']);
    }
}

function removeFromMovieList($conn, $userId) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }
    
    $listItemId = isset($_POST['list_item_id']) ? intval($_POST['list_item_id']) : 0;
    
    if ($listItemId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        return;
    }
    
    $checkSql = "SELECT id_list_item FROM user_movie_lists WHERE id_list_item = ? AND id_user = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $listItemId, $userId);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows === 0) {
        $checkStmt->close();
        echo json_encode(['success' => false, 'message' => 'Not found']);
        return;
    }
    $checkStmt->close();
    
    $deleteSql = "DELETE FROM user_movie_lists WHERE id_list_item = ? AND id_user = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("ii", $listItemId, $userId);
    
    if ($deleteStmt->execute()) {
        $deleteStmt->close();
        echo json_encode(['success' => true, 'message' => 'Removed from list']);
    } else {
        $deleteStmt->close();
        echo json_encode(['success' => false, 'message' => 'Error removing from list']);
    }
}

function deleteReview($conn, $userId) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        return;
    }
    
    $reviewId = isset($_POST['review_id']) ? intval($_POST['review_id']) : 0;
    
    if ($reviewId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        return;
    }
    
    $checkSql = "SELECT id_review FROM reviews WHERE id_review = ? AND id_user = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $reviewId, $userId);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows === 0) {
        $checkStmt->close();
        echo json_encode(['success' => false, 'message' => 'Review not found or access denied']);
        return;
    }
    $checkStmt->close();
    
    $deleteSql = "DELETE FROM reviews WHERE id_review = ? AND id_user = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("ii", $reviewId, $userId);
    
    if ($deleteStmt->execute()) {
        $deleteStmt->close();
        echo json_encode(['success' => true, 'message' => 'Review deleted']);
    } else {
        $deleteStmt->close();
        echo json_encode(['success' => false, 'message' => 'Error deleting review']);
    }
}
?>