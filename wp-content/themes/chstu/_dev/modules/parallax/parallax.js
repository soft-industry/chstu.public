import './parallax.scss';

const
    parallaxSpeed = .7,
    parallaxClass = 'parallax';

let scrollY,
    parallaxElements;

function setScrollY(){
    scrollY = window.scrollY;
}

function setAllElementsPos() {
    setScrollY();
    parallaxElements.forEach(el => setElementPos(el));
}

function setElementPos(el){

    const
        parentEl = el.parentNode,
        parentElRect = parentEl.getBoundingClientRect(),
        parentElTop = parentEl.offsetTop,
        parentElBottom = parentElTop + parentElRect.height,
        scrollYbottom = scrollY + window.outerHeight;

    let elPosY = 0;

    if ( scrollY <= parentElBottom && scrollYbottom > parentElTop ){
        elPosY = Math.floor((scrollY - parentElTop) * parallaxSpeed);
        el.style.transform = `translateY(${elPosY}px)`;
    }
}

function initParallaxElement(el) {
    const
        parentEl = el.parentNode,
        parentElRect = parentEl.getBoundingClientRect();
    if ( parentElRect.height > window.outerHeight ) el.style.height = parentElRect.height+'px';
}

function initAllParallaxElements() {
    parallaxElements.forEach(el => initParallaxElement(el));
}

function init() {
    parallaxElements = document.querySelectorAll(`.${parallaxClass}`);
    initAllParallaxElements();

    window.addEventListener('scroll', function() {
        requestAnimationFrame(function () {
            setAllElementsPos();
        });
    });

    window.addEventListener('resize', function() {
        requestAnimationFrame(function () {
            initAllParallaxElements();
            setAllElementsPos();
        });
    });
}

export default init;