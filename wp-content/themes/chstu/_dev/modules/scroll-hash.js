const defaultDuration = 500;

function anchorPos(id){
    let anchorPos = $(id).offset().top,
        offset;

    if (offset = parseInt($(id).data('scroll-offset'))) {
        //anchorPos -= menuHeight + offset;
        anchorPos -= offset;
    }
    // else {
    //     anchorPos -= menuHeight;
    // }
    //console.log(Math.round(anchorPos));
    return Math.round(anchorPos);
}

function getDuration(element){
    const duration = element.data('scroll-duration');
    if (duration >= 0){
        return duration;
    } else {
        return defaultDuration;
    }
}

function init(){
    $('a[href*="#"]:not([href="#"])').click(function(){
        const
            href = $(this).attr('href'),
            duration = getDuration($(this));

        if ($(href).length){
            $('html, body').stop().animate({
                scrollTop: anchorPos(href)
            }, duration);
            return false;
        }
    });
}

export default init;