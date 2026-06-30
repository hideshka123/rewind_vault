<?php
require_once 'php/config.php';
require_once 'php/functions.php';

if (!isLoggedIn()) {
    header('Location: php/login.php');
    exit;
}

$current_page = 'admin';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админка</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/variables.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/pages/admin.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    <?php include 'php/includes/header.php'; ?>

    <main class="admin-page">
        <div class="container">
            <h1 class="admin-title">Admin Panel</h1>
            
            <div class="admin-section">
                <h2 class="admin-section-title">Tables (CRUD)</h2>
                <div class="admin-tables" id="tables-list">
                    <p>Loading...</p>
                </div>
            </div>
            
            <div class="admin-section">
                <h2 class="admin-section-title">Views (Read Only)</h2>
                <div class="admin-tables" id="views-list">
                    <p>Loading...</p>
                </div>
            </div>
            
            <div class="admin-section" id="data-section" style="display: none;">
                <div class="admin-header">
                    <h2 class="admin-section-title" id="current-table-name">Table</h2>
                    <div class="admin-actions">
                        <button class="btn btn--primary" id="add-btn" style="display: none;">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
                
                <div class="admin-table-wrapper">
                    <table class="admin-data-table" id="data-table">
                        <thead id="table-head"></thead>
                        <tbody id="table-body"></tbody>
                    </table>
                </div>
                
                <div class="admin-pagination" id="pagination"></div>
            </div>
            
            <div class="admin-section" id="form-section" style="display: none;">
                <h2 class="admin-section-title" id="form-title">Add Record</h2>
                <form id="record-form" class="admin-form">
                    <div id="form-fields"></div>
                    <div class="form-buttons">
                        <button type="button" class="btn btn--text" id="cancel-form-btn">Cancel</button>
                        <button type="submit" class="btn btn--primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <?php include 'php/includes/footer.php'; ?>

    <script src="js/admin.js"></script>
</body>
</html>