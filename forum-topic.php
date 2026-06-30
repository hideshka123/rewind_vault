<?php require_once 'php/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обсуждение</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/pages/forum.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>

    <main class="forum-topic-page">
        <div class="container">
            <a href="forum.php" class="back-link">
                <i class="fas fa-chevron-left"></i>
                Back to Forum
            </a>

            <div class="topic-header">
                <h1 class="topic-header__title" id="topic-title">Loading...</h1>
                <div class="topic-header__meta">
                    <span class="topic-header__category" id="topic-category">Loading...</span>
                    <span class="topic-header__author">
                        <img src="assets/images/avatars/default.png" alt="Author" class="topic-header__avatar" id="topic-avatar">
                        <span id="topic-author">Loading...</span>
                    </span>
                    <span class="topic-header__date" id="topic-date">Loading...</span>
                </div>
            </div>

                <p id="topic-message">Loading...</p>
            </div>

            <div class="replies-section">
                <h2 class="replies-section__title">Replies</h2>
                
                <div class="replies-list" id="replies-list">
                </div>
            </div>
        </div>
    </main>

    <?php include 'php/includes/footer.php'; ?>

    <script src="js/forum.js"></script>
</body>
</html>