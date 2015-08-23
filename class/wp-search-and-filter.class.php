<?php
class wp_search_and_filter {

	/* example concept with html: */
	/*
	<p><h4>First Question for material</h4></p>
	<input type="hidden" name="meta_query[0][key]" value="material"/>
	<p>
		<select name="meta_query[0][value]">
			<option value="Rubber">Rubber</option>
			<option value="Rubber - Conductive">Rubber - Conductive</option>
			<option value="Polyurethane">Polyurethane</option>
			<option value="Solid Elastomer">Solid Elastomer</option>
			<option value="Nylon">Nylon</option>
			<option value="HD Plastic">HD Plastic</option>
			<option value="Resin HT">Resin HT</option>
			<option value="Resin">Resin</option>
			<option value="Cast Iron">Cast Iron</option>
			<option value="Steel">Steel</option>
			<option value="Forged Steel">Forged Steel</option>
		</select>
	</p>

	<p><h4>Second Question for load capacity (kg)</h4></p>
	<input type="hidden" name="meta_query[1][ignore_null]" value="true"/>
	<input type="hidden" name="meta_query[1][key]" value="load_capacity_kg"/>
	<p><input type="text" name="meta_query[1][value]" value=""/></p>

	<button>Search</button>
	*/
	
	/* ####################################################################### */
	/* Class properties */
	/* ----------------------------------------------------------------------- */
	
	public $schema = array();
	public $el_counter = 0;
	public $meta_counter = 0;
	public $cat_counter = 0;

	/* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */

	/* ####################################################################### */
	/* Constructor */
	/* ----------------------------------------------------------------------- */
	public function __construct(){
		
	}
	
	/* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */
	
	/* Scripts list --------------------------------------------- */
	/* queries types
		['important': run and save as new ]
		['diff': check on list and run is unique as new ]
		['query_id': run as selected on list]
	*/

	public function render_search_filter_form( $schema ){
		
		$this -> schema = $schema;
		
		/* SERCH FILTER FORM TEMPLATE */
		?>

		<form method="POST">
			
			<h4>Filter AND search</h4>
			
			<?php foreach ( $schema as $key => $value ) { ?>
				
				<div style="float:left; margin-right:10px">

					<div><?php echo $this -> render_label( $schema ); ?></div>
					<div><?php echo $this -> render_field( $schema ); ?></div>

				</div>

			<?php } ?>

			<button>filter</button>

		</form>

		<?php
	}

	public function render_label( $schema ){
		
		$output = '';
		$label = $schema[$this -> el_counter]['label'];
		$output = '<label>'.$label.'</label>';
		return $output;

	}


	public function render_field( $schema ){

		$output = '';
		$format = $schema[$this -> el_counter]['format'];

		
		$output .= $this -> create_tech_inputs( $schema );
		
		
		if($format == 'select'){
			$output .= '<select name="'. $this -> create_node_name( $schema ) .'">';
				$output .= $this -> render_options( $schema );
			$output .= '</select>';
		}
		if($format == 'text'){
			$output .= '<input type="text" name="'. $this -> create_node_name( $schema ) .'">';
		}

		$this -> el_counter ++;
		return $output;

	}

	public function create_tech_inputs( $schema ){

		$output = '';
		if($schema[$this -> el_counter]['type']=='meta_query'){
			$input =  'meta_query['.$this -> meta_counter.'][key]';
			$output .= '<input type="hidden" name="'.$input.'" value="'.$schema[$this -> el_counter]['key'].'">';
		}
		if($schema[$this -> el_counter]['type']=='tax_query'){

			$input =  'tax_query['.$this -> cat_counter.'][taxonomy]';
			$output .= '<input type="hidden" name="'.$input.'" value="'.$schema[$this -> el_counter]['tax_name'].'">';
			$input =  'tax_query['.$this -> cat_counter.'][field]';
			$output .= '<input type="hidden" name="'.$input.'" value="'.$schema[$this -> el_counter]['field'].'">';
		}

		if($schema[$this -> el_counter]['ignore_null']==true){
			$input =  $schema[$this -> el_counter]['type'].'['.$this -> meta_counter.'][ignore_null]';
			$output .= '<input type="hidden" name="'.$input.'" value="true">';
		}
		return $output;

	}

	public function create_node_name( $schema ){

		if($schema[$this -> el_counter]['type']=='meta_query'){
			$node_name =  'meta_query['.$this -> meta_counter.'][value]';
			$this -> meta_counter ++;
		}

		if($schema[$this -> el_counter]['type']=='tax_query'){
			
			$node_name =  'tax_query['.$this -> cat_counter.'][terms]';
			$this -> cat_counter ++;
		}

		return $node_name;
		
	} 

	public function render_options( $schema ){

		$output = '';
		if($schema[$this -> el_counter]['ignore_null']==true){
			$output .= '<option value="null">Empty</option>';
		}

		if($schema[$this -> el_counter]['type']=='tax_query'){
			$args= array();
			$terms = get_terms( $schema[$this -> el_counter]['tax_name'], $args );

			foreach ($terms as $term) {
				
				$output .= '<option value="'. $term->term_id .'">'.$term->name.'</option>';
			}
		}

		if($schema[$this -> el_counter]['dictionary']){
			foreach ( $schema[$this -> el_counter]['dictionary'] as $key => $value ) {
				$output .= '<option value="'. $value .'">'. $value .'</option>';
			}
		}
		return $output;
	}

}

