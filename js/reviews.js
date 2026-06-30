let reviewsData = [];
let moviesData = [];
let currentSort = 'date';
let selectedRating = 0;

let reviewsList;
let sortBtn;
let sortDropdown;
let sortOptions;
let writeReviewBtn;
let modal;
let modalClose;
let modalOverlay;
let cancelReviewBtn;
let reviewForm;
let movieSelect;
let starRating;
let stars;
let ratingInput;
let reviewText;
let charCount;

document.addEventListener('DOMContentLoaded', async function() {
    reviewsList = document.getElementById('reviews-list');
    sortBtn = document.getElementById('sort-btn');
    sortDropdown = document.getElementById('sort-dropdown');
    sortOptions = document.querySelectorAll('.sort-option');
    writeReviewBtn = document.getElementById('write-review-btn');
    modal = document.getElementById('write-review-modal');
    modalClose = document.getElementById('modal-close');
    modalOverlay = document.getElementById('modal-overlay');
    cancelReviewBtn = document.getElementById('cancel-review');
    reviewForm = document.getElementById('review-form');
    movieSelect = document.getElementById('movie-select');
    starRating = document.getElementById('star-rating');
    stars = starRating ? starRating.querySelectorAll('i') : [];
    ratingInput = document.getElementById('rating-input');
    reviewText = document.getElementById('review-text');
    charCount = document.getElementById('char-count');
    
    await loadMovies();
    await loadReviews();
    populateMovieSelect();
    setupEventListeners();
});

async function loadMovies() {
    try {
        if (typeof getAllMovies === 'function') {
            const apiMovies = await getAllMovies();
            
            moviesData = apiMovies.map(movie => {
                const mapping = typeof MOVIES_MAPPING !== 'undefined' ? 
                    MOVIES_MAPPING.find(m => m.id === movie.id) : null;
                return {
                    ...movie,
                    poster: mapping ? mapping.localPoster : 'movie1.png',
                    rating: movie.imdbRating ? parseFloat(movie.imdbRating) : 0
                };
            });
            
            console.log(`Loaded ${moviesData.length} movies for reviews`);
        }
    } catch (error) {
        console.error('Error loading movies:', error);
    }
}

async function loadReviews() {
    try {
        // ИСПРАВЛЕНО: используем profile_handler.php
        const response = await fetch('php/profile_handler.php?action=get_reviews');
        const result = await response.json();
        
        if (result.success) {
            reviewsData = result.reviews;
            renderReviews();
        } else {
            console.error('Error:', result.message);
            if (reviewsList) {
                reviewsList.innerHTML = '<p style="text-align: center; color: var(--color-text-muted); padding: 40px;">Error loading reviews</p>';
            }
        }
    } catch (error) {
        console.error('Error loading reviews:', error);
        if (reviewsList) {
            reviewsList.innerHTML = '<p style="text-align: center; color: #ff4444;">Error loading reviews</p>';
        }
    }
}

function populateMovieSelect() {
    if (!movieSelect) return;
    
    movieSelect.innerHTML = '<option value="">Search for a movie...</option>';
    
    moviesData.forEach(movie => {
        const option = document.createElement('option');
        option.value = movie.id;
        option.textContent = `${movie.title} (${movie.year})`;
        movieSelect.appendChild(option);
    });
}

function renderReviews() {
    if (!reviewsList) return;
    
    let sortedReviews = sortReviews(reviewsData);
    
    reviewsList.innerHTML = '';
    
    sortedReviews.forEach(review => {
        const reviewCard = createReviewCard(review);
        reviewsList.appendChild(reviewCard);
    });
}

