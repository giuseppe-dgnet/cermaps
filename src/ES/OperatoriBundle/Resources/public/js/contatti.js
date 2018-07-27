$(document).ready(function() {

    $.validator.addMethod('phone', function(value) {
        //faccio questo if cosi può anche essere vuoto
        var numbers = value.split(/\d/).length - 1;
        return (8 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)){8,20}$/));
    }, 'inserisci un numero di telefono valido');

    $.validator.addMethod('fax', function(value) {
        //faccio questo if cosi può anche essere vuoto
        if (value) {
            var numbers = value.split(/\d/).length - 1;
            return (8 <= numbers && numbers <= 20 && value.match(/^(\+){0,1}(\d|\s|\(|\)){8,20}$/));
        } else {
            return true;
        }
    }, 'inserisci un numero di fax valido');
    
    
    $.validator.addMethod("complete_url", function(val, elem) {
    // if no url, don't do anything
    if (val.length == 0) { return true; }
    // if user has not entered http:// https:// or ftp:// assume they mean http://
    if(!/^(https?|ftp):\/\//i.test(val)) {
        val = 'http://'+val; // set both the value
        $(elem).val(val); // also update the form element
    }
    // now check if valid url
    // http://docs.jquery.com/Plugins/Validation/Methods/url
    // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
    return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&amp;'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(val);
});

    validaFormContatti();
});


/**
 * la funzione deve essere richiamata nel document ready
 */
function validaFormContatti() {

    $("#form_balloon").validate({//id del form
        rules: {
            'showroom[telefono]': {
                phone: true
            },
            'showroom[fax]': {
                fax: true
            },
            'showroom[email]': {
                email: true
            },
            'showroom[email_pec]': {
                email: true
            },
            'showroom[sito]': {
               complete_url:true
            }
        },
        submitHandler: function(form) {
            balloon_form_submit(form, true);
        }
    });
}