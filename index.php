<?php require_once 'php/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/pages/home.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>

    <section class="hero-slider">
        <div class="slider-container">
            <div class="slide slide--active" style="background-image: url('assets/images/backgrounds/slide1.png');">
                <div class="slide__overlay"></div>
                <div class="container">
                    <div class="slide__content">
                        <h1 class="slide__title">
                            <span class="slide__title-top">Collection of</span>
                            <span class="slide__title-bottom">The Week</span>
                        </h1>
                        <p class="slide__description">Discover this week's handpicked collection of timeless classics and hidden gems.</p>
                        <a href="collections.php" class="btn btn--primary btn--large">Explore</a>
                    </div>
                </div>
            </div>
            
            <div class="slide" style="background-image: url('assets/images/backgrounds/slide2.png');">
                <div class="slide__overlay"></div>
                <div class="container">
                    <div class="slide__content">
                        <h1 class="slide__title">
                            <span class="slide__title-top">Coming</span>
                            <span class="slide__title-bottom">Soon</span>
                        </h1>
                        <p class="slide__description">The most anticipated premieres of the month. Add to your lists and be the first to know about the latest releases.</p>
                        <a href="movies.php" class="btn btn--primary btn--large">See What's Coming</a>
                    </div>
                </div>
            </div>
            
            <div class="slide" style="background-image: url('assets/images/backgrounds/slide3.png');">
                <div class="slide__overlay"></div>
                <div class="container">
                    <div class="slide__content">
                        <h1 class="slide__title">
                            <span class="slide__title-top">Community</span>
                            <span class="slide__title-bottom">Voices</span>
                        </h1>
                        <p class="slide__description">Honest reviews, live discussions, and recommendations from fellow movie lovers. Share your opinions and find like-minded people.</p>
                        <a href="forum.php" class="btn btn--primary btn--large">Join the Discussion</a>
                    </div>
                </div>
            </div>
            
            <div class="slider__controls">
                <button class="slider__dot slider__dot--active" data-slide="0"></button>
                <button class="slider__dot" data-slide="1"></button>
                <button class="slider__dot" data-slide="2"></button>
            </div>
        </div>
    </section>

    <section class="section new-releases">
        <div class="container">
            <div class="section__header">
                <h2 class="section__title">New Releases</h2>
                <a href="movies.php" class="section__link">See All</a>
            </div>
            
            <div class="movies-grid">
                <div class="movie-card">
                    <div class="movie-card__poster">
                        <img src="assets/images/placeholders/new1.png" alt="Backrooms">
                        <div class="movie-card__rating">7.2</div>
                    </div>
                    <div class="movie-card__info">
                        <h3 class="movie-card__title">Backrooms</h3>
                        <span class="movie-card__year">2025</span>
                    </div>
                </div>
                
                <div class="movie-card">
                    <div class="movie-card__poster">
                        <img src="assets/images/placeholders/new2.png" alt="Power Ballad">
                        <div class="movie-card__rating">6.8</div>
                    </div>
                    <div class="movie-card__info">
                        <h3 class="movie-card__title">Power Ballad</h3>
                        <span class="movie-card__year">2024</span>
                    </div>
                </div>
                
                <div class="movie-card">
                    <div class="movie-card__poster">
                        <img src="assets/images/placeholders/new3.png" alt="The Drama">
                        <div class="movie-card__rating">8.1</div>
                    </div>
                    <div class="movie-card__info">
                        <h3 class="movie-card__title">The Drama</h3>
                        <span class="movie-card__year">2024</span>
                    </div>
                </div>
                
                <div class="movie-card">
                    <div class="movie-card__poster">
                        <img src="assets/images/placeholders/new4.png" alt="Project Hail Mary">
                        <div class="movie-card__rating">9.1</div>
                    </div>
                    <div class="movie-card__info">
                        <h3 class="movie-card__title">Project Hail Mary</h3>
                        <span class="movie-card__year">2024</span>
                    </div>
                </div>
                
                <div class="movie-card">
                    <div class="movie-card__poster">
                        <img src="assets/images/placeholders/new5.png" alt="Marty Supreme">
                        <div class="movie-card__rating">8.7</div>
                    </div>
                    <div class="movie-card__info">
                        <h3 class="movie-card__title">Marty Supreme</h3>
                        <span class="movie-card__year">2024</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section most-popular">
        <div class="container">
            <div class="section__header">
                <h2 class="section__title">Most Popular</h2>
                <a href="movies.php" class="section__link">See All</a>
            </div>
            
            <div class="movies-grid">
                <div class="movie-card">
                    <div class="movie-card__poster">
                        <img src="assets/images/placeholders/popular1.png" alt="Possession">
                        <div class="movie-card__rating">7.2</div>
                    </div>
                    <div class="movie-card__info">
                        <h3 class="movie-card__title">Possession</h3>
                        <span class="movie-card__year">1981</span>
                    </div>
                </div>
                
                <div class="movie-card">
                    <div class="movie-card__poster">
                        <img src="assets/images/placeholders/popular2.png" alt="The Seventh Seal">
                        <div class="movie-card__rating">8.1</div>
                    </div>
                    <div class="movie-card__info">
                        <h3 class="movie-card__title">The Seventh Seal</h3>
                        <span class="movie-card__year">1957</span>
                    </div>
                </div>
                
                <div class="movie-card">
                    <div class="movie-card__poster">
                        <img src="assets/images/placeholders/popular3.png" alt="Donnie Darko">
                        <div class="movie-card__rating">8</div>
                    </div>
                    <div class="movie-card__info">
                        <h3 class="movie-card__title">Donnie Darko</h3>
                        <span class="movie-card__year">2001</span>
                    </div>
                </div>
                
                <div class="movie-card">
                    <div class="movie-card__poster">
                        <img src="assets/images/placeholders/popular4.png" alt="Whiplash">
                        <div class="movie-card__rating">8.5</div>
                    </div>
                    <div class="movie-card__info">
                        <h3 class="movie-card__title">Whiplash</h3>
                        <span class="movie-card__year">2014</span>
                    </div>
                </div>
                
                <div class="movie-card">
                    <div class="movie-card__poster">
                        <img src="assets/images/placeholders/popular5.png" alt="The Godfather">
                        <div class="movie-card__rating">9.2</div>
                    </div>
                    <div class="movie-card__info">
                        <h3 class="movie-card__title">The Godfather</h3>
                        <span class="movie-card__year">1972</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'php/includes/footer.php'; ?>

    <script src="js/main.js"></script>
</body>
</html>