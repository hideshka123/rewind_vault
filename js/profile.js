let tabBtns;
let tabContents;
let watchlistContainer;
let favoritesContainer;
let watchedContainer;
let myReviewsContainer;
let avatarInput;
let avatarPreview;
let uploadProgress;

document.addEventListener('DOMContentLoaded', function() {
    tabBtns = document.querySelectorAll('.tab-btn');
    tabContents = document.querySelectorAll('.tab-content');
    watchlistContainer = document.getElementById('watchlist-movies');
    favoritesContainer = document.getElementById('favorites-movies');
    watchedContainer = document.getElementById('watched-movies');
    myReviewsContainer = document.getElementById('my-reviews-list');
    avatarInput = document.getElementById('avatar-input');
    avatarPreview = document.getElementById('avatar-preview');
    uploadProgress = document.getElementById('upload-progress');
    
    loadMovieLists();
    loadMyReviews();
    setupTabs();
    setupAvatarUpload();
});

function setupAvatarUpload() {
    if (!avatarInput) return;
    
    avatarInput.addEventListener('change', async function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) {
            alert('Недопустимый тип файла');
            return;
        }
        
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
            alert('Файл слишком большой');
            return;
        }
        
        if (uploadProgress) {
            uploadProgress.style.display = 'block';
        }
        
        const formData = new FormData();
        formData.append('avatar', file);
        
        try {
            const response = await fetch('upload_avatar.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                if (avatarPreview) {
                    avatarPreview.src = '../' + result.avatar_url + '?t=' + Date.now();
                }
                updateHeaderAvatar(result.avatar_url);
                showNotification('Аватар обновлён!', 'success');
            } else {
                alert(result.message || 'Ошибка');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Ошибка загрузки');
        } finally {
            if (uploadProgress) {
                uploadProgress.style.display = 'none';
            }
        }
    });
}

