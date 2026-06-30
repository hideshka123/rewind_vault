let allMovies = [];
let currentFilters = {
    genre: 'all',
    director: 'all',
    year: 'all',
    actor: 'all'
};
let currentSort = 'popular';
let currentPage = 1;
const moviesPerPage = 10;

let moviesGrid;
let filterBtns;
let filterDropdowns;
let sortBtn;
let sortDropdown;
let paginationDots;

document.addEventListener('DOMContentLoaded', async function() {
    moviesGrid = document.getElementById('movies-grid');
    filterBtns = document.querySelectorAll('.filter-btn');
    filterDropdowns = document.querySelectorAll('.filter-dropdown');
    sortBtn = document.getElementById('sort-btn');
    sortDropdown = document.getElementById('sort-dropdown');
    paginationDots = document.querySelectorAll('.pagination__dot');
    
    if (moviesGrid) {
        moviesGrid.innerHTML = '<p style="text-align: center; color: var(--color-text-muted);">Loading movies...</p>';
    }
    
    await loadMovies();
    setupEventListeners();
});

async function loadMovies() {
    try {
        console.log('Loading movies from OMDB API...');
        allMovies = await getAllMovies();
        console.log(`Loaded ${allMovies.length} movies`);
        
        allMovies = allMovies.map(movie => ({
            ...movie,
            poster: movie.localPoster || 'movie1.png',
            rating: movie.imdbRating ? parseFloat(movie.imdbRating) : 0
        }));
        
        renderMovies();
        await populateFilterDropdowns();
    } catch (error) {
        console.error('Error loading movies:', error);
        if (moviesGrid) {
            moviesGrid.innerHTML = '<p style="text-align: center; color: var(--color-text-muted);">Error loading movies. Please try again later.</p>';
        }
    }
}

async function populateFilterDropdowns() {
    const genreDropdown = document.getElementById('genre-dropdown');
    if (genreDropdown) {
        const genres = getUniqueGenres(allMovies);
        genreDropdown.innerHTML = '<div class="filter-option" data-value="all">All Genres</div>';
        genres.forEach(genre => {
            if (genre) {
                const option = document.createElement('div');
                option.className = 'filter-option';
                option.dataset.value = genre;
                option.textContent = genre;
                genreDropdown.appendChild(option);
            }
        });
    }
    
    const directorDropdown = document.getElementById('director-dropdown');
    if (directorDropdown) {
        const directors = getUniqueDirectors(allMovies);
        directorDropdown.innerHTML = '<div class="filter-option" data-value="all">All Directors</div>';
        directors.forEach(director => {
            if (director) {
                const option = document.createElement('div');
                option.className = 'filter-option';
                option.dataset.value = director;
                option.textContent = director;
                directorDropdown.appendChild(option);
            }
        });
    }
    
    const yearDropdown = document.getElementById('year-dropdown');
    if (yearDropdown) {
        const years = getUniqueYears(allMovies);
        yearDropdown.innerHTML = '<div class="filter-option" data-value="all">All Years</div>';
        years.forEach(year => {
            if (year) {
                const option = document.createElement('div');
                option.className = 'filter-option';
                option.dataset.value = year;
                option.textContent = year;
                yearDropdown.appendChild(option);
            }
        });
    }
    
    const actorDropdown = document.getElementById('actor-dropdown');
    if (actorDropdown) {
        const actors = getUniqueActors(allMovies);
        actorDropdown.innerHTML = '<div class="filter-option" data-value="all">All Actors</div>';
        actors.slice(0, 50).forEach(actor => {
            if (actor) {
                const option = document.createElement('div');
                option.className = 'filter-option';
                option.dataset.value = actor;
                option.textContent = actor;
                actorDropdown.appendChild(option);
            }
        });
    }
}

function renderMovies() {
    let filteredMovies = filterMovies(allMovies);
    let sortedMovies = sortMovies(filteredMovies);
    
    const startIndex = (currentPage - 1) * moviesPerPage;
    const endIndex = startIndex + moviesPerPage;
    const paginatedMovies = sortedMovies.slice(startIndex, endIndex);
    
    if (!moviesGrid) return;
    moviesGrid.innerHTML = '';
    
    paginatedMovies.forEach(movie => {
        const movieCard = createMovieCard(movie);
        moviesGrid.appendChild(movieCard);
    });
    
    updatePagination(sortedMovies.length);
}

