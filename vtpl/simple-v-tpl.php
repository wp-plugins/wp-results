<?php
/**
 * A custom -not from the theme- template
 *
 * @package WordPress
 * @subpackage Virtual_Template
 */
?>

<?php get_header(); ?>
<div id="main-content" class="main-content">	
	<div id="primary" class="content-area">
		<div id="content" class="site-content grid grid-pad" role="main">

			<?php while ( have_posts() ) : the_post(); $theID = $post->ID; ?>

			    <?php $VT->get_template_part( get_theme_mod( 'content_part_'.$VT -> tfname ) ); ?>

			<?php endwhile; ?>

		</div><!-- #content -->
	</div><!-- #primary -->
</div>
<?php get_footer(); ?>

</body>
</html>


