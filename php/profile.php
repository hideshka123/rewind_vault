<?php
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = getCurrentUser();
$userId = $user['id'];

$statsQuery = "
    SELECT 
        (SELECT COUNT(*) FROM user_movie_lists WHERE id_user = ?) as movies_count,
        (SELECT COUNT(*) FROM reviews WHERE id_user = ?) as reviews_count,
        (SELECT COUNT(*) FROM user_movie_lists WHERE id_user = ? AND list_type = 'favorites') as favorites_count
";
$stmt = $conn->prepare($statsQuery);
$stmt->bind_param("iii", $userId, $userId, $userId);
$stmt->execute();
$stats = $stmt->get_result()->fetch_assoc();
$stmt->close();

$joinDate = date('F Y', strtotime($user['join_date'] ?? 'now'));
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../css/variables.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/components.css">
    <link rel="stylesheet" href="../css/pages/profile.css">
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header__content">
                <a href="../index.php" class="logo">
                    <img src="../assets/images/logo.svg" alt="Rewind Vault" class="logo__img">
                </a>
                
                <nav class="nav">
                    <a href="../index.php" class="nav__link">Home</a>
                    <a href="../movies.php" class="nav__link">Movies</a>
                    <a href="../reviews.php" class="nav__link">Reviews</a>
                    <a href="../collections.php" class="nav__link">Collections</a>
                    <a href="../forum.php" class="nav__link">Forum</a>
                </nav>
                
                <div class="header__actions">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                        <a href="profile.php" class="profile-link" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: var(--color-text);">
                            <?php if (!empty($_SESSION['avatar_url'])): ?>
                                <img src="../<?php echo htmlspecialchars($_SESSION['avatar_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($_SESSION['username']); ?>" 
                                     style="width: 40px !important; height: 40px !important; border-radius: 50% !important; object-fit: cover !important;">
                            <?php else: ?>
                                <div style="width: 40px !important; height: 40px !important; border-radius: 50% !important; background-color: #1a1a1f !important; display: flex !important; align-items: center !important; justify-content: center !important;">
                                    <i class="far fa-user" style="font-size: 18px;"></i>
                                </div>
                            <?php endif; ?>
                            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </a>
                        <a href="logout.php" class="btn btn--text">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn--text">Login</a>
                        <a href="register.php" class="btn btn--primary">Sign Up</a>
                    <?php endif; ?>
                </div>
                
                <button class="burger" aria-label="Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <main class="profile-page">
        <div class="container">
            <div class="profile-header">
                <div class="profile-header__avatar">
                    <?php if (!empty($user['avatar_url'])): ?>
                        <img src="../<?php echo htmlspecialchars($user['avatar_url']); ?>" 
                             alt="<?php echo htmlspecialchars($user['username']); ?>"
                             id="avatar-preview">
                    <?php else: ?>
                        <img src="../assets/images/avatars/default.png" 
                             alt="<?php echo htmlspecialchars($user['username']); ?>"
                             id="avatar-preview">
                    <?php endif; ?>
                    
                    <form id="avatar-upload-form" class="avatar-upload-form">
                        <label for="avatar-input" class="avatar-upload-btn">
                            <i class="fas fa-camera"></i>
                            Change
                        </label>
                        <input type="file" 
                               id="avatar-input" 
                               name="avatar" 
                               accept="image/jpeg,image/png,image/gif,image/webp"
                               style="display: none;">
                        <div class="upload-progress" id="upload-progress" style="display: none;">
                            <div class="progress-bar"></div>
                        </div>
                    </form>
                </div>
                
                <div class="profile-header__info">
                    <h1 class="profile-header__name"><?php echo htmlspecialchars($user['username']); ?></h1>
                    <div class="profile-header__stats">
                        <div class="stat-item">
                            <i class="far fa-calendar"></i>
                            <span>Joined <?php echo $joinDate; ?></span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-tv"></i>
                            <span><span id="movies-count"><?php echo $stats['movies_count']; ?></span> movies</span>
                        </div>
                        <div class="stat-item">
                            <i class="far fa-star"></i>
                            <span><span id="reviews-count"><?php echo $stats['reviews_count']; ?></span> reviews</span>
                        </div>
                        <div class="stat-item">
                            <i class="far fa-heart"></i>
                            <span><span id="favorites-count"><?php echo $stats['favorites_count']; ?></span> favorites</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-tabs">
                <button class="tab-btn active" data-tab="lists">
                    <i class="fas fa-list"></i>
                    My Lists
                </button>
                <button class="tab-btn" data-tab="reviews">
                    <i class="far fa-comment-alt"></i>
                    My Reviews
                </button>
            </div>

            <div class="tab-content active" id="tab-lists">
                <div class="lists-grid">
                    <div class="list-card">
                        <div class="list-card__icon list-card__icon--watchlist">
                            <i class="far fa-bookmark"></i>
                        </div>
                        <div class="list-card__movies" id="watchlist-movies">
                            <p class="loading-text">Loading...</p>
                        </div>
                    </div>

                    <div class="list-card">
                        <div class="list-card__icon list-card__icon--favorites">
                            <i class="far fa-heart"></i>
                        </div>
                        <div class="list-card__movies" id="favorites-movies">
                            <p class="loading-text">Loading...</p>
                        </div>
                    </div>

                    <div class="list-card">
                        <div class="list-card__icon list-card__icon--watched">
                            <i class="far fa-eye"></i>
                        </div>
                        <div class="list-card__movies" id="watched-movies">
                            <p class="loading-text">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="tab-reviews">
                <div class="reviews-list" id="my-reviews-list">
                    <p class="loading-text">Loading...</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <a href="../index.php" class="logo">
                    <img src="../assets/images/logo.svg" alt="Rewind Vault" class="logo__img">
                </a>
                
                <nav class="footer__nav">
                    <a href="../index.php" class="footer__link">Home</a>
                    <a href="../movies.php" class="footer__link">Movies</a>
                    <a href="../reviews.php" class="footer__link">Reviews</a>
                    <a href="../collections.php" class="footer__link">Collections</a>
                    <a href="../forum.php" class="footer__link">Forum</a>
                </nav>
                
                <div class="footer__social">
                    <a href="#" class="footer__social-link" aria-label="TikTok">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <a href="#" class="footer__social-link" aria-label="Telegram">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                    <a href="#" class="footer__social-link" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
            
            <div class="footer__copyright">
                <p>© 2026 Rewind Vault. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../js/profile.js"></script>
</body>
</html>