function createMovieCard(movie) {
    const card = document.createElement('div');
    card.className = 'movie-card';
    card.innerHTML = `
        <div class="movie-card__poster">
            <img src="assets/images/placeholders/${movie.poster}" alt="${movie.title}">
            <div class="movie-card__rating">${movie.rating || 'N/A'}</div>
            <div class="movie-card__details">View Details</div>
        </div>
        <div class="movie-card__info">
            <h3 class="movie-card__title">${movie.title}</h3>
            <span class="movie-card__year">${movie.year || 'N/A'}</span>
        </div>
    `;
    
    card.addEventListener('click', () => {
        window.location.href = `movie-details.php?id=${movie.id}`;
    });
    
    return card;
}

function filterMovies(movies) {
    return movies.filter(movie => {
        const genreMatch = currentFilters.genre === 'all' || 
            (movie.genre && movie.genre.includes(currentFilters.genre));
        const directorMatch = currentFilters.director === 'all' || 
            (movie.director && movie.director.includes(currentFilters.director));
        const yearMatch = currentFilters.year === 'all' || 
            movie.year === currentFilters.year;
        const actorMatch = currentFilters.actor === 'all' || 
            (movie.actors && movie.actors.includes(currentFilters.actor));
        
        return genreMatch && directorMatch && yearMatch && actorMatch;
    });
}

function sortMovies(movies) {
    const sorted = [...movies];
    
    switch(currentSort) {
        case 'popular':
            return sorted.sort((a, b) => (b.rating || 0) - (a.rating || 0));
        case 'newest':
            return sorted.sort((a, b) => (parseInt(b.year) || 0) - (parseInt(a.year) || 0));
        case 'oldest':
            return sorted.sort((a, b) => (parseInt(a.year) || 0) - (parseInt(b.year) || 0));
        case 'rating':
            return sorted.sort((a, b) => (b.rating || 0) - (a.rating || 0));
        case 'title':
            return sorted.sort((a, b) => (a.title || '').localeCompare(b.title || ''));
        default:
            return sorted;
    }
}

function updatePagination(totalMovies) {
    const totalPages = Math.ceil(totalMovies / moviesPerPage);
    
    paginationDots.forEach((dot, index) => {
        if (index < totalPages) {
            dot.style.display = 'block';
            dot.classList.toggle('pagination__dot--active', index + 1 === currentPage);
        } else {
            dot.style.display = 'none';
        }
    });
}

function setupEventListeners() {
    filterBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const filterType = btn.dataset.filter;
            const dropdown = document.getElementById(`${filterType}-dropdown`);
            
            filterDropdowns.forEach(d => {
                if (d !== dropdown) d.classList.remove('show');
            });
            
            if (dropdown) {
                dropdown.classList.toggle('show');
            }
            btn.classList.toggle('active');
        });
    });
    
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('filter-option')) {
            const filterType = e.target.parentElement.id.replace('-dropdown', '');
            const value = e.target.dataset.value;
            
            currentFilters[filterType] = value;
            
            const dropdown = e.target.parentElement;
            dropdown.querySelectorAll('.filter-option').forEach(opt => opt.classList.remove('active'));
            e.target.classList.add('active');
            
            const btn = document.querySelector(`[data-filter="${filterType}"]`);
            const text = value === 'all' ? filterType.charAt(0).toUpperCase() + filterType.slice(1) : value;
            if (btn) {
                btn.innerHTML = `${text} <i class="fas fa-chevron-down"></i>`;
            }
            
            dropdown.classList.remove('show');
            if (btn) btn.classList.remove('active');
            
            currentPage = 1;
            renderMovies();
        }
    });
    
    if (sortBtn) {
        sortBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (sortDropdown) {
                sortDropdown.classList.toggle('show');
            }
        });
    }
    
    const sortOptions = document.querySelectorAll('.sort-option');
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
            
            renderMovies();
        });
    });
    
    paginationDots.forEach(dot => {
        dot.addEventListener('click', () => {
            currentPage = parseInt(dot.dataset.page);
            renderMovies();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
    
    document.addEventListener('click', () => {
        filterDropdowns.forEach(d => d.classList.remove('show'));
        filterBtns.forEach(b => b.classList.remove('active'));
        if (sortDropdown) {
            sortDropdown.classList.remove('show');
        }
    });
}

