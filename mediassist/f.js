document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('featuresCarousel');
    const track = document.getElementById('carouselTrack');
    const dots = document.querySelectorAll('.carousel-dot');
    const items = document.querySelectorAll('.carousel-item');
    
    let isDragging = false;
    let startPos = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;
    let currentIndex = 0;
    let animationID;
    
    // Set initial position
    setPositionByIndex();
    
    // Touch events
    carousel.addEventListener('touchstart', touchStart);
    carousel.addEventListener('touchend', touchEnd);
    carousel.addEventListener('touchmove', touchMove);
    
    // Mouse events
    carousel.addEventListener('mousedown', touchStart);
    carousel.addEventListener('mouseup', touchEnd);
    carousel.addEventListener('mouseleave', touchEnd);
    carousel.addEventListener('mousemove', touchMove);
    
    // Dot navigation
    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        currentIndex = index;
        setPositionByIndex();
        updateDots();
      });
    });
    
    // Prevent image drag
    const images = document.querySelectorAll('.feature-image');
    images.forEach(img => {
      img.addEventListener('dragstart', (e) => e.preventDefault());
    });
    
    function touchStart(e) {
      if (e.type === 'touchstart') {
        startPos = e.touches[0].clientX;
      } else {
        startPos = e.clientX;
        e.preventDefault();
      }
      
      isDragging = true;
      animationID = requestAnimationFrame(animation);
      carousel.style.cursor = 'grabbing';
    }
    
    function touchEnd() {
      isDragging = false;
      cancelAnimationFrame(animationID);
      
      const movedBy = currentTranslate - prevTranslate;
      
      if (movedBy < -100 && currentIndex < items.length - 1) {
        currentIndex += 1;
      }
      
      if (movedBy > 100 && currentIndex > 0) {
        currentIndex -= 1;
      }
      
      setPositionByIndex();
      updateDots();
      carousel.style.cursor = 'grab';
    }
    
    function touchMove(e) {
      if (isDragging) {
        const currentPosition = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
        currentTranslate = prevTranslate + currentPosition - startPos;
      }
    }
    
    function animation() {
      setSliderPosition();
      if (isDragging) requestAnimationFrame(animation);
    }
    
    function setSliderPosition() {
      track.style.transform = `translateX(${currentTranslate}px)`;
    }
    
    function setPositionByIndex() {
      currentTranslate = currentIndex * -carousel.offsetWidth;
      prevTranslate = currentTranslate;
      setSliderPosition();
    }
    
    function updateDots() {
      dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentIndex);
      });
    }
    
    // Auto-rotate every 5 seconds
    setInterval(() => {
      if (!isDragging) {
        currentIndex = (currentIndex + 1) % items.length;
        setPositionByIndex();
        updateDots();
      }
    }, 5000);
  });