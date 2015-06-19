<?php
/*
Class Author: brasofilo
Class Author URI: http://wordpress.stackexchange.com/users/12615/brasofilo
Class Version: 2012.12.11
License: GPLv2
*/

$VT = new VirtualTemplateForCPT_class( 'movies' );

class VirtualTemplateForCPT_class
{   
    private $pt;
    private $url;
    private $path;

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
     * @param string $template
     * @return string
     **/
    public function custom_template( $template ) 
    {
        $post_types = array( $this->pt );
        $theme = wp_get_theme();
		$template = $this->path . 'vtpl/simple-v-tpl.php';
		/*
		if ( is_post_type_archive( $post_types ) )
            $template = $this->path . '/single-virtual-cpt.php';

        if ( is_singular( $post_types ) )
            $template = $this->path . '/single-virtual-cpt.php';
		*/
        return $template;
    }

    public function get_template_part( $file )
    {
    	if( $file == 'none' )
    	{
    		get_template_part( 'content', get_post_format() );
    	}
    	else
    	{

    		require( $this->path . '/tpl/'.$file.'.php' );
   		
    	}
    }

}