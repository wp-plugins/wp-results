<?php
/* template: List Table */
?>
<div class="wp_list">
	
	<!-- List header -->
	<div class="wp_list_header">

		<?php foreach (current( (Array)$this->list_data) as $key => $value) { ?>
			
			<div class="wp_list_header_cell" style="<?php echo $style; ?>"><?php echo $value['label']; ?></div>

		<?php } ?>

	</div>

	<!-- List body -->
	<?php foreach ($this->list_data as $key => $list_row) { ?>
		
		<div class="wp_list_row <?php echo ($c++%2==1)?"color_row":NULL; ?>">

			<?php foreach ($list_row as $label => $entry) { ?>
				
				<div class="wp_list_cell"><?php echo $entry['value']; ?></div>
			
			<?php } ?>

		</div>

	<?php } ?>

</div>