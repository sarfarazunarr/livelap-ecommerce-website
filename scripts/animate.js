// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function () {
    // Hero section animation
    gsap.from('#hero .max-w-screen-xl', {
        opacity: 0,
        y: 50,
        duration: 1,
        ease: 'power3.out'
    })

    // Text animation
    gsap.from('#hero h1, p', {
        opacity: 0,
        y: 30,
        duration: 1,
        stagger: 0.2,
        delay: 0.5,
        ease: 'power2.out'
    })

    // Button animation
    gsap.from('#hero .btns', {
        opacity: 0,
        scale: 0.8,
        duration: 1,
        stagger: 0.3,
        delay: 1.2,
        ease: 'elastic.out(1, 0.5)'
    })

    // Image animation (for larger screens)
    gsap.from('#hero .flex-1.hidden.md\\:block img', {
        opacity: 0,
        scale: 0.8,
        duration: 1.2,
        delay: 0.8,
        ease: 'elastic.out(1, 0.8)'
    })

    // Brand section animation
    gsap.from('#brands .py-16.bg-gray-100', {
        opacity: 0,
        y: 30,
        duration: 1,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: '.py-16.bg-gray-100',
            start: 'top 80%',
        }
    })

    // Brand logos animation
    gsap.from('#brands #brandCarousel > div', {
        opacity: 0,
        scale: 0.8,
        duration: 0.8,
        stagger: 0.2,
        ease: 'back.out(1.7)',
        scrollTrigger: {
            trigger: '#brandCarousel',
            start: 'top 80%',
        }
    })

    // Brand section title animation
    gsap.from('#brands .py-16.bg-gray-100 h2', {
        opacity: 0,
        y: 20,
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.py-16.bg-gray-100 h2',
            start: 'top 80%',
        }
    })

    // Navigation buttons animation
    gsap.from('#brands .py-16.bg-gray-100 button', {
        opacity: 0,
        scale: 0.5,
        duration: 0.8,
        stagger: 0.2,
        ease: 'back.out(1.7)',
        scrollTrigger: {
            trigger: '.py-16.bg-gray-100',
            start: 'top 80%',
        }
    })
    // Top Products section animation
    gsap.from('#top-products', {
        opacity: 0,
        y: 30,
        duration: 1,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: '#top-products',
            start: 'top 80%',
        }
    })

    // Top Products title animation
    gsap.from('#top-products h2', {
        opacity: 0,
        y: 20,
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '#top-products h2',
            start: 'top 80%',
        }
    })

    // Top Products carousel items animation
    gsap.from('#laptopCarousel > div', {
        opacity: 0,
        scale: 0.8,
        duration: 0.8,
        stagger: 0.2,
        ease: 'back.out(1.7)',
        scrollTrigger: {
            trigger: '#laptopCarousel',
            start: 'top 80%',
        }
    })

    // Be Vendor section animation
    gsap.from('#bevendor', {
        opacity: 0,
        y: 50,
        duration: 1,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: '#bevendor',
            start: 'top 80%',
        }
    })

    // Be Vendor title animation
    gsap.from('#bevendor h1', {
        opacity: 0,
        y: 30,
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '#bevendor h1',
            start: 'top 80%',
        }
    })

    // Be Vendor paragraph animation
    gsap.from('#bevendor p', {
        opacity: 0,
        y: 20,
        duration: 1,
        delay: 0.2,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '#bevendor p',
            start: 'top 80%',
        }
    })
})
