/**
 * Theme JavaScript
 * 
 * @package My_Portfolio_HTML
 */

// Initialize AOS
AOS.init({ 
    once: true, 
    duration: 800 
});

// Initialize GLightbox
const lightbox = GLightbox({
    touchNavigation: true,
    loop: true,
    autoplayVideos: true,
    zoomable: true
});

// GSAP Text Animation
gsap.registerPlugin(TextPlugin);
if (typeof myPortfolioData !== 'undefined') {
    gsap.to('.lead', {
        duration: 2,
        delay: 1.5,
        text: myPortfolioData.heroSubtitle,
        delimiter: ' ',
    });
}

// Handle hash links - prevent default and scroll without changing URL
document.querySelectorAll('a[href*="#"]:not([href="#"])').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        // Parse the link URL
        const url = new URL(this.href);
        
        // Only proceed if it's the same page (same origin and pathname)
        if (location.origin === url.origin && location.pathname.replace(/\/$/, '') === url.pathname.replace(/\/$/, '')) {
            // Get the hash
            const targetId = url.hash;
            
            // If there's a valid hash
            if (targetId) {
                const target = document.querySelector(targetId);
                
                // Only scroll if target exists on current page
                if (target) {
                    e.preventDefault();
                    
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Allow custom event for hash links
                    const hashEvent = new CustomEvent('hashLinkClicked', {
                        detail: { 
                            href: targetId,
                            target: target,
                            element: this
                        }
                    });
                    document.dispatchEvent(hashEvent);
                    
                    // Close mobile menu if open (reusing existing logic below by dispatching click?)
                    // The existing logic below handles nav-links generally, so no need to duplicate here
                }
            }
        }
    });
});

// Handle hash on page load
function handleHashOnLoad() {
    const hash = window.location.hash;
    if (hash) {
        const target = document.querySelector(hash);
        if (target) {
            // Scroll to target
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            
            // Clean URL without hash
            if (window.history && window.history.pushState) {
                window.history.pushState('', document.title, window.location.pathname + window.location.search);
            }
        }
    }
}

// Run on page load
window.addEventListener('load', handleHashOnLoad);
// Also run immediately if DOM is already loaded
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    handleHashOnLoad();
}

// Navbar scroll effect
const navbar = document.querySelector('.navbar');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
    
    lastScroll = currentScroll;
});

// Active nav link highlighting
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

function highlightNavLink() {
    const scrollPos = window.pageYOffset + 100;
    
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.offsetHeight;
        const sectionId = section.getAttribute('id');
        
        if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + sectionId) {
                    link.classList.add('active');
                }
            });
        }
    });
}

window.addEventListener('scroll', highlightNavLink);
window.addEventListener('load', highlightNavLink);

// Close mobile menu on link click
const navbarCollapse = document.querySelector('.navbar-collapse');
const navbarToggler = document.querySelector('.navbar-toggler');

if (navLinks && navbarCollapse && navbarToggler) {
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (navbarCollapse.classList.contains('show')) {
                navbarToggler.click();
            }
        });
    });
}

// Set current year
const currentYearEl = document.getElementById('currentYear');
if (currentYearEl) {
    currentYearEl.textContent = new Date().getFullYear();
}

// Scroll to Top Button
const scrollToTopBtn = document.getElementById('scrollToTop');

if (scrollToTopBtn) {
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.add('visible');
        } else {
            scrollToTopBtn.classList.remove('visible');
        }
    });
    
    scrollToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}
