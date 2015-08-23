<?php
class admin_column_frontend {
	
	/* ####################################################################### */
	/* Class properties */
	/* ----------------------------------------------------------------------- */
	
	public $data_schema = array();
	public $rows_color_counter = 0;
	public $action_cell = false;

	/* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */

	/* ####################################################################### */
	/* Constructor */
	/* ----------------------------------------------------------------------- */
	public function __construct(){

	}

	public function get_AC_schema($target){
		

		$this -> data_schema = get_option( 'cpac_options_'.$target);

/*		echo '<pre>';
		var_dump($this -> data_schema);
		echo '</pre>';*/

	}

	public function render_loop_header(){

		?>
		<div class="wp_list">
			<div class="wp_list_header">
				
				<?php foreach ($this -> data_schema as $key => $value) { ?>
					<div class="wp_list_header_cell" style=""><?php echo $value['label'] ?></div>
				<?php } ?>


				<?php 
					/* Check ACTION CELL */
					if($this -> action_cell != false){
						?>
							<div class="wp_list_header_cell"><?php echo $this -> action_cell;?></div>
						<?php
					}
				?>	
				
			</div>
		<?php
	}

	public function get_value($key,$value){
			
			global $post;

			if($key == 'date'){
				$parsed_data[$key]['value'] = get_the_date();
			}

			if(($key == 'title')||($key == 'title-1')){	
				$parsed_data[$key]['url'] = get_permalink($post->ID);
				$parsed_data[$key]['value'] = get_the_title();
			}

			if($key == 'column-excerpt'){	
				$parsed_data[$key]['value'] = $post->post_excerpt;
			}

			if($key == 'column-slug'){
				$parsed_data[$key]['value'] = $post->post_name;						
			}

			if($key == 'column-attachment'){						
				$parsed_data[$key]['value'] = get_the_post_thumbnail($post->ID,$value['image_size']);
			}

			if($key == 'author'){
				$parsed_data[$key]['value'] = get_the_author();						
			}

			if($key == 'column-content'){
				$parsed_data[$key]['value'] = get_the_content();
			}

			if(($key == 'column-meta')||($key =='column-meta-1')||($key =='column-meta-2')||($key =='column-meta-3')||($key =='column-meta-4')){
				$field = $value['field'];

				$parsed_data[$key]['value'] = get_post_meta( $post->ID , $field , true);

				if(is_array ( $parsed_data[$key]['value'] )){
					$parsed_data[$key]['value'] = reset($parsed_data[$key]['value']);
				}

				if($value['field_type']=='image'){
					$thumb = wp_get_attachment_image_src( get_post_meta( $post->ID , $field , true)  );
					$url = $thumb['0']; 
					$parsed_data[$key]['value'] = '<img src="'.$url.'"/>';
				}

			}

			if($key == 'categories'){
				if($value['type'] == 'categories' ){
					$type = 'category';
				}
				$args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');
				$terms = wp_get_post_terms( $post->ID, $type, $args );
				$my_terms = '';
				foreach ($terms as $key1 => $value1) {
					$parsed_data[$key]['value'] .= '<span>'.$value1->name.'</span>  ';
				}
			}

			if($key == 'tags'){
					$terms = wp_get_post_tags($the_query->post->ID);
					$my_terms = '';
					foreach ($terms as $key1 => $value1) {
						//var_dump($value1);
						$parsed_data[$key]['value'] .= '<span><a href="#">'.$value1->name.'</a></span>  ';
					}
			}

			return $parsed_data[$key];

	}

	public function render_loop_footer(){

		echo '</div>';
	
	}
	/* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */
	
	/* Scripts list --------------------------------------------- */
	/* queries types
		['important': run and save as new ]
		['diff': check on list and run is unique as new ]
		['query_id': run as selected on list]
	*/

	
}