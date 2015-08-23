# wp-alpaca-options-class

<img src="https://github.com/dadmor/wp-alpaca-options-class/blob/master/header-image.png"/>

Main task of this class is integrate WordPress with powerfull alpacajs dynamic forms library. 
The first proposal support is create options for widgets and metaboxes. Class realize all process automaticly. In feauter users can build forms with creator and pin it to WordPress backend elements.
 
# ALPACAJS FORM DEV ARCH

work about it still in progress....

<img src="https://github.com/dadmor/wp-alpaca-options-class/blob/master/github-assets/wp_alpaca_form_model.png">


/* Init options class */

/* CLASS AUTOLOADER */
/* Init alpaca options pack */
<pre>
/* CLASS AUTOLOADER */
function class_loader($class){
		if(!class_exists($class)) {
			include_once dirname(__FILE__)."/classes/$class.class.php";
		}
}

/* Init alpaca options pack */
class_loader('wp-alpaca-options');
</pre>



<pre>
$name = 'YOUR_ALPACAJS_FORM_UNIQUE_NAME';	

$init_paths = array(
	'base' => plugin_dir_url( __FILE__ ),
	'scripts' => 'js/',
	'styles' => 'css/',
	'schemas' => 'js/'
);	
$ALPC_FRM_RULEZ = new wp_alpaca_options($init_paths); 
</pre>


/* build form - on widget, postbox or plugin space: */

<pre>
$form_args = array(
		'name' => 'FRM_RULEZ',
		'render' => array('type' => 'wp_metabox' ),
		'save' => array('save_method' => 'wp_postmeta' ),
		'run' => 'init_post_meta_methods'			
	);	
	$ALPC_FRM_RULEZ -> render_form($form_args);
</pre>




# ALPACAJS UX FORM EDITOR

<img src="https://github.com/dadmor/alpacajs-ux-form-editor/blob/master/github-assets/editor1.png">

checkout this: [LINK](https://github.com/dadmor/alpacajs-ux-form-editor) 






/* CLASS AUTOLOADER */
function class_loader($class){
		if(!class_exists($class)) {
			include_once dirname(__FILE__)."/classes/$class.class.php";
		}
}

/* Init alpaca options pack */
class_loader('wp-alpaca-options');

	$name = 'YOUR_ALPACAJS_FORM_UNIQUE_NAME';	

	$init_paths = array(
		'base' => plugin_dir_url( __FILE__ ),
		'scripts' => 'js/',
		'styles' => 'css/',
		'schemas' => 'js/'
	);	
	$ALPC_FRM_RULEZ = new wp_alpaca_options($init_paths); 
	$form_args = array(
		'name' => 'FRM_RULEZ',
		'render' => array('type' => 'meta_box' ),
		'save' => array('save_method' => 'post_meta' ),
		'run' => 'init_post_meta_methods'			
	);	
	$ALPC_FRM_RULEZ -> render_form($form_args);
}