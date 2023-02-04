<?php

// Include Jupiter X.
require_once( get_template_directory() . '/lib/init.php' );

/**
 * Enqueue assets.
 *
 * Add custom style and script.
 */
jupiterx_add_smart_action( 'wp_enqueue_scripts', 'jupiterx_child_enqueue_scripts', 8 );

function jupiterx_child_enqueue_scripts() {

	// Add custom script.
	wp_enqueue_style(
		'jupiterx-child',
		get_stylesheet_directory_uri() . '/assets/css/style.css'
	);

	// Add custom script.
	wp_enqueue_script(
		'jupiterx-child',
		get_stylesheet_directory_uri() . '/assets/js/script.js',
		[ 'jquery' ],
		false,
		true
	);
}

/**
 * Example 1
 *
 * Modify markups and attributes.
 */
// jupiterx_add_smart_action( 'wp', 'jupiterx_setup_document' );

function jupiterx_setup_document() {

	// Header
	jupiterx_add_attribute( 'jupiterx_header', 'class', 'jupiterx-child-header' );

	// Breadcrumb
	jupiterx_remove_action( 'jupiterx_breadcrumb' );

	// Post image
	jupiterx_modify_action_hook( 'jupiterx_post_image', 'jupiterx_post_header_before_markup' );

	// Post read more
	jupiterx_replace_attribute( 'jupiterx_post_more_link', 'class' , 'btn-outline-secondary', 'btn-danger' );

	// Post related
	jupiterx_modify_action_priority( 'jupiterx_post_related', 11 );

}

/**
 * Example 2
 *
 * Modify the sub footer credit text.
 */
// jupiterx_add_smart_action( 'jupiterx_subfooter_credit_text_output', 'jupiterx_child_modify_subfooter_credit' );

function jupiterx_child_modify_subfooter_credit() { ?>

	<a href="https//jupiterx.com" target="_blank">Jupiter X Child</a> theme for <a href="http://wordpress.org" target="_blank">WordPress</a>

<?php }


add_action('init', 'category_list');
function category_list(){
	add_shortcode('show_tag_category', 'show_tag_category_callback');
}

function show_tag_category_callback(){
	$a = get_stylesheet_directory().'/custom-form/vlog.php';
	if(is_file($a)){
		include_once($a);
	}else{
		return 'errer';
	}
	//return ob_get_clean(); 	
}

add_action('wp_ajax_change_posts_based_on_category','change_posts_based_on_category_callback');
add_action('wp_ajax_nopriv_change_posts_based_on_category','change_posts_based_on_category_callback');

function change_posts_based_on_category_callback(){
	$cat = isset($_POST['cat_id']) && !empty($_POST['cat_id']) ? $_POST['cat_id'] : "";
	$tag = isset($_POST['tag_slug']) && !empty($_POST['tag_slug']) ? $_POST['tag_slug'] : "";
	if(!empty($cat) && !empty($tag)){
		$arg = array(
			'cat' => $cat,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'post_type' => 'post',
			'post_status' => 'publish',
			'suppress_filters' => true,
			'tax_query'      => array(
				'relation' => 'AND',
				array(
					'taxonomy'  => 'post_tag',
					'field'     => 'slug',
					'terms'     => $tag
				)
			)		
		);
	}else if(!empty($cat) && empty($tag)){
		$arg = array(
			'cat' => $cat,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'post_type' => 'post',
			'post_status' => 'publish',
			'suppress_filters' => true						
		);
	}else if(empty($cat) && !empty($tag)){
		$arg = array(
			'orderby' => 'post_date',
			'order' => 'DESC',
			'post_type' => 'post',
			'post_status' => 'publish',
			'suppress_filters' => true,
			'tax_query'      => array(
				'relation' => 'AND',
				array(
					'taxonomy'  => 'post_tag',
					'field'     => 'slug',
					'terms'     => $tag
				)
			)
		);
		
	}else{
		/*echo "Error ";*/
	}
	
	$data = array();
	$loop = new WP_Query( $arg );	
	$html = "";
	if($loop->have_posts()) : 
		while($loop->have_posts()) : $loop->the_post();
			$post_id = get_the_ID();
			$html .= "<div class='home-blog-single'>";
			$post_tags = get_the_tags();
			$dat = array();
			foreach($post_tags as $data)
			{
				$url = site_url('/tag/'.$data->slug);	
				$dat[] = "<a href='$url'>#".$data->name."</a>";
			}
			
			if (has_post_thumbnail( $post_id ) ): 
				$image = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'single-post-thumbnail'); 
				$html .= '<img class="home-blog-single-img" src="'.$image[0].'" />';
			endif; 
			
			$html .= "<div class='home-blog-single-content'>";			
			$html .= "<h3><a href='	".get_permalink()."'>".get_the_title()."</a></h3>";
			$html .= "<div class='home-blog-tags'>";			
			$html .= "<p>".get_the_date()."</p>";			
			$html .= "<p class='home-blog-tags-inner'>".implode(", " , $dat)."</p>";
			$html .= "</div>";	
			$html .= "<p class='blog-listing-content'>".substr(get_the_excerpt(),0,275 )."</p>";				
			$html .= "<p><a href='".get_permalink()."' class='readmore'>Read More</a></p>";
			$html .= "</div>";
			$html .= "</div>";
		
		endwhile;
		wp_reset_postdata();
	else :
		$html .= "<p class='error-msg-single-blog'>Sorry no blogs found...</p>";
	endif;
		echo $html;	 
	wp_die();
}
add_filter( 'excerpt_more', '__return_empty_string' ); 



add_action('init', 'single_pagination');
function single_pagination(){
	add_shortcode('show_blog_pagination', 'wpb_posts_nav');
}
function wpb_posts_nav(){
    $next_post = get_next_post();
    $prev_post = get_previous_post();
     
    if ( $next_post || $prev_post ) : ?>
     
        <div class="wpb-posts-nav">
            <div class="previous-pag">
                <?php if ( ! empty( $prev_post ) ) : ?>
                    <a href="<?php echo get_permalink( $prev_post ); ?>">
                        <div class="pagination-inner">
                            <div class="wpb-posts-nav__thumbnail wpb-posts-nav__prev">
                                <?php echo get_the_post_thumbnail( $prev_post ); ?>
                            </div>
                        	<div class="pagination-inner-content">
	                            <p><?php _e( 'Older post', 'textdomain' ) ?></p>
	                            <h4><?php echo get_the_title( $prev_post ); ?></h4>
	                        </div>
                        </div>
                    </a>
                <?php endif; ?>
            </div>

            <div class="next-pag">
                <?php if ( ! empty( $next_post ) ) : ?>
                    <a href="<?php echo get_permalink( $next_post ); ?>">
                        <div class="pagination-inner">
                        	<div class="pagination-inner-content">
	                            <p><?php _e( 'Newer post', 'textdomain' ) ?></p>
	                            <h4><?php echo get_the_title( $next_post ); ?></h4>
                        	</div>
                            <div class="wpb-posts-nav__thumbnail wpb-posts-nav__next">
                                <?php echo get_the_post_thumbnail( $next_post ); ?>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            </div>

        </div>
    <?php endif;
}


add_action('wp_head', 'wpb_add_googleanalytics');
function wpb_add_googleanalytics() { ?>
 
<!-- Google tag (gtag.js) --> <script async src="https://www.googletagmanager.com/gtag/js?id=G-7JQYFPGW0L"></script> <script> window.dataLayer = window.dataLayer || []; function gtag()

{dataLayer.push(arguments);}
gtag('js', new Date()); gtag('config', 'G-7JQYFPGW0L'); </script>
 
<?php } ?>