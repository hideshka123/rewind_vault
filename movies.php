<?php require_once 'php/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Фильмы</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/pages/movies.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>

    <main class="movies-page">
        <div class="container">
            <div class="movies-header">
                <h1 class="movies-header__title">All Movies</h1>
                
                <div class="movies-filters">
                    <div class="filter-group">
                        <button class="filter-btn" data-filter="genre">
                            Genre <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-dropdown" id="genre-dropdown">
                            <div class="filter-option" data-value="all">All Genres</div>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <button class="filter-btn" data-filter="director">
                            Director <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-dropdown" id="director-dropdown">
                            <div class="filter-option" data-value="all">All Directors</div>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <button class="filter-btn" data-filter="year">
                            Year <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-dropdown" id="year-dropdown">
                            <div class="filter-option" data-value="all">All Years</div>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <button class="filter-btn" data-filter="actor">
                            Actor <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="filter-dropdown" id="actor-dropdown">
                            <div class="filter-option" data-value="all">All Actors</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="movies-controls">
                <div class="sort-group">
                    <span class="sort-label">Sort by</span>
                    <div class="sort-select">
                        <button class="sort-btn" id="sort-btn">
                            Most Popular <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="sort-dropdown" id="sort-dropdown">
                            <div class="sort-option active" data-sort="popular">Most Popular</div>
                            <div class="sort-option" data-sort="newest">Newest First</div>
                            <div class="sort-option" data-sort="oldest">Oldest First</div>
                            <div class="sort-option" data-sort="rating">Highest Rated</div>
                            <div class="sort-option" data-sort="title">Title A-Z</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="movies-grid" id="movies-grid">
            </div>
            
            <div class="pagination">
                <button class="pagination__dot pagination__dot--active" data-page="1"></button>
                <button class="pagination__dot" data-page="2"></button>
                <button class="pagination__dot" data-page="3"></button>
                <button class="pagination__dot" data-page="4"></button>
                <button class="pagination__dot" data-page="5"></button>
            </div>
        </div>
    </main>

    <?php include 'php/includes/footer.php'; ?>

    <script src="js/api.js"></script>
    <script src="js/movies.js"></script>
</body>
</html>