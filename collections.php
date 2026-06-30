<?php require_once 'php/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подборки</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/pages/collections.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    <main class="collections-page">
        <div class="container">
            <section class="collections-section">
                <h2 class="section-title">Top of the Week</h2>
                
                <div class="top-movies-grid">
                    <div class="top-movie-card">
                        <img src="assets/images/collections/top1.png" alt="Trainspotting" class="top-movie-card__img">
                        <div class="top-movie-card__overlay">
                            <span class="top-movie-card__number">01</span>
                            <span class="top-movie-card__title">Trainspotting</span>
                        </div>
                    </div>
                    
                    <div class="top-movie-card">
                        <img src="assets/images/collections/top2.png" alt="Inglourious Basterds" class="top-movie-card__img">
                        <div class="top-movie-card__overlay">
                            <span class="top-movie-card__number">02</span>
                            <span class="top-movie-card__title">Inglourious Basterds</span>
                        </div>
                    </div>
                    
                    <div class="top-movie-card">
                        <img src="assets/images/collections/top3.png" alt="Secretary" class="top-movie-card__img">
                        <div class="top-movie-card__overlay">
                            <span class="top-movie-card__number">03</span>
                            <span class="top-movie-card__title">Secretary</span>
                        </div>
                    </div>
                    
                    <div class="top-movie-card">
                        <img src="assets/images/collections/top4.png" alt="After Hours" class="top-movie-card__img">
                        <div class="top-movie-card__overlay">
                            <span class="top-movie-card__number">04</span>
                            <span class="top-movie-card__title">After Hours</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="collections-section">
                <h2 class="section-title">Thematic Lists</h2>
                
                <div class="thematic-grid">
                    <div class="thematic-card">
                        <img src="assets/images/collections/drama.png" alt="Drama" class="thematic-card__img">
                        <div class="thematic-card__overlay">
                            <h3 class="thematic-card__title">Drama</h3>
                            <span class="thematic-card__count">60 films</span>
                        </div>
                    </div>
                    
                    <div class="thematic-card">
                        <img src="assets/images/collections/thriller.png" alt="Thriller" class="thematic-card__img">
                        <div class="thematic-card__overlay">
                            <h3 class="thematic-card__title">Thriller</h3>
                            <span class="thematic-card__count">54 films</span>
                        </div>
                    </div>
                    
                    <div class="thematic-card">
                        <img src="assets/images/collections/classic.png" alt="Classic" class="thematic-card__img">
                        <div class="thematic-card__overlay">
                            <h3 class="thematic-card__title">Classic</h3>
                            <span class="thematic-card__count">66 films</span>
                        </div>
                    </div>
                    
                    <div class="thematic-card">
                        <img src="assets/images/collections/comedy.png" alt="Comedy" class="thematic-card__img">
                        <div class="thematic-card__overlay">
                            <h3 class="thematic-card__title">Comedy</h3>
                            <span class="thematic-card__count">35 films</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="collections-section">
                <h2 class="section-title">User Collections</h2>
                
                <div class="user-collections-grid">
                    <div class="user-collection-card">
                        <img src="assets/images/collections/user1.png" alt="Mind-Bending Movies" class="user-collection-card__img">
                        <div class="user-collection-card__overlay">
                            <h3 class="user-collection-card__title">Mind-Bending Movies</h3>
                            <span class="user-collection-card__count">42 films</span>
                        </div>
                    </div>
                    
                    <div class="user-collection-card">
                        <img src="assets/images/collections/user2.png" alt="80s Cult Classics" class="user-collection-card__img">
                        <div class="user-collection-card__overlay">
                            <h3 class="user-collection-card__title">80s Cult Classics</h3>
                            <span class="user-collection-card__count">36 films</span>
                        </div>
                    </div>
                    
                    <div class="user-collection-card">
                        <img src="assets/images/collections/user3.png" alt="Timeless Masterpieces" class="user-collection-card__img">
                        <div class="user-collection-card__overlay">
                            <h3 class="user-collection-card__title">Timeless Masterpieces</h3>
                            <span class="user-collection-card__count">64 films</span>
                        </div>
                    </div>
                    
                    <div class="user-collection-card">
                        <img src="assets/images/collections/user4.png" alt="Late Night Thrillers" class="user-collection-card__img">
                        <div class="user-collection-card__overlay">
                            <h3 class="user-collection-card__title">Late Night Thrillers</h3>
                            <span class="user-collection-card__count">51 films</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <?php include 'php/includes/footer.php'; ?>
    <script src="js/collections.js"></script>
</body>
</html>