// Node Modules
import '@babel/polyfill';
import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;
// import 'swiper/dist/css/swiper.min.css';
// import { Swiper, Navigation, Pagination, EffectCoverflow, Keyboard } from 'swiper/dist/js/swiper.esm.js';
// window.Swiper = Swiper;
import ScrollHash from '../../modules/scroll-hash';
import './index.scss';

// Localization
import '../../languages/ru_RU.po';
import '../../languages/uk.po';

// Components
// import '../../components/button.scss';

// Modules
import Header from '../header/header';
import FrontPage from '../../templates/front-page/front-page';
import Footer from '../footer/footer';
import '../../modules/language-switcher/language-switcher';
import CookieAgreement from '../../modules/cookie-agreement/cookie-agreement';

// Resources
import ArrowLeftSvg from '../../resources/images/arrow-left.svg?inline-js';

if (process.env.NODE_ENV !== 'production') console.log(`jQuery version: ${$().jquery}`);


// For removing google recaptcha empty div (bug with fixed height),
// and Wordpress clear: both div
function removeUselessLayers(){
    $('body>div').each(function () {
        if (
            (
                (
                    $(this).css('position') === 'absolute' &&
                    $(this).css('z-index') === '-10000' &&
                    parseInt($(this).css('top')) === 0 &&
                    parseInt($(this).css('left')) === 0 &&
                    parseInt($(this).css('right')) === 0
                ) ||
                $(this).css('clear') === 'both'
            ) &&
            !$(this).html()
        ){
            $(this).remove();
        }
    });
}

$(document).ready(function() {

    // Swiper.use([Navigation, Pagination, EffectCoverflow, Keyboard]);
    Header();
    FrontPage();
    Footer();
    //CookieAgreement();
    // AOS.init({
    //     duration: 1000,
    //     easing: 'ease-out-sine',
    //     disable: 'mobile'
    // });
    ScrollHash;

    $(`.has-arrow-left`).each(function () {
        $(this).append(ArrowLeftSvg);
    });

    setTimeout(function () {
        removeUselessLayers();
    }, 1000);

});