document.addEventListener('DOMContentLoaded', function() {
    const allCards = document.querySelectorAll('.top-movie-card, .thematic-card, .user-collection-card');
    
    allCards.forEach(card => {
        card.addEventListener('click', () => {
            console.log('Collection card clicked');
        });
    });
});