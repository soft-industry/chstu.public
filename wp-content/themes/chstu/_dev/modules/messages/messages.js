import './messages.scss';
import infoSvg from './images/info.svg?inline-js';
import errorSvg from './images/error.svg?inline-js';
import successSvg from './images/success.svg?inline-js';

function showPopUpMessage(state, text, hideDelay){

    hideDelay = hideDelay ? hideDelay : 4000;

    let menuTopPos = 0,
        msgClass = 'message',
        msgInnerClass = 'message__inner',
        msgVisibleClass = msgClass+'_active',
        msgErrorClass = msgClass+'_error',
        msgInfoClass = msgClass+'_info',
        msgSuccessClass = msgClass+'_success',
        uniqueId = 'message-id-'+parseInt(Math.random()*(4096-1024)+1024);

    let msg_html = '<div id="'+uniqueId+'" class="'+msgClass+'" style="top: '+menuTopPos+'px;">' +
        '<div class="'+msgInnerClass+'"></div>' +
        '<button class="'+msgClass+'__close"></button>' +
        '</div>',
        msg_ico = function(){

            let svg = false;

            if (state === 'error'){
                svg = errorSvg;
            } else if (state === 'info'){
                svg = infoSvg;
            } else if (state === 'success') {
                svg = successSvg;
            }

            if (svg){
                return '<div class="'+msgClass+'__icon">'+svg+'</div>';
            } else {
                return '';
            }

        };

    $('body').append(msg_html).find('#'+uniqueId).delay(50).queue(function(){

        if (state === 'error'){
            $(this).addClass(msgErrorClass);
        } else if (state === 'info'){
            $(this).addClass(msgInfoClass);
        } else if (state === 'success'){
            $(this).addClass(msgSuccessClass);
        }

        msg_html = msg_ico()+text;

        $(this).find('.'+msgInnerClass).html(msg_html);
        $(this).dequeue();

    }).delay(20).queue(function(){
        $(this).addClass(msgVisibleClass).dequeue();
    }).delay(hideDelay).queue(function(){
        $(this).removeClass(msgVisibleClass).dequeue();
    }).delay(3000).queue(function(){
        $(this).remove().dequeue();
    });

    $('.'+msgClass).on('click', function(){
        $(this).removeClass(msgVisibleClass);
    });

    $('.'+msgClass+'__close').on('click', function(e){
        $(this).closest('.'+msgClass).removeClass(msgVisibleClass);
        e.stopPropagation();
    });
}

window.showPopUpMessage = showPopUpMessage;

export default showPopUpMessage;