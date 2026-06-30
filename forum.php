<?php require_once 'php/includes/header.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форум</title>
    
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

    <main class="forum-page">
        <div class="container">
            <div class="forum-layout">
                <aside class="forum-sidebar">
                    <h3 class="sidebar-title">Categories</h3>
                    <nav class="sidebar-nav">
                        <a href="#" class="sidebar-link active" data-category="all">
                            <i class="far fa-comment-alt"></i>
                            Film Discussions
                        </a>
                        <a href="#" class="sidebar-link" data-category="user">
                            <i class="far fa-user"></i>
                            User Topics
                        </a>
                    </nav>
                </aside>

                <div class="forum-content">
                    <div class="forum-header">
                        <h1 class="forum-title">FILM DISCUSSIONS</h1>
                    </div>

                    <div class="topics-table">
                        <div class="topics-table__header">
                            <div class="topics-table__col topics-table__col--topic">Topic</div>
                            <div class="topics-table__col topics-table__col--category">Category</div>
                            <div class="topics-table__col topics-table__col--author">Author</div>
                            <div class="topics-table__col topics-table__col--replies">Replies</div>
                        </div>

                        <div class="topics-table__body" id="topics-list">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'php/includes/footer.php'; ?>

    <script src="js/forum.js"></script>
</body>
</html>