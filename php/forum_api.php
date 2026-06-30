<?php
require_once 'config.php';

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'get_topics') {
    getTopics();
} elseif ($action === 'get_topic') {
    getTopic();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getAvatarUrl($avatarUrl) {
    if (empty($avatarUrl) || strpos($avatarUrl, 'example.com') !== false) {
        return 'assets/images/avatars/default.png';
    }
    return $avatarUrl;
}

function getPosterUrl($posterUrl) {
    if (empty($posterUrl) || strpos($posterUrl, 'example.com') !== false) {
        return 'assets/images/placeholders/movie1.png';
    }
    return $posterUrl;
}

function getTopics() {
    global $conn;
    
    $category = isset($_GET['category']) ? $_GET['category'] : 'Film Discussions';
    
    try {
        $sql = "
            SELECT 
                t.id_topic,
                t.title,
                t.id_movie,
                c.name_category as category,
                u.username as author,
                u.avatar_url as author_avatar,
                m.title as movie_title,
                m.poster_url as movie_poster,
                (SELECT COUNT(*) FROM forum_posts WHERE id_topic = t.id_topic) as replies,
                DATE_FORMAT(t.created_date, '%M %d, %Y') as date
            FROM forum_topics t
            JOIN forum_categories c ON t.id_category = c.id_category
            JOIN users u ON t.id_user = u.id_user
            LEFT JOIN movies m ON t.id_movie = m.id_movie
            WHERE c.name_category = ?
            ORDER BY t.created_date DESC
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $topics = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        foreach ($topics as &$topic) {
            $topic['author_avatar'] = getAvatarUrl($topic['author_avatar']);
            $topic['movie_poster'] = getPosterUrl($topic['movie_poster']);
        }
        
        echo json_encode(['success' => true, 'topics' => $topics]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function getTopic() {
    global $conn;
    
    $topicId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($topicId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        return;
    }
    
    try {
        $sql = "
            SELECT 
                t.id_topic,
                t.title,
                t.id_movie,
                c.name_category as category,
                u.username as author,
                u.avatar_url as author_avatar,
                m.title as movie_title,
                m.poster_url as movie_poster,
                DATE_FORMAT(t.created_date, '%M %d, %Y') as date
            FROM forum_topics t
            JOIN forum_categories c ON t.id_category = c.id_category
            JOIN users u ON t.id_user = u.id_user
            LEFT JOIN movies m ON t.id_movie = m.id_movie
            WHERE t.id_topic = ?
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $topicId);
        $stmt->execute();
        $topic = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if (!$topic) {
            echo json_encode(['success' => false, 'message' => 'Topic not found']);
            return;
        }
        
        $topic['author_avatar'] = getAvatarUrl($topic['author_avatar']);
        $topic['movie_poster'] = getPosterUrl($topic['movie_poster']);
        
        $postsSql = "
            SELECT 
                p.id_post,
                p.message,
                DATE_FORMAT(p.post_date, '%M %d, %Y') as date,
                u.username as author,
                u.avatar_url as author_avatar
            FROM forum_posts p
            JOIN users u ON p.id_user = u.id_user
            WHERE p.id_topic = ?
            ORDER BY p.post_date ASC
        ";
        
        $stmt = $conn->prepare($postsSql);
        $stmt->bind_param("i", $topicId);
        $stmt->execute();
        $posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        foreach ($posts as &$post) {
            $post['author_avatar'] = getAvatarUrl($post['author_avatar']);
        }
        
        echo json_encode([
            'success' => true,
            'topic' => $topic,
            'posts' => $posts
        ]);
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>