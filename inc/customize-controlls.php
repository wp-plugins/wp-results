<?php

//if ( is_admin() ) {
       new theme_customizer();
//}
   


class theme_customizer
{
    
    private $gfxfiles = array();
    
    public function __construct()
    {
        add_action ('admin_menu', array(&$this, 'customizer_admin'));
        add_action( 'customize_register', array(&$this, 'customize_manager_demo' ));

        $this -> gfxfiles['none'] = 'none';

        if ($handle = opendir(GFX_DIR)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $this -> gfxfiles[$entry] = $entry;
                }
            }
            closedir($handle);
        }
       
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

        /*$wp_manager->add_section( 'customiser_demo_section', array(
            'title'          => 'Theme Grid GFX',
            'priority'       => 35,
        ) );

        $wp_manager->add_setting( 'wraper_footer_menu_style', array(
            'default'        => '',
        ) );
        $wp_manager->add_control( 'wraper_footer_menu_style', array(
            'label'   => 'Select wraper-footer_menu bg style',
            'section' => 'customiser_demo_section',
            'type'    => 'text',
            'priority' => 8
        ) );*/


        $wp_manager->add_section( 'customiser_google_fonts', array(
            'title'          => 'WP Results Composer',
            'priority'       => 35,
        ) );
         // Select wraper-header control
        $wp_manager->add_setting( 'font_name', array(
            'default'        => 'none',
        ) );
        $wp_manager->add_control( 'font_name', array(
            'label'   => 'Content template part',
            'section' => 'customiser_google_fonts',
            'type'    => 'select',

            'choices' => array(
                'none' => 'Oryginal content',
                'template-part-1' => 'Template 1',
                'template-part-2' => 'Template 2',
                'template-part-3' => 'Template 3',
                
                ),
            'priority' => 1
        ) );

    }

}





?>