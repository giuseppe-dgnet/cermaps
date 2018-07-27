$(document).ready(function() {
    // Handler for .ready() called.
    $(".rdo_cer:even").css("background-color", "#d6eac9");
    $(".rdo_cer:odd").css("background-color", "#7dbf63");

    $('.form_gestione_rdo_admin').each(function(index, numero_form) {
        var theRules = {};
        $(this).validate({
            rules: theRules,
            submitHandler: function(form) {
                salvaRdoAdmin(form);
            }
        });
    });

    $('.load-detail').click(function(){
        var id = $(this).attr('rdo');
        $('.detail-rdo').html('Attendere caricamento...');
        $('.detail-row').hide();
        $('#espandi-'+id).show();
        $.post(Routing.generate('load_cer_admin_form'), {id: id}, function(html) {
            $('#detail-'+id).html(html);
            $('.autoupdate').change(function() {
                var form = $(this).closest('form');
                $.post(form.attr('action'), form.serialize(), function(out) {
                    $('.bt_fake').hide();
                    console.log(out);
                    if(out.status === 'OK') {
                        $("#stato-"+out.id).html(out.stato);
                        $("#admin-"+out.id).html(out.admin);
                        if(out.stato === 'Elimina' || out.stato === 'Chiusa' || out.stato === 'Pubblica') {
                            $('.rdo-'+out.id).remove();
                        }
                    } else {
                        alert(out.error);
                    }
                });
            });

            $('.showbutton').keyup(function() {
                $(this).closest('form').find('button').show();
            });
        });
    });


});

/**
 * Il form deve avere la action e un pulsante submit e un id
 * 
 * @param {object} form oggetto form che gli arriva dal validate
 */
function salvaRdoAdmin(form) {

    $.ajax({
        type: "POST",
        url: form.action,
        data: $("#" + form.id).serialize() + "&form_id=" + $("#" + form.id).find(":submit").attr('id').split("-").pop(),
        dataType: "html",
        beforeSend: function() {
            disabilitaElemento($("#" + form.id).find(":submit"));
            beforeElemento($("#" + form.id).find(":submit"), '<p class="feedback_form">' + Translator.get('messages:caricamento') + '</p>', 'slow');

        },
        success: function(msg) {
            msg = $.parseJSON(msg);
            if (msg.status == "OK") {
                $(".feedback_form").html(Translator.get('messages:success'));
            } else {
                $(".feedback_form").html(Translator.get('messages:error'));
            }

            eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 3000);
        },
        error: function(msg) {
            $(".feedback_form").html(Translator.get('messages:error'));
            eliminaElemento(".feedback_form", 2000);
            attivaElemento($("#" + form.id).find(":submit"), 2000);
        }
    });
}
