function getMovieId() {
    const urlParams = new URLSearchParams(window.location.search);
    return parseInt(urlParams.get('id'));
}

function findMovieMapping(id) {
    return MOVIES_MAPPING.find(movie => movie.id === id);
}

let movieListsState = {
    watchlist: false,
    favorites: false,
    watched: false
};

async function renderMovieDetails() {
    const movieId = getMovieId();
    
    if (!movieId) {
        window.location.href = 'movies.php';
        return;
    }
    
    try {
        console.log(`Loading movie details for ID: ${movieId}`);
        const movie = await getMovieById(movieId);
        
        if (!movie) {
            console.error('Movie not found');
            window.location.href = 'movies.php';
            return;
        }
        
        console.log('Movie data:', movie);
        
        document.title = `Rewind Vault - ${movie.title}`;
        
        const mapping = findMovieMapping(movieId);
        if (mapping) {
            document.getElementById('movie-poster').src = `assets/images/placeholders/${mapping.localPoster}`;
        }
        document.getElementById('movie-poster').alt = movie.title;
        
        document.getElementById('movie-title').textContent = movie.title || 'N/A';
        
        const rating = movie.imdbRating && movie.imdbRating !== 'N/A' ? movie.imdbRating : 'N/A';
        document.getElementById('movie-rating').textContent = rating;
        
        document.getElementById('movie-year').textContent = movie.year || 'N/A';
        
        const duration = movie.runtime && movie.runtime !== 'N/A' ? movie.runtime : 'N/A';
        document.getElementById('movie-duration').textContent = duration;
        
        document.getElementById('movie-genres').textContent = movie.genre || 'N/A';
        document.getElementById('movie-description').textContent = movie.plot || 'No description available.';
        
        renderActors(movie);
        
        await loadMovieListsState(movieId);
        
        setupActionButtons(movie);
        
    } catch (error) {
        console.error('Error loading movie details:', error);
        const mapping = findMovieMapping(movieId);
        if (mapping) {
            document.getElementById('movie-poster').src = `assets/images/placeholders/${mapping.localPoster}`;
            document.getElementById('movie-title').textContent = mapping.title;
            document.getElementById('movie-year').textContent = mapping.year;
            document.getElementById('movie-description').textContent = 'Description is loading...';
        }
    }
}

function renderActors(movie) {
    const actorsList = document.getElementById('actors-list');
    actorsList.innerHTML = '';
    
    if (movie.director && movie.director !== 'N/A') {
        const directors = movie.director.split(',').map(d => d.trim());
        directors.forEach(director => {
            if (director) {
                const directorCard = document.createElement('div');
                directorCard.className = 'actor-card';
                directorCard.innerHTML = `
                    <span class="actor-card__name">${director}</span>
                    <span class="actor-card__role">Director</span>
                `;
                actorsList.appendChild(directorCard);
            }
        });
    }
    
    if (movie.actors && movie.actors !== 'N/A') {
        const actorsListArray = movie.actors.split(',').map(a => a.trim());
        actorsListArray.forEach(actor => {
            if (actor) {
                const actorCard = document.createElement('div');
                actorCard.className = 'actor-card';
                actorCard.innerHTML = `
                    <span class="actor-card__name">${actor}</span>
                    <span class="actor-card__role">Actor</span>
                `;
                actorsList.appendChild(actorCard);
            }
        });
    } else {
        actorsList.innerHTML = '<p style="color: var(--color-text-muted);">No actor information available.</p>';
    }
}

async function loadMovieListsState(movieId) {
    try {
        const response = await fetch('php/profile_handler.php?action=get_lists');
        const result = await response.json();
        
        if (result.success) {
            movieListsState.watchlist = result.lists.watchlist.some(m => m.id === movieId);
            movieListsState.favorites = result.lists.favorites.some(m => m.id === movieId);
            movieListsState.watched = result.lists.watched.some(m => m.id === movieId);
            
            console.log('Movie lists state:', movieListsState);
        }
    } catch (error) {
        console.error('Error loading lists state:', error);
    }
}

function setupActionButtons(movie) {
    const btnWatchlist = document.getElementById('btn-watchlist');
    const btnFavorites = document.getElementById('btn-favorites');
    const btnWatched = document.getElementById('btn-watched');
    
    if (!btnWatchlist || !btnFavorites || !btnWatched) return;
    
    if (movieListsState.watchlist) {
        btnWatchlist.classList.add('btn-action--primary');
        btnWatchlist.innerHTML = '<i class="fas fa-bookmark"></i> In Watchlist';
    }
    
    if (movieListsState.favorites) {
        btnFavorites.classList.add('btn-action--primary');
        btnFavorites.innerHTML = '<i class="fas fa-heart"></i> In Favorites';
    }
    
    if (movieListsState.watched) {
        btnWatched.classList.add('btn-action--primary');
        btnWatched.innerHTML = '<i class="fas fa-eye"></i> In Watched';
    }
    
    btnWatchlist.addEventListener('click', () => toggleListInDB('watchlist', movie.id, btnWatchlist));
    btnFavorites.addEventListener('click', () => toggleListInDB('favorites', movie.id, btnFavorites));
    btnWatched.addEventListener('click', () => toggleListInDB('watched', movie.id, btnWatched));
}

async function toggleListInDB(listType, movieId, button) {
    const isInList = movieListsState[listType];
    const action = isInList ? 'remove_from_list' : 'add_to_list';
    
    const formData = new FormData();
    formData.append('movie_id', movieId);
    formData.append('list_type', listType);
    
    if (isInList) {
        try {
            const response = await fetch('php/profile_handler.php?action=get_lists');
            const result = await response.json();
            
            if (result.success) {
                const listItem = result.lists[listType].find(m => m.id === movieId);
                if (listItem) {
                    formData.append('list_item_id', listItem.list_item_id);
                }
            }
        } catch (error) {
            console.error('Error getting list item id:', error);
            alert('Ошибка получения данных');
            return;
        }
    }
    
    try {
        const response = await fetch(`php/profile_handler.php?action=${action}`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            movieListsState[listType] = !isInList;
            
            if (movieListsState[listType]) {
                button.classList.add('btn-action--primary');
                if (listType === 'watchlist') {
                    button.innerHTML = '<i class="fas fa-bookmark"></i> In Watchlist';
                } else if (listType === 'favorites') {
                    button.innerHTML = '<i class="fas fa-heart"></i> In Favorites';
                } else if (listType === 'watched') {
                    button.innerHTML = '<i class="fas fa-eye"></i> In Watched';
                }
            } else {
                button.classList.remove('btn-action--primary');
                if (listType === 'watchlist') {
                    button.innerHTML = '<i class="fas fa-bookmark"></i> Add to Watchlist';
                } else if (listType === 'favorites') {
                    button.innerHTML = '<i class="far fa-heart"></i> Add to Favorites';
                } else if (listType === 'watched') {
                    button.innerHTML = '<i class="far fa-eye"></i> Add to Watched';
                }
            }
            
            showNotification(`Movie ${movieListsState[listType] ? 'added to' : 'removed from'} ${listType}`, 'success');
        } else {
            alert(result.message || 'Error');
        }
    } catch (error) {
        console.error('Error toggling list:', error);
        alert('Ошибка соединения');
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        padding: 16px 24px;
        background-color: #1a1a1f;
        border: 1px solid rgba(237, 232, 232, 0.1);
        border-left: 4px solid ${type === 'success' ? '#00C851' : '#ff4444'};
        border-radius: 4px;
        color: #EDE8E8;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}

document.addEventListener('DOMContentLoaded', renderMovieDetails);