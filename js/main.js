document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider__dot');
    let currentSlide = 0;
    let slideInterval;
    
    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('slide--active'));
        dots.forEach(dot => dot.classList.remove('slider__dot--active'));
        
        slides[index].classList.add('slide--active');
        dots[index].classList.add('slider__dot--active');
        
        currentSlide = index;
    }
    
    function nextSlide() {
        let next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }
    
    function startSlideShow() {
        slideInterval = setInterval(nextSlide, 5000);
    }
    
    function stopSlideShow() {
        clearInterval(slideInterval);
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopSlideShow();
            showSlide(index);
            startSlideShow();
        });
    });
    
    startSlideShow();
    
    const burger = document.querySelector('.burger');
    if (burger) {
        burger.addEventListener('click', () => {
            console.log('Burger clicked');
        });
    }
});