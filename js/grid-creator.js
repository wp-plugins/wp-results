this.grid = {}
var render_blocks;

var GRID_CREATOR = {};


(function($) { 
//jQuery(document).ready(function($) {

	

	

	
	
	


	GRID_CREATOR = {

		sidebar_target: true,
		schema: {},

		reset_schema: function(){
			this.schema = {
				'top_header':true,
				'wp_header':true,
				'results_header':true,
				'left_bar':true,
				'results_loop':true,
				'right_bar':true,
				'bottom_bar':true,
				'bottom_bar_half':{'one':true,'two':true},
				'bottom_bar_third':{'one':true,'two':true,'three':true},
				'wp_footer':true
			}
		},
		reset_grid: function(){
			this.grid = {
				'top_header': true,
				'wp_header': true,
				'results_loop': true,
				'wp_footer': true,
			}
		},

		render_blocks: function(){

			this.grid = this.resort_keys( this.schema );

			$('#grid article').children().remove();
			$( ".grid-btn div" ).removeClass('button-primary');

			var main_content = 0;
			//console.log(grid);
			$.each( this.grid, function( index, value ) {
				if(( index != 'right_bar' )&&( index != 'left_bar' )&&( index != 'bottom_bar_half' )&&( index != 'bottom_bar_third')&&(index != 'results_loop')){
					// add row
					if(( index != 'wp_header' )&&( index != 'wp_footer' )){
						$("#one-column-with-link").tmpl({index: index}).appendTo("#grid article");
					}else{					
						$("#one-column").tmpl({index: index}).appendTo("#grid article");
					}				
				}

				// bottom bars
				if(index == 'bottom_bar_half'){
					//$('#grid article').append(output);
					$("#two-columns").tmpl({index: index}).appendTo("#grid article");
				}
				if(index == 'bottom_bar_third'){
					//$('#grid article').append(output);
					$("#three-columns").tmpl({index: index}).appendTo("#grid article");
				}

				// loop, left and right
				if((index == 'right_bar')||(index == 'left_bar')||(index == 'results_loop')){
					if(main_content==0){
						$("#loop-tpl").tmpl({}).appendTo("#grid article");
					}
					main_content++;
				}

				// set button
				//$( ".grid-btn div[data-name='"+index+"']" ).removeClass('button');
				$( ".grid-btn div[data-name='"+index+"']" ).toggleClass('button-primary');
			});

			if(main_content == 1){
				var row = 'twelve column';
				var bar = 'twelve column'
			}
			if(main_content == 2){
				var row = 'two-thirds column';
				var bar = 'one-third column'
			}
			if(main_content == 3){
				var row = 'one-third column';
				var bar = 'one-third column'
			}

			if(this.grid['left_bar']!=undefined){
				$("#left-bar").tmpl({bar: bar}).appendTo(".loop");
			}
			if(this.grid['results_loop']==true){
				$("#loop-cell").tmpl({row: row}).appendTo(".loop");
			}
			if(this.grid['right_bar']!=undefined){
				$("#right-bar").tmpl({bar: bar}).appendTo(".loop");
			}

			//colored linked elements
			//console.log(value);
			$.each(this.grid, function( index, value ) {
				
				if(value != true){

					if(value['id'] != undefined){
						
						if((index == 'right_bar')||(index == 'left_bar')){

							$('section div[data-name="'+index+'"]').addClass('green');
							$('section div[data-name="'+index+'"]').append('<pre class="reg_sidebar">['+value['name']+']</pre>');
							
						}else{

							$('section[data-name="'+index+'"]').children().addClass('green');
							$('section[data-name="'+index+'"]').children().append('<pre class="reg_sidebar">['+value['name']+']</pre>');
						}

					}else{

						$.each(value, function(index1, value1){
							if(value1 != true){
								$('section div[data-name="'+index+'-'+index1+'"]').addClass('green');
								$('section div[data-name="'+index+'-'+index1+'"]').append('<pre class="reg_sidebar">['+value1['name']+']</pre>');
							}
						});

					}

				}
			});

		},
		resort_keys: function ( schema ){
			var tech_schema = {};
			var _grid = this.grid;
			
			if(this.grid == undefined){
				alert('I cant loaded data, check server permision or internet access.');
				return schema;
			}
			
			$.each(schema, function( index, value ) {
				if(_grid[index] != undefined){
					//tech_schema[index] = value;
					if(value == true){
						tech_schema[index] = _grid[index];
					}else{
						tech_schema[index] = value;
					}

					console.log(value);
				}
			});
			return tech_schema;
		
		},
		link_sidebar: function( sidebar, target ){

			var input = target.split('-');

			if(input[1] == undefined){
				this.schema[input[0]] = sidebar;			
			}else{
				this.schema[input[0]][input[1]] = sidebar;			
			}

			this.render_blocks();
		}
	};

	GRID_CREATOR.reset_grid();
	GRID_CREATOR.reset_schema();
	GRID_CREATOR.render_blocks();


	$('#grid_controlls .button').click(function(){
		if($(this).hasClass('button-primary')){
			
			delete GRID_CREATOR.grid[$(this).attr('data-name')];
			GRID_CREATOR.render_blocks();
		
		}else{

			GRID_CREATOR.grid[$(this).attr('data-name')] = true;
			GRID_CREATOR.render_blocks();
		}
	});

	//$('article .grid-btn .button').live('click',function(){
	$('#grid').on('click','.grid-btn .button',function(){	

		var sidebar = {'id':null,'name':null};
		sidebar['id'] = $(this).attr('data-name');
		sidebar['name'] = $(this).text();
		GRID_CREATOR.link_sidebar(sidebar,sidebar_target);

		$('#sidebar-list-body').remove();
		$('#grid article').fadeIn();

	});

	$('#grid').on('click','.dashicons-admin-links',function(){


		//$('#grid article').fadeOut();
		$('#grid article').fadeOut( 250 , function() {
		    $("#sidebars-list").tmpl({}).prependTo("#grid");
		});
		

		sidebar_target = $(this).closest('section').attr('data-name');
		if(sidebar_target == 'container'){
			sidebar_target = $(this).closest('.column').attr('data-name');
		}

	});



//});
})(jQuery);