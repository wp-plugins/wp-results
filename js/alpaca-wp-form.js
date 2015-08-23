(function($){
	/* Init form */
	/* 1. Render form on_load or on_action ? */
	/* 2. Get data - from options or serialized_meta or widget_instance ? */
	/* 3. Save data model [save on save_post action, save as send http_post, save on ajax request] */
	//console.log(ajax_object);
	
	/* WP WIDGETS */
	window.init_widgets_methods = function(){

		$( document ).ajaxComplete( function( event, XMLHttpRequest, ajaxOptions ) {
			try{
				var request = {}, pairs = ajaxOptions.data.split('&'), i, split, widget;
			}catch(e){
				console.log('%c I cath null request with ajaxComplete action. Sys error name:' +e, 'background: #222; color: orange');
			}
			console.log(JSON.stringify(ajax_object));
			//var request = {}, pairs = ajaxOptions.data.split('&'), i, split, widget;
			for( i in pairs ) {
				split = pairs[i].split( '=' );
				request[decodeURIComponent( split[0] )] = decodeURIComponent( split[1] );
			}

			if( request.action && ( request.action === 'save-widget' ) ) {
				widget = $('input.widget-id[value="' + request['widget-id'] + '"]').parents('.widget');
				if( !XMLHttpRequest.responseText ) 
					wpWidgets.save(widget, 0, 1, 0);
				else
					if(widget.find('.widget_marker').attr('data-guardian') == 'true'){
						render_form_with_widget(widget);
					}
			}
		});		
		
		/* Render form to right activation widget by click */
		var widget_open_handler = function(){
			var orig_slideDown = $.fn.slideDown;
			$.fn.slideDown = function(){
				$(this).trigger('slideDown');
				orig_slideDown.apply(this, arguments);
			};
			$('.widget-inside').bind('slideDown',function(){
				
				if($(this).find('.widget_marker').attr('data-guardian') == 'true'){
					render_form_with_widget($(this));
				}
			});
		}
		widget_open_handler();

		var render_form_with_widget = function(_target){

			ajax_object['render']['id'] = _target.find('.widget-id').val();
			ajax_object['render']['tech_data_id'] = 'widget-'+_target.find('.widget-id').val()+'-tech_widget_data';
			
			ajax_object['data'] = {'base':$('#'+ajax_object['render']['tech_data_id']).val()};
			ajax_object['data']['base'] = JSON.parse(decodeURIComponent(ajax_object['data']['base']));
			
			$('#'+ajax_object['render']['id']).remove();
			_target.prepend($('<div id="'+ajax_object['render']['id']+'"></div>'));
			render_alpaca(ajax_object['render']['id']);
		}

		/*var init_render_form_with_widget = function(_target){
			alert('cc');
			_target.prepend( '<div style="border:10px solid red" id="'+ajax_object['render']['render_handler']+'" ></div>');
			//render_alpaca(ajax_object['render']['render_handler']);
		}

		init_render_form_with_widget( $('#'+ajax_object['render']['render_handler']) );*/
	}

	window.init_post_meta_methods = function(){
		
		var el_ID = ajax_object['name'];
		ajax_object['render']['tech_data_id'] = 'alpaca-data-'+el_ID;
		ajax_object['data']['base'] = JSON.parse(decodeURIComponent(ajax_object['data']['base']));
		render_alpaca(el_ID);
		/*$( "#" + el_ID  ).alpaca({
			"data" : JSON.parse(decodeURIComponent(ajax_object['form_data'])),
			"optionsSource": ajax_object['paths']['base'] + ajax_object['paths']['schemas'] + ajax_object['name'] + "-options.json",
			"schemaSource": ajax_object['paths']['base'] + ajax_object['paths']['schemas'] + ajax_object['name'] + "-schema.json",
			"postRender": function(renderedForm) {
				var _target = 'alpaca-data-'+el_ID;
				$( '#' + el_ID + ' select, #' + el_ID + ' input, #' + el_ID + ' textarea').live( 'change', function() {
					_val = renderedForm.getValue();
					$( '#' +   _target ).val( encodeURIComponent( JSON.stringify( _val )));
				});
			}
		});*/
	}

	function render_alpaca(el_ID){
		/* render alpaca form */
		$( "#" + el_ID  ).alpaca({
			"data" : ajax_object['data']['base'],
			"optionsSource": ajax_object['paths']['base'] + ajax_object['paths']['schemas'] + ajax_object['name'] + "-options.json",
			"schemaSource": ajax_object['paths']['base'] + ajax_object['paths']['schemas'] + ajax_object['name'] + "-schema.json",
			"postRender": function(renderedForm) {
				var _target = ajax_object['render']['tech_data_id'];
				$( '#' + el_ID + ' select, #' + el_ID + ' input, #' + el_ID + ' textarea').live( 'change', function() {
					_val = renderedForm.getValue();
					$( '#' +   _target ).val( encodeURIComponent( JSON.stringify( _val )));
				});
			}
		});
	}

	// CALLBACK FUNCTION TO SWITH INIT BY FORM TYPE
	// all init types declarate on window.fname()
	callback = ajax_object['run'];
	window[callback]();
	// --------------------------------------------

})(jQuery)

//init_widgets_methods();
/* chage json to string */
/*var prepareData = function (data,method){
	if(( method == 'wp_postmeta') || ( method == 'wp_widget')) ({
		return encodeURIComponent( JSON.stringify( data ));
	}
	if( method == 'wp_options'){
		return data;
	}
}*/
/* escape data object to display into alpaca form */
/*$( '#' + ajax_object['render']['tech_data_id'] ).val(ajax_object['form_data']);
try{
    data=JSON.parse(decodeURIComponent(ajax_object['form_data']));
}catch(e){
    //alert(e); //error in the above string(in this case,yes)!
    data = {};
}*/


/* old file version - with postbox */

/*var prepareData = function (data,method){
		if( method == 'wp_postmeta'){
			return encodeURIComponent(JSON.stringify(data));
		}
		if( method == 'wp_options'){
			return data;
		}
	}
	var _id = ajax_object['form_id'];
	
	$('#alpaca-data-' +  _id).val(ajax_object['form_data']);
    try{
        data=JSON.parse(decodeURIComponent(ajax_object['form_data']));
    }catch(e){
        //alert(e); //error in the above string(in this case,yes)!
        data = {};
    }

	$( "#" + _id ).alpaca({
		"data" : data,
		"optionsSource": ajax_object['paths']['base'] + '/' + ajax_object['paths']['schemas'] + _id + "-options.json",
		"schemaSource": ajax_object['paths']['base'] + '/' + ajax_object['paths']['schemas'] + _id + "-schema.json",
		"postRender": function(renderedForm) {
			$( '#' + _id + ' select, #' + _id + ' input, #' + _id + ' textarea').live( 'change', function() {
				var val = renderedForm.getValue();
				$('#alpaca-data-' +  _id).val(encodeURIComponent(JSON.stringify(val)));
			});
		}
	});
*/

