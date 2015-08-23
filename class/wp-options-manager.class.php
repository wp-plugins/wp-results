<?php
/* https://github.com/dadmor/wp-options-manager-class */
class wp_options_manager {
	
	/* ####################################################################### */
	/* Class properties */
	/* ----------------------------------------------------------------------- */
	
	public $options_group = array();
	public $scripts_prefix = 'uigen-om-';

	/* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */

	/* ####################################################################### */
	/* Constructor */
	/* ----------------------------------------------------------------------- */
	public function __construct($scripts_prefix = 'uigen-om-' ){
			
		// Chceck prefix
		if($scripts_prefix != $this->scripts_prefix){
			$this->scripts_prefix = $scripts_prefix;
		}
	
	}
	/* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */

	/* ----------Add options Methods ----------*/
	
	public function add_option($option_name, $value, $options_group_name, $autoload = 'no' , $encode = 'no'){

		$options_group = get_option( $this->scripts_prefix.'group-'.$options_group_name );
		if($options_group == false){
			add_option( $this->scripts_prefix.'group-'.$options_group_name, array(), '', 'yes' );
			$options_group = array();
		}
		$this->options_group = $options_group;
		
		if($encode='yes'){
			$value = json_decode( urldecode ( $value ), true) ;	
		}

		$output = update_option( $this->scripts_prefix.$option_name, $value, '', $autoload );
		
		// update group
		$this->options_group[] =  $option_name;
		$this->options_group = array_unique ($this->options_group);
		update_option( $this->scripts_prefix.'group-'.$options_group_name, $this->options_group, 'yes');

		return $this->options_group;
	}
	public function add_option_ajax(){
		
		check_ajax_referer( $_SERVER['SERVER_NAME'] , 'security' );
		wp_send_json (array(
			'name' => $_POST['name'],
			'group' => $this -> add_option( $_POST['name'], $_POST['value'], $_POST['group_name'], $_POST['autoload'], $_POST['encode'] ),
		));
	
	}	
	/* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */
	
	/* ----------Get options Methods ----------*/
	
	public function get_option($option_name){
	
		return get_option( $this->scripts_prefix.$option_name );
	
	}
	public function get_option_ajax($option_name){
		
		check_ajax_referer( $_SERVER['SERVER_NAME'] , 'security' );
		wp_send_json (array(
			'name' => $_POST['name'],
			'value' => $this->get_option( $_POST['name'] )
		));
	
	}
	/* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */	

	public function del_option( $option_name , $options_group_name){

		delete_option( $this->scripts_prefix . $option_name);

		foreach ($this->options_group as $key => $value) {
			if($value == $option_name){
				unset($this->options_group[$key]);
			}
		}


		update_option( $this->scripts_prefix.'group-'.$options_group_name, $this->options_group, 'yes');
		return $this->options_group;

	}

	public function del_option_ajax($option_name, $options_group_name){
		
		check_ajax_referer( $_SERVER['SERVER_NAME'] , 'security' );

		wp_send_json (array(
			'name' => $_POST['name'],
			'group' => 	$this->del_option( $_POST['name'], $_POST['group_name'] )
		));
	
	}
	/* ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */

	/* ----------List Methods ----------*/

	public function list_group( $options_group_name ){
	
		return get_option( $this->scripts_prefix.'group-'.$options_group_name );
	
	}
	public function list_group_ajax(){

		check_ajax_referer( $_SERVER['SERVER_NAME'] , 'security' );
		wp_send_json (array(
			'group_name' =>  $_POST['group_name'],
			'options_list' =>  $this->list_group( $_POST['group_name'] )
		));

    }

    public function get_all_of_group($options_group_name){
	
		$group = get_option( $this->scripts_prefix.'group-'.$options_group_name );
		$all_group = array();
		
		foreach ($group as $key => $value) {
			$all_group[$value] = $this -> get_option($value);
		}

		return $all_group;
	}

	/* ----------Register Ajax methods ----------*/
	public function register_ajax_methods(){

		add_action('wp_ajax_list_group', array($this, 'list_group_ajax'));
		add_action('wp_ajax_get_option', array($this, 'get_option_ajax'));
		add_action('wp_ajax_add_option', array($this, 'add_option_ajax'));
		add_action('wp_ajax_del_option', array($this, 'del_option_ajax'));
	}
	
}