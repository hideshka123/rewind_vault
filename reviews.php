<?php require_once 'php/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Рецензии</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/pages/reviews.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>

    <main class="reviews-page">
        <div class="container">
            <div class="reviews-header">
                <div class="reviews-header__text">
                    <h1 class="reviews-header__title">REVIEWS</h1>
                    <p class="reviews-header__subtitle">Read and share thoughts about the movies you love.</p>
                </div>
                
                <div class="reviews-header__actions">
                    <?php if (isLoggedIn()): ?>
                        <button class="btn btn--primary btn--write" id="write-review-btn">
                            <i class="fas fa-pen"></i>
                            Write a Review
                        </button>
                    <?php else: ?>
                        <a href="php/login.php" class="btn btn--primary btn--write">
                            <i class="fas fa-pen"></i>
                            Login to Write
                        </a>
                    <?php endif; ?>
                    
                    <div class="sort-control">
                        <span class="sort-control__label">Sort by</span>
                        <div class="sort-select">
                            <button class="sort-btn" id="sort-btn">
                                Date <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="sort-dropdown" id="sort-dropdown">
                                <div class="sort-option active" data-sort="date">Date</div>
                                <div class="sort-option" data-sort="rating">Rating</div>
                                <div class="sort-option" data-sort="popular">Most Popular</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="reviews-list" id="reviews-list">
            </div>
        </div>
    </main>

    <?php if (isLoggedIn()): ?>
    <div class="modal" id="write-review-modal">
        <div class="modal__overlay" id="modal-overlay"></div>
        <div class="modal__content">
            <div class="modal__header">
                <h2 class="modal__title">Write a Review</h2>
                <button class="modal__close" id="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form class="review-form" id="review-form">
                <div class="form-group">
                    <label class="form-label">Movie</label>
                    <select class="form-select" id="movie-select" required>
                        <option value="">Search for a movie...</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Your Rating</label>
                    <div class="star-rating" id="star-rating">
                        <i class="far fa-star" data-rating="1"></i>
                        <i class="far fa-star" data-rating="2"></i>
                        <i class="far fa-star" data-rating="3"></i>
                        <i class="far fa-star" data-rating="4"></i>
                        <i class="far fa-star" data-rating="5"></i>
                    </div>
                    <input type="hidden" id="rating-input" name="rating" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Your Review</label>
                    <textarea 
                        class="form-textarea" 
                        id="review-text" 
                        placeholder="Share your thoughts about the movie..."
                        maxlength="5000"
                        rows="6"
                        required
                    ></textarea>
                    <div class="form-hint">
                        <span id="char-count">0</span> / 5,000
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn--text" id="cancel-review">Cancel</button>
                    <button type="submit" class="btn btn--primary btn--large">Publish Review</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <?php include 'php/includes/footer.php'; ?>

    <script src="js/api.js"></script>
    <script src="js/reviews.js"></script>
</body>
</html>