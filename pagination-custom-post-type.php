<?php 
?>
<section class="elementor-section project_short">
<div class="elementor-container">
<div class="elementor-column">
<div class="elementor-widget-wrap">

<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$data= new WP_Query(array(
    'post_type'=>'portfolio',
    'posts_per_page' => 8,
    'paged' => $paged,
));

if($data->have_posts()) :
    while($data->have_posts())  : $data->the_post();

?>
        <!-- Start Loop -->
            <div class="inner-details">
                <?php the_post_thumbnail(); ?>
                <h2 class="pr_title"> <?php the_title();?></h2>
                <div class="pr-content"> <?php the_content();?></div> 
                <p class="moreless-button">read more…</p>   
            </div>
            <!-- End loop -->
<?php

    endwhile;
?>
</div>
</div>
</div>
</section>

<?php

    $total_pages = $data->max_num_pages;

    if ($total_pages > 1){

        $current_page = max(1, get_query_var('paged'));
        ?>
<div class="project-pagination">
    <?php
        echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '/page/%#%',
            'current' => $current_page,
            'total' => $total_pages,
            'prev_text'    => __('<i class="fa-regular fa-chevron-left"></i>'),
            'next_text'    => __('<i class="fa-regular fa-chevron-right"></i>'),
        ));
    }
    ?> 
</div>   
<?php else :?>
<h2><?php _e('404 Error Not Found', ''); ?></h2>
<?php endif; ?>
<?php wp_reset_postdata();?>