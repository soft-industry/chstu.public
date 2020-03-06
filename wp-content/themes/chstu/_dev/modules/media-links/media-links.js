import './media-links.scss';
import Clipboard from 'clipboard';

const copyBtnClass = 'media-links__item_cb';

function init() {

    let
        copyBtn = $(`.${copyBtnClass}`),
        copyBtnDoneText = copyBtn.data('copied-success');

    if (!copyBtnDoneText) copyBtnDoneText = 'Copied to clipboard';

    new Clipboard(`.${copyBtnClass}`, {
        text: function() {
            if (window.showPopUpMessage) window.showPopUpMessage('success', copyBtnDoneText);
            return window.location.href;
        }
    });
}

export default init;

