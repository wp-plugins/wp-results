/* CUSTOMIZER JS */

	//var api = wp.customize;
	//console.log(wp.customize.control( 'blogname' ).section( 'nav' ));

	// Site title and description.
/*	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '#header h1 a, #footer a.site-name' ).html( to );
		} );
	} );
	
	api( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '#header p.site-description' ).html( to );
		} );
	} );*/

	//api.control("blogname").priority(100);




( function( $ ) {
//jQuery(document).ready(function() {
//wp.customize.preview.send( 'focus-widget-control', widgetId );
	//wp.customize.bind('ready', function() {

		//alert('test');
/*		wp.customize.panel.each( function ( panel ) { 
			console.log(panel);
		} );*/

	//});
//});
	//alert('sdsd');

/*	$(document).on('click', "#customize-controls", function() {
		alert('donateString');
	});*/


	


} )( jQuery );

jQuery(document).ready(function($) {
	$(document).on('click', "body.wp-full-overlay expanded", function() {
		alert('donateString');
	});
});