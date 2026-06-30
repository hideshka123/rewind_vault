<?php require_once 'php/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Детали фильма</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/pages/movie-details.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>

    <main class="movie-details-page">
        <div class="container">
            <a href="movies.php" class="back-link">
                <i class="fas fa-chevron-left"></i>
                Back to Movies
            </a>
            
            <div class="movie-details">
                <div class="movie-details__poster">
                    <img id="movie-poster" src="" alt="Movie Poster">
                </div>
                
                <div class="movie-details__info">
                    <h1 class="movie-details__title" id="movie-title">The Banshees of Inisherin</h1>
                    
                    <div class="movie-details__meta">
                        <span class="movie-details__rating">
                            <i class="fas fa-star" style="color: var(--color-primary);"></i>
                            <span id="movie-rating">4.3</span>
                        </span>
                        <span class="movie-details__separator">·</span>
                        <span class="movie-details__year" id="movie-year">2022</span>
                        <span class="movie-details__separator">·</span>
                        <span class="movie-details__duration" id="movie-duration">114 mins</span>
                        <span class="movie-details__separator">·</span>
                        <span class="movie-details__genres" id="movie-genres">Comedy, Drama</span>
                    </div>
                    
                    <p class="movie-details__description" id="movie-description"></p>
                    
                    <div class="actors-section">
                        <h2 class="actors-section__title">ACTORS</h2>
                        <div class="actors-list" id="actors-list">
                        </div>
                    </div>
                    
                    <?php if (isLoggedIn()): ?>
                    <div class="movie-actions">
                        <button class="btn-action btn-action--primary" id="btn-watchlist">
                            <i class="fas fa-bookmark"></i>
                            Add to Watchlist
                        </button>
                        <button class="btn-action" id="btn-favorites">
                            <i class="far fa-heart"></i>
                            Add to Favorites
                        </button>
                        <button class="btn-action" id="btn-watched">
                            <i class="far fa-eye"></i>
                            Add to Watched
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="movie-actions">
                        <a href="php/login.php" class="btn-action btn-action--primary">
                            <i class="fas fa-sign-in-alt"></i>
                            Login to add to lists
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'php/includes/footer.php'; ?>

    <script src="js/api.js"></script>
    <script src="js/movie-details.js"></script>
</body>
</html>