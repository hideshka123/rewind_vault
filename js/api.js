const OMDB_API_CONFIG = {
    baseURL: 'https://www.omdbapi.com/',
    apiKey: '8b2d3ef5'
};

const MOVIES_MAPPING = [
    { id: 1, title: "The Banshees of Inisherin", year: "2022", localPoster: "movie3.png" },
    { id: 2, title: "Whiplash", year: "2014", localPoster: "movie9.png" },
    { id: 3, title: "The Godfather", year: "1972", localPoster: "movie20.png" },
    { id: 4, title: "Psycho", year: "1960", localPoster: "movie24.png" },
    { id: 5, title: "Donnie Darko", year: "2001", localPoster: "movie15.png" },
    { id: 6, title: "Apocalypse Now", year: "1979", localPoster: "movie19.png" },
    { id: 7, title: "Full Metal Jacket", year: "1987", localPoster: "movie16.png" },
    { id: 8, title: "Andrei Rublev", year: "1966", localPoster: "movie23.png" },
    { id: 9, title: "Birdman", year: "2014", localPoster: "movie7.png" },
    { id: 10, title: "Filth", year: "2013", localPoster: "movie10.png" },
    { id: 11, title: "Incendies", year: "2010", localPoster: "movie11.png" },
    { id: 12, title: "Control", year: "2007", localPoster: "movie12.png" },
    { id: 13, title: "Half Nelson", year: "2006", localPoster: "movie13.png" },
    { id: 14, title: "28 Days Later", year: "2002", localPoster: "movie14.png" },
    { id: 15, title: "The Lighthouse", year: "2019", localPoster: "movie5.png" },
    { id: 16, title: "Manchester by the Sea", year: "2016", localPoster: "movie6.png" },
    { id: 17, title: "Dinner in America", year: "2020", localPoster: "movie4.png" },
    { id: 18, title: "Possession", year: "1981", localPoster: "movie18.png" },
    { id: 19, title: "Videodrome", year: "1983", localPoster: "movie17.png" },
    { id: 20, title: "Aftersun", year: "2022", localPoster: "movie2.png" },
    { id: 21, title: "Sentimental Value", year: "2025", localPoster: "movie1.png" },
    { id: 22, title: "Johnny Got His Gun", year: "1971", localPoster: "movie21.png" },
    { id: 23, title: "2001: A Space Odyssey", year: "1968", localPoster: "movie22.png" },
    { id: 24, title: "The Seventh Seal", year: "1957", localPoster: "movie25.png" },
    { id: 25, title: "Creep", year: "2014", localPoster: "movie8.png" }
];

async function fetchMovieFromAPI(title, year) {
    try {
        const url = `${OMDB_API_CONFIG.baseURL}?t=${encodeURIComponent(title)}&y=${year}&apikey=${OMDB_API_CONFIG.apiKey}`;
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.Response === 'True') {
            return parseMovieData(data);
        } else {
            console.error(`OMDB API Error: ${data.Error} for ${title} (${year})`);
            return null;
        }
    } catch (error) {
        console.error(`Fetch error for ${title}:`, error);
        return null;
    }
}

function parseMovieData(apiData) {
    return {
        title: apiData.Title,
        year: apiData.Year,
        rated: apiData.Rated,
        released: apiData.Released,
        runtime: apiData.Runtime,
        genre: apiData.Genre,
        director: apiData.Director,
        writer: apiData.Writer,
        actors: apiData.Actors,
        plot: apiData.Plot,
        language: apiData.Language,
        country: apiData.Country,
        awards: apiData.Awards,
        ratings: apiData.ratings || [],
        imdbRating: apiData.imdbRating,
        imdbVotes: apiData.imdbVotes,
        type: apiData.Type
    };
}

async function getMovieById(localId) {
    const mapping = MOVIES_MAPPING.find(m => m.id === localId);
    if (!mapping) {
        console.error(`No mapping found for ID: ${localId}`);
        return null;
    }
    
    const apiData = await fetchMovieFromAPI(mapping.title, mapping.year);
    
    if (apiData) {
        return {
            ...apiData,
            id: localId,
            localPoster: mapping.localPoster,  
            Poster: `assets/images/placeholders/${mapping.localPoster}`  
        };
    }
    
    return {
        id: localId,
        title: mapping.title,
        year: mapping.year,
        localPoster: mapping.localPoster,
        Poster: `assets/images/placeholders/${mapping.localPoster}`,
        genre: '',
        director: '',
        actors: '',
        plot: '',
        imdbRating: '0'
    };
}

async function getAllMovies() {
    const movies = [];
    
    for (const mapping of MOVIES_MAPPING) {
        const movieData = await getMovieById(mapping.id);
        if (movieData) {
            movies.push(movieData);
        }
        await new Promise(resolve => setTimeout(resolve, 100));
    }
    
    return movies;
}

function getUniqueGenres(movies) {
    const genresSet = new Set();
    movies.forEach(movie => {
        if (movie.genre) {
            movie.genre.split(',').forEach(g => genresSet.add(g.trim()));
        }
    });
    return Array.from(genresSet).sort();
}

function getUniqueDirectors(movies) {
    const directorsSet = new Set();
    movies.forEach(movie => {
        if (movie.director) {
            movie.director.split(',').forEach(d => directorsSet.add(d.trim()));
        }
    });
    return Array.from(directorsSet).sort();
}

function getUniqueYears(movies) {
    return [...new Set(movies.map(m => m.year).filter(Boolean))].sort((a, b) => b - a);
}

function getUniqueActors(movies) {
    const actorsSet = new Set();
    movies.forEach(movie => {
        if (movie.actors) {
            movie.actors.split(',').forEach(a => actorsSet.add(a.trim()));
        }
    });
    return Array.from(actorsSet).sort();
}