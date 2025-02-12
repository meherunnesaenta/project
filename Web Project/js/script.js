// JavaScript for Slider Functionality
document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.slide');
    const prev = document.getElementById('prev');
    const next = document.getElementById('next');
    let currentSlide = 0;
    const slideIntervalTime = 5000; // Time in milliseconds (5 seconds)

    // Function to reset slides (remove "active" class from all slides)
    function resetSlides() {
        slides.forEach(slide => slide.classList.remove('active'));
    }

    // Function to show the current slide
    function showSlide(index) {
        resetSlides();
        slides[index].classList.add('active');
    }

    // Event listener for "Next" button
    next.addEventListener('click', () => {
        currentSlide = (currentSlide + 1) % slides.length; // Loop to the first slide
        showSlide(currentSlide);
    });

    // Event listener for "Previous" button
    prev.addEventListener('click', () => {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length; // Loop to the last slide
        showSlide(currentSlide);
    });

    // Automatically move to the next slide
    setInterval(() => {
        currentSlide = (currentSlide + 1) % slides.length; // Loop to the first slide
        showSlide(currentSlide);
    }, slideIntervalTime);

    // Initialize by showing the first slide
    showSlide(currentSlide);
});
