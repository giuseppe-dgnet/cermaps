var a_mps = new Array();
var switch_rdo = false;
$(document).ready(function(){
    $('.anchor_mps').click(function () {
        $('#select_mps').slideDown('fast');
    });
    $('ul.menu-mps li a').click(function(){
        switch_tabs($(this), '.content-mps');
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
                $("#mps_mps_list").val(list);
            }
            switch_rdo = false;
        }
    });
 
    if(!modificabile) {
        // Per apertura fancybox con form RDO
        $("a.rdoRequestMps").fancybox({
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
                resetta_richieste("mps");
            }
        });
    } else {
        $(".rdoRequestMps a").attr('href', 'javascript:void(0);');
        $("a.rdoRequestMps").click(function(){
            fancyAlert('Operazione non consentita sul proprio Showroom.');
            event.preventDefault();
            return false;
        });
    }
});

//potrebbe essere migliorato ma non mi viene in mente al momento una soluzione
function resetta_richieste() {
    a_mps.each(function(n) {  
        $("#mps"+n).toggleClass('selected');
        $("#mps"+n).find('.remove').switchClass( "remove", "add", 1 ).text('Aggiungi');
        a_mps = [];
        $('#n_mps').html(0);
        $('#submit_mps').hide();
    });
}

function showCer(type) {
    $('.allFilter').css('opacity', '1');
    $('.perFilter').css('opacity', '0.5');
    $('.npFilter').css('opacity', '0.5');
    $('.mps-per').closest('li').show();
    $('.mps-np').closest('li').show();
    if(type === 'per') {
        $('.mps-np').closest('li').hide();
        $('.allFilter').css('opacity', '0.5');
        $('.npFilter').css('opacity', '0.5');
        $('.perFilter').css('opacity', '1');
    }
    if(type === 'np') {
        $('.mps-per').closest('li').hide();
        $('.allFilter').css('opacity', '0.5');
        $('.npFilter').css('opacity', '1');
        $('.perFilter').css('opacity', '0.5');
    }
}