function updateHeaderAvatar(avatarUrl) {
    const headerAvatar = document.querySelector('.header__actions .profile-avatar-img');
    if (headerAvatar) {
        headerAvatar.src = '../' + avatarUrl + '?t=' + Date.now();
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.cssText = 'position: fixed; top: 100px; right: 20px; padding: 16px 24px; background: #1a1a1f; border-left: 4px solid ' + (type === 'success' ? '#00C851' : '#ff4444') + '; color: #EDE8E8; z-index: 10000; border-radius: 4px;';
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

async function loadMovieLists() {
    try {
        const response = await fetch('profile_handler.php?action=get_lists');
        const result = await response.json();
        
        if (result.success) {
            renderMovieList(watchlistContainer, result.lists.watchlist, 'watchlist');
            renderMovieList(favoritesContainer, result.lists.favorites, 'favorites');
            renderMovieList(watchedContainer, result.lists.watched, 'watched');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function loadMyReviews() {
    try {
        const response = await fetch('profile_handler.php?action=get_user_reviews');
        const result = await response.json();
        
        console.log('Reviews response:', result); 
        
        if (result.success) {
            renderMyReviews(result.reviews);
        } else {
            console.error('Error:', result.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function renderMovieList(container, movies, listType) {
    if (!container) return;
    
    container.innerHTML = '';
    
    if (movies.length === 0) {
        container.innerHTML = '<p class="empty-text">No movies in this list</p>';
        return;
    }
    
    movies.forEach(movie => {
        const poster = document.createElement('div');
        poster.className = 'list-movie-poster';
        poster.innerHTML = `
            <img src="../${movie.poster}" alt="${escapeHtml(movie.title)}">
            <button class="remove-btn" data-list-item-id="${movie.list_item_id}" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        poster.addEventListener('click', (e) => {
            if (!e.target.closest('.remove-btn')) {
                window.location.href = `../movie-details.php?id=${movie.id}`;
            }
        });
        
        const removeBtn = poster.querySelector('.remove-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                removeFromList(movie.list_item_id, listType);
            });
        }
        
        container.appendChild(poster);
    });
}

async function removeFromList(listItemId, listType) {
    if (!confirm('Remove this movie?')) return;
    
    try {
        const formData = new FormData();
        formData.append('list_item_id', listItemId);
        
        const response = await fetch('profile_handler.php?action=remove_from_list', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            await loadMovieLists();
            await updateStats();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function renderMyReviews(reviews) {
    if (!myReviewsContainer) return;
    
    console.log('Rendering reviews:', reviews); 
    
    myReviewsContainer.innerHTML = '';
    
    if (reviews.length === 0) {
        myReviewsContainer.innerHTML = '<p class="empty-text">No reviews yet</p>';
        return;
    }
    
    reviews.forEach(review => {
        const reviewCard = document.createElement('div');
        reviewCard.className = 'my-review-card';
        reviewCard.style.cssText = 'background: rgba(255,255,255,0.05); border-radius: 8px; padding: 20px; margin-bottom: 16px;';
        
        const stars = generateStars(review.rating);
        
        const reviewText = review.review_text || 'No text';
        const movieTitle = review.movie_title || 'Unknown Movie';
        const movieYear = review.movie_year || '';
        const moviePoster = review.movie_poster || 'assets/images/placeholders/movie1.png';
        const reviewDate = formatDate(review.review_date);
        const likes = review.likes || 0;
        const dislikes = review.dislikes || 0;
        
        reviewCard.innerHTML = `
            <div style="display: flex; gap: 16px; align-items: start;">
                <img src="../${moviePoster}" alt="${escapeHtml(movieTitle)}" 
                     style="width: 60px; height: 90px; object-fit: cover; border-radius: 4px;"
                     onerror="this.src='../assets/images/placeholders/movie1.png'">
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 8px;">
                        <div>
                            <h3 style="margin: 0 0 4px 0; color: var(--color-text);">${escapeHtml(movieTitle)}</h3>
                            <div style="color: var(--color-text-muted); font-size: 14px; margin-bottom: 8px;">
                                ${movieYear} • ${stars}
                            </div>
                        </div>
                        <button class="delete-review-btn" data-review-id="${review.id_review}" title="Delete"
                                style="background: none; border: none; color: var(--color-text-muted); cursor: pointer; font-size: 18px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <p style="color: var(--color-text); margin: 0 0 12px 0; line-height: 1.6;">${escapeHtml(reviewText)}</p>
                    <div style="display: flex; gap: 12px; color: var(--color-text-muted); font-size: 14px;">
                        <span>${reviewDate}</span>
                        <button class="like-btn" data-review-id="${review.id_review}" data-action="like"
                                style="background: none; border: none; color: var(--color-text-muted); cursor: pointer;">
                            <i class="far fa-thumbs-up"></i> ${likes}
                        </button>
                        <button class="dislike-btn" data-review-id="${review.id_review}" data-action="dislike"
                                style="background: none; border: none; color: var(--color-text-muted); cursor: pointer;">
                            <i class="far fa-thumbs-down"></i> ${dislikes}
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        const deleteBtn = reviewCard.querySelector('.delete-review-btn');
        const likeBtn = reviewCard.querySelector('.like-btn');
        const dislikeBtn = reviewCard.querySelector('.dislike-btn');
        
        if (deleteBtn) {
            deleteBtn.addEventListener('click', () => deleteReview(review.id_review));
        }
        
        if (likeBtn) {
            likeBtn.addEventListener('click', () => handleVote(review.id_review, 'like'));
        }
        
        if (dislikeBtn) {
            dislikeBtn.addEventListener('click', () => handleVote(review.id_review, 'dislike'));
        }
        
        myReviewsContainer.appendChild(reviewCard);
    });
}

async function handleVote(reviewId, action) {
    try {
        const formData = new FormData();
        formData.append('review_id', reviewId);
        formData.append('action', action);
        
        const response = await fetch('profile_handler.php?action=toggle_review_vote', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            await loadMyReviews();
        } else {
            alert(result.message || 'Error');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error updating vote');
    }
}

async function deleteReview(reviewId) {
    if (!confirm('Delete this review?')) return;
    
    try {
        const formData = new FormData();
        formData.append('review_id', reviewId);
        
        const response = await fetch('profile_handler.php?action=delete_review', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            await loadMyReviews();
            await updateStats();
        } else {
            alert(result.message || 'Error');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error deleting review');
    }
}

async function updateStats() {
    try {
        const response = await fetch('profile_handler.php?action=get_lists');
        const result = await response.json();
        
        if (result.success) {
            const totalMovies = result.lists.watchlist.length + result.lists.favorites.length + result.lists.watched.length;
            const favoritesCount = result.lists.favorites.length;
            
            const moviesCountEl = document.getElementById('movies-count');
            const favoritesCountEl = document.getElementById('favorites-count');
            
            if (moviesCountEl) moviesCountEl.textContent = totalMovies;
            if (favoritesCountEl) favoritesCountEl.textContent = favoritesCount;
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star" style="color: var(--color-primary);"></i>';
        } else {
            stars += '<i class="far fa-star" style="color: var(--color-text-muted);"></i>';
        }
    }
    return stars;
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 1) return 'Today';
    if (diffDays === 2) return 'Yesterday';
    if (diffDays <= 7) return `${diffDays} days ago`;
    if (diffDays <= 30) return `${Math.ceil(diffDays / 7)} weeks ago`;
    return date.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
}

function setupTabs() {
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.tab;
            
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            tabContents.forEach(content => content.classList.remove('active'));
            document.getElementById(`tab-${tab}`).classList.add('active');
        });
    });
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}