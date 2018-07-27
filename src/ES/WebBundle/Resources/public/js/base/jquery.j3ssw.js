(function($) {

	$.fn.j3ssw = function(options){

		var j3ssw = this;

		var settings = jQuery.extend({
				index:		0,
				status:		false,
				def: 		"j3sswdef",
				off: 		"j3sswoff",
				on: 		"j3sswon",
				mode:		2,
				callback:	function(selection, status, id, j3sswobj){}
		},options||{});

		return this.each(function() {

			var container = $(this);
			var currentstatus = settings.def;
			var defaultradio;
			var radiovalue;
			var checkbox;

			container.find(":radio").click(function(){
				
				var isRadioSelected = (radiovalue == $(this).val());
				var isCheckSelected = (checkbox.attr("checked"));

				switch(settings.mode){
					case 1:break;
					case 2:if(isRadioSelected){checkbox.click();};break;
					case 3:if(isRadioSelected){if(!isCheckSelected){checkbox.click();}
												else{$(this).removeAttr("checked");
													checkbox.removeAttr("checked");}}
							else{$(this).attr("checked", true);checkbox.removeAttr("checked");};
							break;
				}
				defaultradio = container.find(":radio").filter(":checked");
				radiovalue = defaultradio.val();
				update($(this));
				
				
			});

			j3ssw.getStatus = function(){
				return checkbox.attr("checked");
			};

			j3ssw.getSelection = function(){
				return radiovalue;
			};

			function update(radio){
				var id = radio.attr("id");
				var isRadioSelected = (radiovalue == radio.val());
				var isCheckSelected = (checkbox.attr("checked"));

				switch(settings.mode){
					case 1:currentstatus = isRadioSelected?settings.on:currentstatus;break;
					case 2:currentstatus = isRadioSelected?(isCheckSelected?settings.off:settings.on):currentstatus;break;
					case 3:currentstatus = isRadioSelected?(isCheckSelected?settings.off:settings.on):settings.def;break;
				}
				container.find("label").removeClass(settings.on).removeClass(settings.off).removeClass(settings.def);
				container.find("label[for!='" + id + "']").addClass(settings.def);
				container.find("label[for='" + id + "']").addClass(currentstatus);

				settings.callback(radiovalue, checkbox.attr("checked"), container.attr("id"), j3ssw);

			}
			container.prepend('<input type="checkbox">');
			container.find("input").css({"position":"absolute", "top":"-99999px", "left":"-99999px"});
			container.find("label").addClass(settings.def);
			container.find("label").hover(function(){
				$(this).css("cursor","pointer"); 
				},function(){
				$(this).css("cursor","default");
			});

			checkbox = container.find(":checkbox").attr("checked", settings.status);
			defaultradio = container.find(":radio").eq(settings.index).attr("checked", true);
			radiovalue = defaultradio.val();
			update(defaultradio);

		});
	};
})(jQuery);
