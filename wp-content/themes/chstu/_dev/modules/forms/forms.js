import './forms.scss';
import showPopUpMessage from '../messages/messages';
import 'jquery-validation';

const
    formClass = 'form',
    captchaClass = 'form__captcha',
    captchaInputSelector = 'input[name="captcha"]';

let captchaState;

function toggleFormProcessing(thisForm, state){
    if (state){
        thisForm.find('button[type="submit"]').prop('disabled', true);
    } else {
        thisForm.find('button[type="submit"]').prop('disabled', false);
    }
    thisForm.toggleClass(`${formClass}_processing`);
}

function resetForm(thisForm){
    const validForm = thisForm.validate();
    validForm.resetForm();
    thisForm[0].reset();
    const formControls = thisForm.find(`.${formControlsClass}`);
    formControls.removeClass(formControlsVisibleClass);
}

function sendForm(thisForm){

    const data = new FormData(thisForm.get(0));
    const captcha = captchaInForm(thisForm);
    data.append('form_type', thisForm.data('form-type'));

    $.ajax({

        dataType: 'json',
        url: thisForm.attr('action'),
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',

        beforeSend: function() {
            toggleFormProcessing(thisForm, true);
        },

        success: function(data){
            if (data['error']) {
                if ( data['error'].name === 'captcha' ){
                    captcha.clearInput();
                    captcha.focusInput();
                }
                captcha.removeValidState();
                toggleFormControls(thisForm, true);
                showPopUpMessage('error', data['error'].text);
            } else if (data['success']){
                showPopUpMessage('success', data['success']);
                resetForm(thisForm);
                captcha.removeValidState();
                toggleFormControls(thisForm, false);
                executeAdditionalCode(data['add_js_code']);
            }
        },

        error: function (data, xhr, ajaxOptions) {
            console.error(xhr);
            console.error(ajaxOptions);
            console.error(data);
            console.error(data.responseText);
            showPopUpMessage('error', thisForm.data('message-broken-form'));
        },

        complete: function() {
            toggleFormProcessing(thisForm, false);
        }
    });
}

function formSubmit(){
    $(document).on('submit', `.${formClass}`, function (e) {
        e.preventDefault();
        if ( $(this).valid() ) sendForm($(this));
    });
}

const
    formControlsClass = 'form__controls',
    formControlsVisibleClass = `${formControlsClass}_visible`,
    inputPostRequiredData = 'post-required',
    inputPostRequiredSelector = 'input[data-'+inputPostRequiredData+'="true"]';

function setPostRequiredInputs(thisFormControls, state){

    thisFormControls.find(inputPostRequiredSelector).each(function (){

        if (state){
            $(this).prop('required', true);
        } else {
            $(this).removeAttr('required');
        }
    });
}

function getFieldsValidation(thisForm){

    const allInputs = thisForm.find('input, textarea, select').not('.ignore');
    let validationState = true;

    allInputs.each(function () {
        if ( !$(this).valid() ) validationState = false;
    });

    return validationState;
}

function changeFormsInputs() {
    $(document).on('input', `.${formClass} input, .${formClass} textarea`, function () {
        let form = $(this).closest(`.${formClass}`);
        if ( getFieldsValidation(form) ){
            let controls = form.find(`.${formControlsClass}`);
            if (!controls.hasClass(formControlsVisibleClass)){
                toggleFormControls(form, true);
            }
        }
    });
}

function captchaInForm(form) {

    const captchaInput = form.find(`.${captchaClass} ${captchaInputSelector}`);

    function clickOnImageEvent() {
        if (captchaState){
            form.find(`.${captchaClass}-img`).on('click', function(){
                genCaptcha();
                focusInput();
            });
        }
    }

    function genCaptcha() {
        if (captchaState){
            const captchaImage = form.find(`.${captchaClass}-img`);
            const url = captchaImage.data('src');
            captchaImage.prop('src', url+'?'+Math.random());
        }
    }

    function clearInput() {
        if (captchaState) captchaInput.val('');
    }

    function removeValidState() {
        if (captchaState) captchaInput.removeClass('valid');
    }

    function focusInput() {
        if (captchaState) captchaInput.focus();
    }

    return {
        genCaptcha: genCaptcha,
        clearInput: clearInput,
        removeValidState: removeValidState,
        focusInput: focusInput,
        clickOnImageEvent: clickOnImageEvent
    }
}

function validateForm(thisForm) {

    $.validator.messages.required = thisForm.data('message-required');

    thisForm.validate({
        messages: {
            name: thisForm.data('message-name-error'),
            email: {
                required: thisForm.data('message-email-error'),
                email: thisForm.data('message-email-format-error')
            },
            message: {
                required: thisForm.data('message-text-error'),
                minlength: jQuery.validator.format(thisForm.data('message-text-length-error'))
            }
        },
        ignore: '.ignore',
        errorPlacement: function(error, element) {
            if (
                element.attr('type') === 'checkbox' &&
                element.closest('.checkbox')
            ){
                let wrapper = element.closest('.label-with-checkbox');
                if (wrapper){
                    error.insertAfter(wrapper);
                } else {
                    error.insertAfter(element.closest('.checkbox'));
                }
            } else if ( element.attr('name') === 'captcha' ){
                error.appendTo(element.closest('.input-wrapper'));
            } else {
                error.insertAfter(element);
            }
        }
    });
}

function executeAdditionalCode(strCode) {
    if (strCode){
        const addJsCode = new Function (strCode);
        return (addJsCode());
    }
}

function toggleFormControls(form, state){

    let controls = form.find(`.${formControlsClass}`);

    if ( state ){
        captchaInForm(form).genCaptcha();
        setPostRequiredInputs(controls, true);
        controls.addClass(formControlsVisibleClass);
    } else {
        controls.removeClass(formControlsVisibleClass);
        setPostRequiredInputs(controls, false);
    }
}

function init(){

    window.showPopUpMessage = showPopUpMessage;

    const defaultForms = $(`.${formClass}`);

    captchaState = $('body').data('captcha-state');

    defaultForms.each(function () {
        captchaInForm($(this)).clickOnImageEvent();
        validateForm($(this));
    });
    changeFormsInputs();
    formSubmit();
}

export default init;