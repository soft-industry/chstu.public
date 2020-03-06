const
    inverseElementClass = 'use-dark-zone',
    inverseElementActiveClass = `${inverseElementClass}_active`;

function getScrollPos(){
    return $(window).scrollTop();
}

function darkZonesComparator(scrollPos, darkZones){
    return darkZones.some(darkZone => (
        (scrollPos > darkZone.top) && (scrollPos <= darkZone.bottom)
    ));
}

function getElementPos(element){
    const elementHalfHeight = parseInt(element.height()/2);
    return parseInt(element.position().top) + elementHalfHeight;
}

function transformElements(elements, scrollPos, darkZones){
    elements.each(function () {
        let element = $(this);
        const
            elementPos = getElementPos(element),
            elementScrollPos = scrollPos + elementPos;

        if ( darkZonesComparator(elementScrollPos, darkZones) ){
            if ( !element.hasClass(inverseElementActiveClass) ){
                element.addClass(inverseElementActiveClass);
            }
        } else {
            if ( element.hasClass(inverseElementActiveClass) ) {
                element.removeClass(inverseElementActiveClass);
            }
        }
    });
}

function gatherDarkZones(darkBlocks){
    let darkZones = [];
    darkBlocks.each(function () {
        const
            darkBlock = $(this),
            darkBlockTopOffset = darkBlock.offset().top,
            darkBlockBottomOffset = darkBlockTopOffset + darkBlock.outerHeight();
        darkZones.push({
            top: parseInt(darkBlockTopOffset),
            bottom: parseInt(darkBlockBottomOffset)
        });
    });

    return darkZones;
}

function init() {

    let
        darkBlocks = $('.dark-zone'),
        elements = $(`.${inverseElementClass}`),
        darkZones = gatherDarkZones(darkBlocks);

    transformElements(elements, getScrollPos(), darkZones);

    $(window).resize(function () {
        darkZones = gatherDarkZones(darkBlocks);
        transformElements(elements, getScrollPos(), darkZones);
    });

    $(window).scroll(function(){
        const scrollPos = getScrollPos();
        transformElements(elements, scrollPos, darkZones);
    });

    let
        darkZonesMutationCallback = function(){
            darkZones = gatherDarkZones(darkBlocks);
            transformElements(elements, getScrollPos(), darkZones);
        },
        darkZonesObserver = new MutationObserver(darkZonesMutationCallback),
        options = {
            'childList': true,
            'subtree': true
        };

    darkZonesObserver.observe(document.body, options);

}

export default init;