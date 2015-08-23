<?php
/*
Class Author: brasofilo | forked by dadmor
Class Author URI: http://wordpress.stackexchange.com/users/12615/brasofilo
Class Version: 2012.12.11 -> 0.1
License: GPLv2
*/

$VT = new VirtualTemplateForCPT_class( 'movies' );

class VirtualTemplateForCPT_class
{   
    
    private $pt;
    private $url;
    private $path;
    
    public $tfname;
    public $registered_templates = array(); // template names
    public $defined_Template;

    /**
     * Construct 
     *
     * @return void
     **/
    public function __construct( $pt )
    {      



        $this->pt = $pt;
        $this->url = plugins_url( '', __FILE__ );
        $this->path = plugin_dir_path( __DIR__ );
        add_action( 'init', array( $this, 'init_all' ) );

    }

    /**
     * Dispatch general hooks
     *
     * @return void
     **/
    public function init_all() 
    {
        
        add_action( 'wp_enqueue_scripts',   array( $this, 'frontend_enqueue' ) );
        add_filter( 'body_class',           array( $this, 'add_body_class' ) );
        add_filter( 'template_include',     array( $this, 'custom_template' ) );
    }

    /**
     * Use for custom frontend enqueues of scripts and styles
     * http://codex.wordpress.org/Plugin_API/Action_Reference/wp_enqueue_scripts
     *
     * @return void
     **/
    public function frontend_enqueue() 
    {
        global $post;
        if( $this->pt != get_post_type( $post->ID ) )
            return;         
    }

    /**
     * Add custom class to body tag
     * http://codex.wordpress.org/Function_Reference/body_class
     *
     * @param array $classes
     * @return array
     **/
    public function add_body_class( $classes ) 
    {
        global $post;
        if( $this->pt != get_post_type( $post->ID ) )
            return $classes;

        $classes[] = $this->pt . '-body-class';
        return $classes;
    }

    /**
     * Use the plugin template file to display the CPT
     * http://codex.wordpress.org/Conditional_Tags
     *
     * @param string $tpl
     * @return string
     **/
    public function custom_template( $tpl ) 
    {

        global $template;
        $this -> tfname = basename($template, ".php");

        global $GRIDS;
        $this -> defined_Template = get_option('uigen-om-'.get_theme_mod( 'template_'.$this -> tfname ));
       
        //echo 'Tname init:'.$this -> tfname;
        //$post_types = array( $this->pt );
        //$theme = wp_get_theme();

        // Table headers
        if( (get_theme_mod( 'content_part_'.$this -> tfname ) == 'tp-dynamic-table-ac')||
            (get_theme_mod( 'content_part_'.$this -> tfname ) == 'tp-dynamic-cart-table-ac')
         ){
            global $ACOL;
            $ACOL -> get_AC_schema('post');
        }

        // GET CUSTOM TEMPLATE
        if(($this -> defined_Template != false)&&($this -> defined_Template != 'original')){

            $tpl = $this->path . 'vtpl/dynamic-v-tpl.php';
        }


        //var_dump(get_option( 'template_'.$this -> tfname ));
/*        if(
            ($this -> tfname == 'index')||
            ($this -> tfname == 'category')||
            ($this -> tfname == 'tag')||
            ($this -> tfname == 'author')||
            ($this -> tfname == 'date')||
            ($this -> tfname == 'search')
        ){

        }*/
		/*
		if ( is_post_type_archive( $post_types ) )
            $tpl = $this->path . '/single-virtual-cpt.php';

        if ( is_singular( $post_types ) )
            $tpl = $this->path . '/single-virtual-cpt.php';
		*/
        return $tpl;
    }

    public function get_template_part( $file )
    {

        if( ($file == 'none')||($file == false) )
    	{
    		get_template_part( 'content', get_post_format() );
    	}
    	else
    	{

    		require( $this->path . '/tpl/'.$file.'.php' );
   		
    	}
    }

    public function register_template( $name ){

    }

    public function unregister_template( $name ){
        
    }



}