// Semaforo per far partire lo scroll
var scroll_active = false;
var scroll_available = false;
var scroll_on = false;
/* FOLLOWER */
var is_follower = false;
function followerPresente() {
    is_follower = true;
}
$(document).ready(function(){
    /* FOLLOWER */
    if(is_follower) {
        var offset = $(".slider").offset();
        var topPadding = 80;
        $(window).scroll(function() {
            if ($(window).scrollTop() > offset.top) {
                $(".slider").stop().animate({
                    marginTop: $(window).scrollTop() - offset.top + topPadding
                });
            } else {
                $(".slider").stop().animate({
                    marginTop: 0
                });
            };
        });
    }
    $(".breadcrumbs span").tipTip({
        keepAlive: false, 
        delay: 0
    });
    
    /* Abilita le notifiche dei messaggi */
    if( testVariabile('url_check_nuovi_messaggi') ) {
        checkNuoviMessaggi();
        window.setInterval( function() {
            checkNuoviMessaggi()      
        }, 60000);
    }
    if( testVariabile('url_check_notifiche_nuvola') ) {
        checkNuoveNotifiche();
        window.setInterval( function() {
            checkNuoveNotifiche()  
        }, 30000);
    }
    
    window.setInterval( function() {
        aggiornaTempiInvio()
    }, 15000);
    aggiornaTempiInvio()
    
    //se è definita url_scroll_result, attiva la funzione di load altri risultati
    if( testVariabile('url_scroll_result') ) {
        $(window).scroll(function(){
            if  ($(window).scrollTop() == $(document).height() - $(window).height()){
                if(scroll_active && scroll_available) {
                    scrollResult();
                }
            }
        }); 
    }
    
    /*
     * se è definita scroll_reload vengono ricaricati i risultati
     * precedentemente caricati, e deve essere un oggetto js così formato:
     * 
     * scroll_reload = [{'url': '/cerca/reload/1', 'listing': '#listing_1'}, {'url': '/cerca/reload/2', 'listing': '#listing_2'}]
     */
    if( testVariabile('scroll_reload') ) {
        reload_risultati();
    }

});

function reload_risultati()  { 
    scroll_reload.forEach(function(o) {
        $.post(o.url,
            function(data){
                if (data != "") {
                    $(o.listing).append(data);	
                    if( testVariabile('callback_scroll_result') ) {
                        eval(callback_scroll_result+'()');
                    }		
                }
            });
    });
};  


function aggiornaTempiInvio() {
    $('.date-stamp').each(function(){
        data = $(this).attr('time');
        if(data) {
            sec = Date.create().secondsSince(data);
            if(sec < 60) {
                $(this).text(sec+(sec == 1 ? ' secondo' : ' secondi')+' fa');
                return true;
            } 
            min = Date.create().minutesSince(data);
            if(min < 60) {
                $(this).text(min+(min == 1 ? ' minuto' : ' minuti')+' fa');
                return true;
            }
            hour = Date.create().hoursSince(data);
            if(hour < 24) {
                $(this).text(hour+(hour == 1 ? ' ora' : ' ore')+' fa');
                return true;
            }
            day = Date.create().daysSince(data);
            if(day < 7) {
                $(this).text(day+(day == 1 ? ' giorno' : ' giorni')+' fa');
                return true;
            }
            if(day < 30) {
                week = Date.create().weeksSince(data)
                $(this).text(week+(week == 1 ? ' settimana' : ' settimane')+' fa');
                return true;
            }
            mon = Date.create().monthsSince(data)
            if(mon < 12) {
                $(this).text(mon+(mon == 1 ? ' mese' : ' mesi')+' fa');
                return true;
            }
            year = Date.create().yearsSince(data)
            $(this).text(year+(year == 1 ? ' anno' : ' anni')+' fa');
            return true;
        }
    });
    $('.date-stamp').show();
}

function scrollResult()  { 
    if(!scroll_on) {
        scroll_on = true;
        $('#more-result').hide();
        if($(".listing-result").length > 0) {
            li = $('<li id="spinner-scroll" class="relative last margin-top-20"></li>').html('<div id="preloader-util">&nbsp;</div>');
            $(".listing-result").append(li);
        } else {
            div = $('<div id="spinner-scroll" class="relative last margin-top-20"></div>').html('<div id="preloader-util">&nbsp;</div>');
            $('#more-result-container').before(div);
        }
        spinnerBigDef.spin();
        $('#preloader-util').append(spinnerBigDef.el);
        $.post(url_scroll_result,
            function(data){
                //alert(data);
                if (data != "") {
                    if($(".listing-result").length > 0) {
                        $(".listing-result").append(data);	
                    } else {
                        $('#more-result-container').before(data);
                    }
                    if( testVariabile('callback_scroll_result') ) {
                        eval(callback_scroll_result+'()');
                    }		
                    $('#more-result').show();
                } else {
                    $('#more-result').hide();
                }
                spinnerBigDef.stop();
                $('#spinner-scroll').remove();
                scroll_on = false;
            });
    }
};  
		