function createReviewCard(review) {
    const card = document.createElement('div');
    card.className = 'review-card';
    card.style.cssText = 'background: rgba(255,255,255,0.05); border-radius: 8px; padding: 24px; margin-bottom: 20px;';
    
    const truncatedText = truncateText(review.review_text, 250);
    const starsHtml = generateStars(review.rating);
    
    card.innerHTML = `
        <div style="display: flex; gap: 20px;">
            <div style="flex-shrink: 0;">
                <img src="${review.avatar_url}" alt="${escapeHtml(review.username)}" 
                     style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;"
                     onerror="this.src='assets/images/avatars/default.png'">
            </div>
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                    <div>
                        <div style="font-weight: 600; color: var(--color-text); margin-bottom: 4px;">${escapeHtml(review.username)}</div>
                        <div style="color: var(--color-text-muted); font-size: 14px;">${formatDate(review.review_date)}</div>
                    </div>
                    <div style="display: flex; gap: 12px;">
                        <button class="like-btn" data-review-id="${review.id_review}" data-action="like" 
                                style="background: none; border: none; color: var(--color-text-muted); cursor: pointer; display: flex; align-items: center; gap: 4px;">
                            <i class="far fa-thumbs-up"></i>
                            <span>${review.likes}</span>
                        </button>
                        <button class="dislike-btn" data-review-id="${review.id_review}" data-action="dislike"
                                style="background: none; border: none; color: var(--color-text-muted); cursor: pointer; display: flex; align-items: center; gap: 4px;">
                            <i class="far fa-thumbs-down"></i>
                            <span>${review.dislikes}</span>
                        </button>
                    </div>
                </div>
                
                <div style="display: flex; gap: 16px; margin-bottom: 16px; align-items: center;">
                    <img src="${review.movie_poster}" alt="${escapeHtml(review.movie_title)}" 
                         style="width: 60px; height: 90px; object-fit: cover; border-radius: 4px;"
                         onerror="this.src='assets/images/placeholders/movie1.png'">
                    <div>
                        <div style="font-weight: 600; color: var(--color-text); margin-bottom: 4px;">${escapeHtml(review.movie_title)}</div>
                        <div style="color: var(--color-text-muted); font-size: 14px; margin-bottom: 8px;">${review.movie_year}</div>
                        <div>${starsHtml}</div>
                    </div>
                </div>
                
                <p style="color: var(--color-text); line-height: 1.6; margin-bottom: 12px;">
                    ${truncatedText.text}
                    ${!truncatedText.isFull ? `<span class="read-more" style="color: var(--color-primary); cursor: pointer; font-weight: 600;" data-full-text="${escapeHtml(review.review_text)}">Read more</span>` : ''}
                </p>
            </div>
        </div>
    `;
    
    const likeBtn = card.querySelector('.like-btn');
    const dislikeBtn = card.querySelector('.dislike-btn');
    
    if (likeBtn) {
        likeBtn.addEventListener('click', () => handleVote(review.id_review, 'like'));
    }
    
    if (dislikeBtn) {
        dislikeBtn.addEventListener('click', () => handleVote(review.id_review, 'dislike'));
    }
    
    const readMore = card.querySelector('.read-more');
    if (readMore) {
        readMore.addEventListener('click', handleReadMore);
    }
    
    return card;
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

function truncateText(text, maxLength) {
    if (!text) return { text: '', isFull: true };
    if (text.length <= maxLength) {
        return { text: text, isFull: true };
    }
    return { text: text.substring(0, maxLength) + '...', isFull: false };
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

function sortReviews(reviews) {
    const sorted = [...reviews];
    
    switch(currentSort) {
        case 'date':
            return sorted.sort((a, b) => {
                const dateA = new Date(a.review_date);
                const dateB = new Date(b.review_date);
                return dateB - dateA;
            });
        case 'rating':
            return sorted.sort((a, b) => (b.rating || 0) - (a.rating || 0));
        case 'popular':
            return sorted.sort((a, b) => (b.likes || 0) - (a.likes || 0));
        default:
            return sorted;
    }
}

async function handleVote(reviewId, action) {
    try {
        const formData = new FormData();
        formData.append('review_id', reviewId);
        formData.append('action', action);
        
        // ИСПРАВЛЕНО: используем profile_handler.php
        const response = await fetch('php/profile_handler.php?action=toggle_review_vote', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            await loadReviews();
        } else {
            alert(result.message || 'Error');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error updating vote');
    }
}

function handleReadMore(e) {
    const fullText = e.target.dataset.fullText;
    const textElement = e.target.parentElement;
    textElement.innerHTML = fullText;
}

function setupEventListeners() {
    if (sortBtn) {
        sortBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (sortDropdown) {
                sortDropdown.classList.toggle('show');
            }
        });
    }
    
    sortOptions.forEach(option => {
        option.addEventListener('click', (e) => {
            e.stopPropagation();
            currentSort = option.dataset.sort;
            
            sortOptions.forEach(opt => opt.classList.remove('active'));
            option.classList.add('active');
            
            if (sortBtn) {
                sortBtn.innerHTML = `${option.textContent} <i class="fas fa-chevron-down"></i>`;
            }
            if (sortDropdown) {
                sortDropdown.classList.remove('show');
            }
            
            renderReviews();
        });
    });
    
    if (writeReviewBtn) {
        writeReviewBtn.addEventListener('click', openModal);
    }
    if (modalClose) {
        modalClose.addEventListener('click', closeModal);
    }
    if (modalOverlay) {
        modalOverlay.addEventListener('click', closeModal);
    }
    if (cancelReviewBtn) {
        cancelReviewBtn.addEventListener('click', closeModal);
    }
    
    if (starRating && stars.length > 0) {
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const rating = parseInt(star.dataset.rating);
                selectedRating = rating;
                if (ratingInput) ratingInput.value = rating;
                updateStarRating(rating);
            });
            
            star.addEventListener('mouseenter', () => {
                const rating = parseInt(star.dataset.rating);
                updateStarRating(rating, true);
            });
        });
        
        starRating.addEventListener('mouseleave', () => {
            updateStarRating(selectedRating);
        });
    }
    
    if (reviewText && charCount) {
        reviewText.addEventListener('input', () => {
            charCount.textContent = reviewText.value.length;
        });
    }
    
    if (reviewForm) {
        reviewForm.addEventListener('submit', handleFormSubmit);
    }
    
    document.addEventListener('click', () => {
        if (sortDropdown) {
            sortDropdown.classList.remove('show');
        }
    });
}

