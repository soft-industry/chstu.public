import './cookie-agreement.scss';

function setCookie(name, value, options) {
    options = options || {};

    let expires = options.expires;

    if (typeof expires === 'number' && expires) {
        let d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    options.path = '/'; // Cookie for root

    value = encodeURIComponent(value);

    let updatedCookie = name + "=" + value;

    for (let propName in options) {
        updatedCookie += "; " + propName;
        let propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function createCookieAgreementWindow(){

    let body = $('body');

    const
        title = body.data(`${caName}-title`),
        message = body.data(`${caName}-message`),
        url = body.data(`${caName}-url`),
        accept = body.data(`${caName}-accept`),
        close  = body.data(`${caName}-close`),
        html = `
            <div class="${caName}">
                <div class="${caName}__wrapper">
                    <p>${title}. <strong><a href="${url}">${message}.</a></strong></p>
                    <div class="cookie-agreement__controls">
                        <button class="${caAcceptButtonClass} button button_sm button_style_brand-fill button_hover_dark">
                            ${accept}
                        </button>
                        <button class="${caCloseButtonClass} button button_sm button_style_brand-light button_hover_dark">
                            ${close}
                        </button>
                    </div>
                </div>
            </div>`;

    body.append(html);
}

function openCookieAgreementWindow() {
    setTimeout(function () {
        $(`.${caName}`).addClass(caVisibleBlockClass);
    }, 10);
}

function closeCookieAgreementWindow() {
    let caBlock = $(`.${caName}`);
    caBlock.removeClass(caVisibleBlockClass);
    setTimeout(function () {
        caBlock.remove();
    }, 1000);
}

function acceptCookieAgreementWindow() {
    let date = new Date();
    date.setDate(date.getDate() + 365);
    setCookie(caName, true, {expires: date.toUTCString()});

    closeCookieAgreementWindow();
}

const
    caName = 'cookie-agreement',
    caVisibleBlockClass = `${caName}_visible`,
    caAcceptButtonClass = `${caName}__accept`,
    caCloseButtonClass = `${caName}__close`;

function init() {

    if (!getCookie(caName)){
        createCookieAgreementWindow();
        openCookieAgreementWindow();
    }

    $(document).on('click', `.${caAcceptButtonClass}`, function () {
        acceptCookieAgreementWindow();
    });

    $(document).on('click', `.${caCloseButtonClass}`, function () {
        closeCookieAgreementWindow();
    });
}

export default init;