<?php
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
}
if (file_exists(__DIR__ . '/../functions.php')) {
    require_once __DIR__ . '/../functions.php';
}

$current_page = basename($_SERVER['PHP_SELF'], '.php');

$base_path = '';
?>
<header class="header">
    <div class="container">
        <div class="header__content">
            <a href="<?php echo $base_path; ?>index.php" class="logo">
                <img src="<?php echo $base_path; ?>assets/images/logo.svg" alt="Rewind Vault" class="logo__img">
            </a>
            
            <nav class="nav">
                <a href="<?php echo $base_path; ?>index.php" class="nav__link <?php echo $current_page === 'index' ? 'nav__link--active' : ''; ?>">Home</a>
                <a href="<?php echo $base_path; ?>movies.php" class="nav__link <?php echo $current_page === 'movies' ? 'nav__link--active' : ''; ?>">Movies</a>
                <a href="<?php echo $base_path; ?>reviews.php" class="nav__link <?php echo $current_page === 'reviews' ? 'nav__link--active' : ''; ?>">Reviews</a>
                <a href="<?php echo $base_path; ?>collections.php" class="nav__link <?php echo $current_page === 'collections' ? 'nav__link--active' : ''; ?>">Collections</a>
                <a href="<?php echo $base_path; ?>forum.php" class="nav__link <?php echo in_array($current_page, ['forum', 'forum-topic']) ? 'nav__link--active' : ''; ?>">Forum</a>
                <a href="<?php echo $base_path; ?>admin.php" class="nav__link <?php echo $current_page === 'admin' ? 'nav__link--active' : ''; ?>">Admin</a>
            </nav>
            
            <div class="header__actions">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <a href="<?php echo $base_path; ?>php/profile.php" class="profile-link" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: var(--color-text);">
                        <?php if (!empty($_SESSION['avatar_url'])): ?>
                            <img src="<?php echo $base_path; ?><?php echo htmlspecialchars($_SESSION['avatar_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($_SESSION['username']); ?>" 
                                 style="width: 40px !important; height: 40px !important; min-width: 40px !important; max-width: 40px !important; min-height: 40px !important; max-height: 40px !important; border-radius: 50% !important; object-fit: cover !important; border: 2px solid var(--color-primary) !important; display: inline-block !important; flex-shrink: 0 !important;">
                        <?php else: ?>
                            <div style="width: 40px !important; height: 40px !important; min-width: 40px !important; max-width: 40px !important; min-height: 40px !important; max-height: 40px !important; border-radius: 50% !important; background-color: #1a1a1f !important; border: 1px solid var(--color-border) !important; display: flex !important; align-items: center !important; justify-content: center !important; flex-shrink: 0 !important;">
                                <i class="far fa-user" style="font-size: 18px;"></i>
                            </div>
                        <?php endif; ?>
                        <span style="font-size: var(--font-size-base); font-weight: 600; white-space: nowrap;"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    </a>
                    <a href="<?php echo $base_path; ?>php/logout.php" class="btn btn--text">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $base_path; ?>php/login.php" class="btn btn--text">Login</a>
                    <a href="<?php echo $base_path; ?>php/register.php" class="btn btn--primary">Sign Up</a>
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