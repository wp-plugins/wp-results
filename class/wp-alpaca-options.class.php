<?php
/*
Class Author: dadmor
Class Author URI: https://pl.linkedin.com/pub/grzegorz-durtan/11/b74/296
Class Copyright: BlockBox Sp. z o.o.
Class Version: 0.1
License: GPLv2
*/
class wp_alpaca_options
{   
    /**
     * paths to class assets and js libraries.
     *
     * @since 0.1
     * @access public
     * @var array Set arguments paths: base, scripts, styles, schemas
     **/
    public $paths;

    /**
     * Construct alpaca form
     *
     * @param array $paths Defined paths to class assets and js libraries. 
     * @return void
     **/
    public function __construct($paths = false)
    {      
        if($paths == false){
            $paths = array(
                'base' => plugins_url().plugin_basename( __DIR__ ),
                'scripts' => '',
                'styles' => '',
                'schemas' => ''
            );
        }
        $this -> paths = $paths;

        wp_register_script( 'alpaca-core', plugins_url( $this -> paths['scripts'] . 'alpaca-core.min.js', dirname(__FILE__) ) , array('jquery'));
        //wp_register_script( 'alpaca-js', plugins_url( $this->path . 'alpaca.min.js', dirname(__FILE__) ) , array('jquery'));
        
        wp_register_script( 'alpaca-wp-form', plugins_url( $this -> paths['scripts'] . 'alpaca-wp-form.js', dirname(__FILE__) ), array('alpaca-core'));
        wp_enqueue_script( 'alpaca-wp-form' );
       
    }
     
    /**
     * Render alpaca form
     *
     * @return void
     **/
    public function render_form($args = array()) 
    {

        /*  $args_schema = array(
            'render' => array(
                'type'=> null // [wp_widget, wp_postmeta] 
                'render_handler' => null,
                'id' => null,
                'tech_data_id' => null
            ),            
            'paths' =>  array(
                'base' => null,
                'scripts' => null,
                'styles' => null,
                'schemas' => null,
            ),
            'name' => null,
            'style' => 'alpaca-wpadmin',

            'form_schema' => array('storage_method'=>'file','path'=>'js/'),
            'form_options' => array('storage_method'=>'file','path'=>'js/'),
            'run' => 'init_widgets_methods',
            'data' => array(
                'storage_method'=>'file', [file,wp_postmeta,wp_options,wp_widget]
                'path'=>'js/',
                'base' => null
            ),
            'save' => array(
                'save_method'=>'file', [file,wp_postmeta,wp_options,wp_widget] 
                'path'=>'js/'
            ),
        );*/


        /* default style */
        if($args['style'] == NULL){
            $args['style'] = 'alpaca-wpadmin';
        }
        /* set run callback */
        if($args['render']['type'] == 'wp_widget'){
            $args['run'] = 'init_widgets_methods';
        }
        /* ------------- */



        wp_register_style( 'alpaca-admin-style', plugins_url( $this->paths['styles'] . $args['style'] .'.css', dirname(__FILE__) ));
        wp_enqueue_style( 'alpaca-admin-style' );

        wp_localize_script( 'alpaca-wp-form', 'ajax_object',
        
        array( 
            'render' =>  $args['render'],             
            'paths' =>  $this -> paths,
            'name' => $args['name'],
            'form_schema' => $this -> get_schema( $args['schema'] ),
            'form_options' => $this -> get_schema( $args['options'] ),
            'data' => array( 'base' => $this -> get_data( $args )),
            'run' => $args['run']
            ) 
        );
        if($args['render']['type'] == 'wp_metabox'){
            //echo 'render form instance '.$args['name'].'<br>';
            $output = '<div id="'. $args['name'] . '"></div>';  
            $output .= '<input type="hidden" id="alpaca-data-'. $args['name'] . '" name="alpaca-data-'. $args['name'] . '">'; 
            return $output; 
        }
    }

    public function get_schema($method){

        if($method['storage_method'] == 'file'){
            $output = array('method'=>'file');
        }
        return $output;
    }

    /**
     * Get data from database to display into form
     *
     * @return URL string 
     **/
    public function get_data($args , $name){
        
        if($args['save']['save_method'] == 'wp_postmeta'){
            global $post;
            $output = get_post_meta($post -> ID, '_alpaca-data-'.$args['name'], true);
            if($output == ''){
                $output = '%7B%7D';
            }
            return $output;
        }
    }

    public function add_widget_tech_data_field( $instance, $id,$name ){
        
        $tech_widget_data = ! empty( $instance['tech_widget_data'] ) ? $instance['tech_widget_data'] : __( '%7B%7D', 'text_domain' );
        ?>
            <div class="widget_marker" data-guardian="true"></div>
            <input type="hidden" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo esc_attr( $tech_widget_data ); ?>" >
        <?php 

    } 
}

/* API */
function wp_result_save_as_metabox(){
    function wp_result_metabox_save( $post_id ) {
        // Check if our nonce is set.
        var_dump($_POST);
        if ( ! isset( $_POST['myplugin_meta_box_nonce'] ) ) {
            return;
        }
    }
    add_action( 'save_post', 'wp_result_metabox_save' );
}