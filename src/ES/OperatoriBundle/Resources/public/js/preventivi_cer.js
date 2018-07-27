var a_cer = new Array();
var switch_rdo = false;
$(document).ready(function(){
    $('.anchor_cer').click(function () {
        $('#select_cer').slideDown('fast');
    });
        
    $('ul.act-list > li').click(function () {
        if (!switch_rdo) {
            switch_rdo = true;
            all = $(this).attr('all');
            if(all) {
                $('.'+all).toggleClass('selected');
                $('.'+all).find('.add').switchClass( "add", "remove", 1 ).text('Rimuovi');
                $('.'+all).find('.remove').switchClass( "remove", "add", 1 ).text('Aggiungi');
            } else {
                $(this).toggleClass('selected');
                $(this).find('.add').switchClass( "add", "remove", 1 ).text('Rimuovi');
                $(this).find('.remove').switchClass( "remove", "add", 1 ).text('Aggiungi');
                all = $(this).attr('id');
            }
            if(all) {
                prefix = $(this).parent().attr('a');
                elem_id = all.remove(prefix);
                list = eval('a_'+prefix);
                if(list.find(function() { return elem_id; })) {
                    list.remove(elem_id)
                    if(list.isEmpty()) {
                        $('#submit_'+$(this).closest('ul').attr('a')).slideUp('fast');
                    }
                } else {
                    list.add(elem_id);
                    $('#submit_'+$(this).closest('ul').attr('a')).slideDown('fast');
                }
                $('#n_'+prefix).html(list.length);
            }
            switch_rdo = false;
        }
    });
 
    if(!modificabile) {
        // Per apertura fancybox con form RDO
        $("a.rdoRequestCer").fancybox({
            helpers : {
                overlay : {
                    css : {
                        'background-color' : 'rgba(0,0,0,.85)'
                    },
                    speedIn : 500,
                    opacity : 0.5
                }
            },
            title: false,
            type: 'ajax',
            padding:8,
            margin:0,
            beforeShow : function() {
                $(".fancybox-skin").css("backgroundColor","rgba(255,255,255,.5)");
                $(".fancybox-inner").css("backgroundColor","rgba(255,255,255,1)");
            },
            afterClose: function() {
                $('.box-rdo').hide();
                $('#console-rdo').show();
                resetta_richieste("cer");
            }
        });
    } else {
        $(".rdoRequestCer a").attr('href', 'javascript:void(0);');
        $("a.rdoRequestCer").click(function(){
            fancyAlert('Operazione non consentita sul proprio Showroom.');
            event.preventDefault();
            return false;
        });
    }
});

//potrebbe essere migliorato ma non mi viene in mente al momento una soluzione
function resetta_richieste() {
    a_cer.each(function(n) {  
        $("#cer"+n).toggleClass('selected');
        $("#cer"+n).find('.remove').switchClass( "remove", "add", 1 ).text('Aggiungi');
        a_cer = [];
        $('#n_cer').html(0);
        $('#submit_cer').hide();
    });
}

function showCer(type) {
    $('.allFilter').css('opacity', '1');
    $('.perFilter').css('opacity', '0.5');
    $('.npFilter').css('opacity', '0.5');
    $('.cer-per').closest('li').show();
    $('.cer-np').closest('li').show();
    if(type === 'per') {
        $('.cer-np').closest('li').hide();
        $('.allFilter').css('opacity', '0.5');
        $('.npFilter').css('opacity', '0.5');
        $('.perFilter').css('opacity', '1');
    }
    if(type === 'np') {
        $('.cer-per').closest('li').hide();
        $('.allFilter').css('opacity', '0.5');
        $('.npFilter').css('opacity', '1');
        $('.perFilter').css('opacity', '0.5');
    }
}