function updateStarRating(rating, isHover = false) {
    if (!stars || stars.length === 0) return;
    
    stars.forEach(star => {
        const starRating = parseInt(star.dataset.rating);
        if (starRating <= rating) {
            star.classList.remove('far');
            star.classList.add('fas');
        } else {
            star.classList.remove('fas');
            star.classList.add('far');
        }
    });
}

function openModal() {
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal() {
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
    if (reviewForm) {
        reviewForm.reset();
    }
    selectedRating = 0;
    updateStarRating(0);
    if (charCount) {
        charCount.textContent = '0';
    }
}

async function handleFormSubmit(e) {
    e.preventDefault();
    
    const movieId = movieSelect ? parseInt(movieSelect.value) : 0;
    const rating = ratingInput ? parseInt(ratingInput.value) : 0;
    const text = reviewText ? reviewText.value.trim() : '';
    
    if (!movieId || !rating || !text) {
        alert('Please fill in all fields');
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('movie_id', movieId);
        formData.append('rating', rating);
        formData.append('review_text', text);
        
        // ИСПРАВЛЕНО: используем profile_handler.php
        const response = await fetch('php/profile_handler.php?action=add_review', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            await loadReviews();
            closeModal();
            showNotification('Review published successfully!', 'success');
        } else {
            alert(result.message || 'Error publishing review');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error publishing review');
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.textContent = message;
    notification.style.cssText = 'position: fixed; top: 100px; right: 20px; padding: 16px 24px; background: #1a1a1f; border-left: 4px solid ' + (type === 'success' ? '#00C851' : '#ff4444') + '; color: #EDE8E8; z-index: 10000; border-radius: 4px;';
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal && modal.classList.contains('show')) {
        closeModal();
    }
});