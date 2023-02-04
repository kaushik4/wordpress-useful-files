<?php
$categories = get_categories( array(
    'orderby' => 'name',
    'order'   => 'ASC'
) );
?>
<div class="hentry page publish post-1 odd author-buyandsellyour post">
   <section class="entry-content">
     <p><?php //echo __( 'Category and tag', 'tevolution' ); ?> </p>
      <form name="submit_form" action="" method="post" enctype="multipart/form-data">
		<div class="accordion" id="post-listing">
            <div class="accordion-navigation step-wrapper step-post current">				
			   <div id="post" class="step-post content clearfix current" style="display: block;">
					<div id="submit_form_custom_fields" class="submit_form_custom_fields category-filter-form">
					<h2>Featured<br> <span style="font-weight:700;">blog posts</span></h2>

					<div class="elementor-element elementor-element-beb592c elementor-widget elementor-widget-raven-divider" data-id="beb592c" data-element_type="widget" data-widget_type="raven-divider.default">
						<div class="elementor-widget-container">
						<div class="raven-widget-wrapper">
						<div class="raven-divider">
						<span class="raven-divider-line raven-divider-solid"></span>
						</div>
						</div>
						</div>
						</div>


						 <div class="submit_form_fields">
							<div class="form_row">
							   <select class="select-class" name="lwa_post_category" id="post-cat">
									<option value="">--- Select Category ---</option>
									<?php
									foreach($categories as $data)	 	
									{
										$term_id = $data->term_id;
										$name = $data->name;	
										echo "<option value='".$term_id."'>".$name."</option>";
									}
									?>
							   </select>
							</div>
							<div class="form_row">
							   <select class="select-class"name="lwa_post_tags" id="post-tag">
								<option value="">--- Select Tag ---</option>
								<?php
									$tags = get_tags(array(
										'hide_empty' => false
									));
									foreach($tags as $tag_data)	 										
									{
										$slug = $tag_data->slug;
										$tag_name = $tag_data->name;
										echo "<option value='".$slug."'>".$tag_name."</option>";
									}
								?>
								</select>
							</div>
							
						</div>
					</div>
				<div id="loader-img">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/loading-buffering.gif">
				</div>
				<div id="posts_lists">
					
					<?php					
					$arg = array(
						'orderby' => 'post_date',
						'order' => 'DESC',
						'post_type' => 'post',
						'post_status' => 'publish',
						'suppress_filters' => true								
					);
					$loop = new WP_Query( $arg );	
					$html = "";
					while($loop->have_posts()) : $loop->the_post();					
						$post_id = get_the_ID();
					?>
						<div class="home-blog-single">
							
							<?php
							$post_tags = get_the_tags();
							
							
							if (has_post_thumbnail( $post_id ) ): 
								$image = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'single-post-thumbnail'); 
							?>
								<img src="<?php echo $image[0]; ?>" class="home-blog-single-img"/>
							<?php
							endif; 
							?>
							<div class="home-blog-single-content">
								<h3><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
								<div class="home-blog-tags">
									<p><?php echo get_the_date(); ?></p>
									<p class="home-blog-tags-inner">
									<?php
										$dat = array();
										foreach($post_tags as $data)
										{
											$url = site_url('/tag/'.$data->slug);	
											$dat[] = "<a href='$url'>#".$data->name."</a>";
										}
										echo implode(", " , $dat);
								
									?>
									</p>
								</div>
								<p class="blog-listing-content"><?php echo substr(get_the_excerpt(),0,275 ) ?></p>
								<p><a href='<?php echo get_permalink(); ?>' class="readmore">Read More</a></p>
							</div>
						</div>
					<?php 				   
						endwhile;
					?>
				</div>
				</div>
				</div>
		</div>
	</form>
   </section>
</div>


<script>
var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";	
jQuery("#post-cat, #post-tag").on("change", function(){
	
	var categoryId = jQuery("#post-cat").val();	
	var tagSlug = jQuery("#post-tag").val();	
	var data = {
		'action' 	: 'change_posts_based_on_category',
		'cat_id' 	: categoryId,
		'tag_slug' 	: tagSlug,
	};
	jQuery.ajax({
		type : "POST",
		url : ajaxurl,
		data : data,
		datatype : 'html',
		beforeSend : function() {
			  jQuery("#loader-img").show();
			  jQuery("#posts_lists").hide();
		},		
		success : function(response) {
			 jQuery("#loader-img").hide();
			 jQuery("#posts_lists").html(response);
			   jQuery("#posts_lists").show();
			 // var json = $.parseJSON(response); 
			  // jQuery(json).each(function(index, item){
				 // jQuery("#posts_lists").html(index);
			  // })
			 
			
		}
	});
	
	//alert(categoryId);
	
})
</script>