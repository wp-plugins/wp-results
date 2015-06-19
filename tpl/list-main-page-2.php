<?php
/* template: List Main Page */

?>

	
	<div id="content" class="clearfix">

	<!-- List body -->
	<?php foreach ($this->list_data as $key => $list_row) { ?>
		
		<article class="post">
			<div class="blog-content">
			
				<header class="entry-header">
					<h2 class="entry-title">
					<a href="<?php echo $list_row['title']['url']; ?>">
						<?php echo $list_row['title']['value'];?>
					</a>
					</h2>
				</header>

				<div class="entry-meta-bar clearfix">
					<div class="entry-meta clearfix">				
					<?php foreach ($list_row as $field => $entry) { ?>
							<?php if($field=='author') echo $entry['value']; ?>
							<?php if($field=='date') echo $entry['value']; ?>
							<?php if($field=='categories') echo $entry['value']; ?>
					<?php } ?>
					</div>
				</div>


				<div class="entry-content clearfix">
					<?php $decorator_start = '<figure class="post-featured-image">'; ?>
					<?php $decorator_end = '</a></figure>'; ?>
					<?php if($list_row['column-meta']['field_type']=="image"){ 
						echo $decorator_start.'<a href="'.$list_row['title']['url'].'">';
						echo $list_row['column-meta']['value'] . $decorator_end; 
					} ?>
					<?php if($list_row['column-meta-1']['field_type']=="image"){ 
						echo $decorator_start.'<a href="'.$list_row['title']['url'].'">';
						echo $list_row['column-meta-1']['value'] . $decorator_end; 
					} ?>
					<?php if($list_row['column-meta-2']['field_type']=="image"){ 
						echo $decorator_start.'<a href="'.$list_row['title']['url'].'">';
						echo $list_row['column-meta-2']['value'] . $decorator_end; 
					} ?>
					<?php if($list_row['column-meta-3']['field_type']=="image"){ 
						echo $decorator_start.'<a href="'.$list_row['title']['url'].'">';
						echo $list_row['column-meta-3']['value'] . $decorator_end; 
					} ?>
					<?php if($list_row['column-meta-4']['field_type']=="image"){ 
						echo $decorator_start.'<a href="'.$list_row['title']['url'].'">';
						echo $list_row['column-meta-4']['value'] . $decorator_end; 
					} ?>
					
					<p>
						<?php echo $list_row['column-excerpt']['value']; ?>
						<?php echo $list_row['column-content']['value']; ?>
					</p>

					<p>
						<?php foreach ($list_row as $field => $entry) { ?>
								<?php if(($field == 'column-meta')&&($entry['field_type'] != 'image')){ ?>
									<?php echo $entry['label'];?> : <?php echo $entry['value'];?> <br>
								<?php } ?>	
								<?php if(($field == 'column-meta-1')&&($entry['field_type'] != 'image')){ ?>
									<?php echo $entry['label'];?> : <?php echo $entry['value'];?> <br>
								<?php } ?>	
								<?php if(($field == 'column-meta-2')&&($entry['field_type'] != 'image')){ ?>
									<?php echo $entry['label'];?> : <?php echo $entry['value'];?> <br>
								<?php } ?>	
								<?php if(($field == 'column-meta-3')&&($entry['field_type'] != 'image')){ ?>
									<?php echo $entry['label'];?> : <?php echo $entry['value'];?> <br>
								<?php } ?>	
								<?php if(($field == 'column-meta-4')&&($entry['field_type'] != 'image')){ ?>
									<?php echo $entry['label'];?> : <?php echo $entry['value'];?> <br>
								<?php } ?>						
						<?php } ?>
					</p>
					
					<div class="tags">
						<?php echo $list_row['tags']['value']; ?>
					</div>
				
					<div class="readmore-wrap">
						<a class="read-more" title="Kontakt" href="<?php echo $list_row['title']['url']; ?>">
							<?php _e( 'Czytaj więcej', 'text_domain' ); ?>
						</a>
					</div>
					
					

					
				</div>
	
			</div>
		</article>

	<?php } ?>
	</div>
