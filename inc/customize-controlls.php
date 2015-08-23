<?php
function my_preview_js() {
    wp_enqueue_script( 'custom-grid-displayer', plugins_url().'/wp-results/js/custom-grid-displayer.js', array( 'customize-preview',  'jquery'  ) ,true);
        //wp_enqueue_script('customizer-slider', plugins_url('js/slider-ui.js', __FILE__), array('jquery-ui-slider'), '1.0', true);
        //wp_register_style('customizer-slider-styles', plugins_url('css/slider-ui.min.css', __FILE__));  
        //wp_enqueue_style('customizer-slider-styles');

}
add_action( 'customize_preview_init', 'my_preview_js' );




//if ( is_admin() ) {
$TC = new theme_customizer();
//}
  
class theme_customizer
{
   
    public function __construct()
    {
        add_action( 'customize_register', array(&$this, 'customize_manager_demo' ));
    }

    /**
     * Add the Customize link to the admin menu
     * @return void
     */
    public function customizer_admin() {
        add_theme_page( 'Customize', 'Customize', 'edit_theme_options', 'customize.php' );
    }

    /**
     * Customizer manager demo
     * @param  WP_Customizer_Manager $wp_manager
     * @return void
     */
    public function customize_manager_demo( $wp_manager )
    {
        $this->demo_section( $wp_manager );
    }


    public function demo_section( $wp_manager )
    {

        $wp_manager->add_section( 'template_parts_section', array(
            'title'          => 'WP Results Composer',
            'priority'       => 35,
            //'panel' => 'template_parts_panel',
        ) );

        /* Template part Files */
        $template_parts_array = array(
            'index',
            'home',
            'category',
            'archive',
            'tag',
            'author',
            'date',
            'search',
            'page'
        );

        global $GRIDS;
        $grids_list = array('original' => 'Original template');
        foreach ($GRIDS->options_group as $value) {
            $grids_list[$value] = $value;
        }

        foreach ($template_parts_array as $template_part) {


            $wp_manager->add_setting( 'template_'.$template_part, array(
                'default'        => 'none',
                //'sanitize_callback' => 'example_sanitize_text',
            ) );
            $wp_manager->add_control( 'template_'.$template_part, array(
                'input_attrs' => array('hierarchy_name' => $template_part),
                'label'   => 'Templatelate ('.$template_part.')',
                'section' => 'template_parts_section',
                'type'    => 'select',              
                'choices' => $grids_list,
                'priority' => 1
            ) );
           
            # code...
            // Select wraper-header control
            $wp_manager->add_setting( 'content_part_'.$template_part, array(
                'default'        => 'none',
                //'sanitize_callback' => 'example_sanitize_text',
            ) );
            $wp_manager->add_control( 'content_part_'.$template_part, array(
                'input_attrs' => array('hierarchy_name' => $template_part),
                'label'   => 'Content template part ('.$template_part.')',
                'section' => 'template_parts_section',
                'type'    => 'select',              
                'choices' => array(
                    'none' => 'Original content',
                    'tp-dynamic-table-ac' => 'Dynamic table (Admin columns)',
                    'tp-dynamic-cart-table-ac' => 'Dynamic table with CART (Admin columns)',
                    /*'template-part-1' => 'Template 1',
                    'template-part-2' => 'Template 2',
                    'template-part-3' => 'Template 3',     */               
                    ),
                'priority' => 1
            ) );
        }
    }
}

function title_tagline_control_filter( $active, $control ) {

    if ( $control->input_attrs['hierarchy_name'] === 'index'  ) {
        return is_front_page();
    }
    if ( $control->input_attrs['hierarchy_name'] === 'home'  ) {
        return is_home();
    }
    if ( $control->input_attrs['hierarchy_name'] === 'category'  ) {
        return is_category();
    }
    if ( $control->input_attrs['hierarchy_name'] === 'archive'  ) {
        return is_archive();
    }
    if ( $control->input_attrs['hierarchy_name'] === 'tag'  ) {
        return is_tag();
    }
    if ( $control->input_attrs['hierarchy_name'] === 'author'  ) {
        return is_author();
    }
    if ( $control->input_attrs['hierarchy_name'] === 'date'  ) {
        return is_date();
    }
    if ( $control->input_attrs['hierarchy_name'] === 'search'  ) {
        return is_search();
    }
    return true;

}
add_filter( 'customize_control_active', 'title_tagline_control_filter', 10, 2 );
