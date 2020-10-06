<?php
/* Template Name: Creating-search-acf-group */
/**
 * Template used for pages.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<?php get_header(); ?>
<style type="text/css">
select, textarea {border-color: inherit;color: #333;}	
#button{display:block;margin:20px auto;padding:10px 30px;background-color:#eee;border:solid #ccc 1px;cursor: pointer;}
#overlay{position: fixed;top: 0;z-index: 99999;width: 100%;height:100%;display: none;background: rgba(0,0,0,0.6);}
.cv-spinner {height: 100%;display: flex;justify-content: center;align-items: center;}
.spinner {width: 40px;height: 40px;border: 4px #ddd solid;border-top: 4px #2e93e6 solid;border-radius: 50%;animation: sp-anime 0.8s infinite linear;}
@keyframes sp-anime { 100% { transform: rotate(360deg); } }
.is-hide{display:none;}
/***pagination css****/
.paginate_links span.page-numbers.current {background: #0057a4; padding: 16px; border-radius: 10px; color: #fff;}
.paginate_links a.page-numbers {background: #0984e3; padding: 10px; border-radius: 5px; color: #fff;} 
.paginate_links p.readmore {display: inherit; } 
section#creating-search-acf-group-main select, section#creating-search-acf-group-main textarea {background-color: #ffffff;}
</style>
<section id="content" <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php echo fusion_render_rich_snippets_for_pages(); // phpcs:ignore WordPress.Security.EscapeOutput ?>

			<?php avada_singular_featured_image(); ?>

			<div class="post-content">
				<?php the_content(); ?>
				<?php fusion_link_pages(); ?>
			</div>
			<?php if ( ! post_password_required( $post->ID ) ) : ?>
				<?php do_action( 'avada_before_additional_page_content' ); ?>
				<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<?php $woo_thanks_page_id = get_option( 'woocommerce_thanks_page_id' ); ?>
					<?php $is_woo_thanks_page = ( ! get_option( 'woocommerce_thanks_page_id' ) ) ? false : is_page( get_option( 'woocommerce_thanks_page_id' ) ); ?>
					<?php if ( Avada()->settings->get( 'comments_pages' ) && ! is_cart() && ! is_checkout() && ! is_account_page() && ! $is_woo_thanks_page ) : ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				<?php else : ?>
					<?php if ( Avada()->settings->get( 'comments_pages' ) ) : ?>
						<?php comments_template(); ?>
					<?php endif; ?>
				<?php endif; ?>
				<?php do_action( 'avada_after_additional_page_content' ); ?>
			<?php endif; // Password check. ?>
		</div>
	<?php endwhile; ?>
</section>
<section id="creating-search-acf-group-main" class="creating-search-acf-group-main" style="margin:50px 0px;">
	<div class="main">
		<form id="acf-adv" action="" method="get">
			<select id="post_type" name="posttype" class="change-event">
				<option value="all" <?php echo ($_REQUEST['posttype'] == "all") ? 'selected' : ''; ?>>-- Select Post type --</option>
				<option value="products" <?php echo ($_REQUEST['posttype'] == "products") ? 'selected' : ''; ?>>Products</option>
				<option value="activities" <?php echo ($_REQUEST['posttype'] == "activities") ? 'selected' : ''; ?>>Activities</option>
				<option value="tribe_events" <?php echo ($_REQUEST['posttype'] == "tribe_events") ? 'selected' : ''; ?>>Events</option>
				<option value="tribe_venue" <?php echo ($_REQUEST['posttype'] == "tribe_venue") ? 'selected' : ''; ?>>Venue</option>
			</select>
			<?php 
				// Price Summary
				$field = get_field_object('field_5eeb8837e92ea');
	
				if( $field['choices'] ): ?>
			   <select id="price_summary" name="<?php echo $field['name']; ?>" class="change-event">
			   	<option value="all">-- <?php echo $field['label']; ?> --</option>
			        <?php foreach( $field['choices'] as $value => $label ): ?>
			            <option value="<?php echo $value; ?>" <?php echo ($_REQUEST['price_summary'] == $value) ? 'selected' : ''; ?>><?php echo $label; ?></option>
			        <?php endforeach; ?>
			    </select>
			<?php endif; ?>
			
			<?php 
				// Min. Age
				$field = get_field_object('field_5e44424bd1a8c');
	
				if( $field['choices'] ): ?>
			   <select id="min-age" name="min_age" class="change-event">
			   	<option value="all">-- <?php echo $field['label']; ?> --</option>
			        <?php foreach( $field['choices'] as $value => $label ): ?>
			            <option value="<?php echo $value; ?>" <?php echo (isset($_REQUEST['min_age']) && $_REQUEST['min_age'] != 'all' && $_REQUEST['min_age'] == $value) ? 'selected' : ''; ?>><?php echo $label; ?></option>
			        <?php endforeach; ?>
			    </select>
			<?php endif; ?>
			
			<?php 
				// Max Age
				$max_field = get_field_object('field_5eeb8a8ed369e');
	
				if( $max_field['choices'] ): ?>
			   <select id="max-age" name="max_age" class="change-event">
			   	<option value="all">-- <?php echo $max_field['label']; ?> --</option>
			        <?php foreach( $max_field['choices'] as $max_value => $max_label ): ?>
			            <option value="<?php echo $max_value; ?>" <?php echo (isset($_REQUEST['max_age']) && $_REQUEST['max_age'] != 'all' && $_REQUEST['max_age'] == $max_value) ? 'selected' : ''; ?>><?php echo $max_label; ?></option>
			        <?php endforeach; ?>
			    </select>
			<?php endif; ?>
			
			<?php 
				// category
				$category_field = get_field_object('field_5eebc18c8eb63');
	
				if( $category_field['choices'] ): ?>
			   <select id="max-age" name="category" class="change-event">
			   	<option value="all">-- <?php echo $category_field['label']; ?> --</option>
			        <?php foreach( $category_field['choices'] as $category_value => $category_label ): ?>
			            <option value="<?php echo $category_value; ?>" <?php echo (isset($_REQUEST['category']) && $_REQUEST['category'] != 'all' && $_REQUEST['category'] == $category_value) ? 'selected' : ''; ?>><?php echo $category_label; ?></option>
			        <?php endforeach; ?>
			    </select>
			<?php endif; ?>

			<?php 
				// interests
				$interests_field = get_field_object('field_5eeb8f6d1477b');
	
				if( $interests_field['choices'] ): ?>
			   <select id="max-age" name="interests" class="change-event">
			   	<option value="all">-- <?php echo $interests_field['label']; ?> --</option>
			        <?php foreach( $interests_field['choices'] as $interests_value => $interests_label ): ?>
			            <option value="<?php echo $interests_value; ?>" <?php echo (isset($_REQUEST['interests']) && $_REQUEST['interests'] != 'all' && $_REQUEST['interests'] == $interests_value) ? 'selected' : ''; ?>><?php echo $interests_label; ?></option>
			        <?php endforeach; ?>
			    </select>
			<?php endif; ?>
			
			<a href="<?php echo get_permalink(); ?>" style="color: white;font-size: 19px;font-weight: 700;border: 1px solid #0067c1;padding: 7px;background: #0057a4;">Reset Filter</a>
		</form>
		
	</div>
	
	<div id="search-data-result" class="search-data-result">
		<!-- <div class="loading"><p>Please wait...</p></div> -->
		<?php
			global $wp_rewrite,$wp_query;

		    $post_type = ( isset( $_REQUEST['posttype'] ) && $_REQUEST['posttype'] != 'all' ) ? array($_REQUEST['posttype']) : array( 'tribe_venue','products','activities', 'tribe_events');    
		    
		  //  $paged = ( isset( $_REQUEST['page'] ) && !empty( $_REQUEST['page'] ) ) ? $_REQUEST['page'] : 1;  
		    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

		    $price_summary = ( isset($_REQUEST['price_summary']) && $_REQUEST['price_summary'] != 'all' ) ? $_REQUEST['price_summary'] : '';    
		    $min_age = ( isset($_REQUEST['min_age']) && $_REQUEST['min_age'] != 'all' ) ? $_REQUEST['min_age'] : '';
		    $max_age = ( isset($_REQUEST['max_age']) && $_REQUEST['max_age'] != 'all' ) ? $_REQUEST['max_age'] : '';
		    
		    $category = ( isset($_REQUEST['category']) && $_REQUEST['category'] != 'all' ) ? $_REQUEST['category'] : '';
		    $interests = ( isset($_REQUEST['interests']) && $_REQUEST['interests'] != 'all' ) ? $_REQUEST['interests'] : '';

		    if( ! empty( $price_summary )){
		      $meta_query[] = array(
		              'key' => 'price_summary',
		              'value' => $price_summary,
		              'compare' => '='
		          );
		    }
		    if( ! empty( $min_age )){
		      $meta_query1[] = array(
		              'key' => 'min-age',
		              'value' => $min_age,
		              'compare' => '='
		          );
		    }
		    if( ! empty( $max_age )){
		      $meta_query2[] = array(
		              'key' => 'max-age',
		              'value' => $max_age,
		              'compare' => '='
		          );
		    }
		    if( ! empty( $category )){
		      $meta_query3[] = array(
		              'key' => 'category',
		              'value' => $category,
		              'compare' => 'LIKE'
		          );
		    }
		    if( ! empty( $interests )){
		      $meta_query4[] = array(
		              'key' => 'interests',
		              'value' => $interests,
		              'compare' => 'LIKE'
		          );
		    }
		    
		    $args = array(
		      'post_type' => $post_type,
		      'post_status' => array('publish'),
		      'posts_per_page' => 10, 
		      'paged' => get_query_var('paged'),
		      'order' => 'DESC',
		      'orderby' => 'date',
		      'paged' => $paged,
		      'meta_query'      => array(
		        'relation'    => 'AND',
		        $meta_query,$meta_query1,$meta_query2,$meta_query3,$meta_query4
		      )
		    );

		    // the query
		    $the_query = new WP_Query( $args ); 
		    $html = '';
		    if ( $the_query->have_posts() ) :
		      $html .= '<div class="acf-advance-main">';
		      while ( $the_query->have_posts() ) : $the_query->the_post();

		        /*if ( has_post_thumbnail() && ! post_password_required() ) :
		          $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');         
		         $html .= '<a href="'.get_permalink(get_the_ID()).'"><img src="'.$featured_img_url.'" /></a>';
		        endif;      
		        $html .= '<a href="'.get_permalink(get_the_ID()).'"><h2>'.get_the_title().'</h2></a>';*/

		         $postId = get_the_ID();
		        $slug = basename(get_permalink($postId));

		        $html .= '<div id="item-'.get_the_ID().'" class="right content_right acf-advance-item">';
		         $html .= '<div class="content_box">';
		          
		          $html .= '<div class="img_left left">';
		          if ( has_post_thumbnail() ) {
		                    
		              $image = get_the_post_thumbnail();
		              $html .='<div class="image"><a href="'.get_the_permalink(get_the_ID()).'">'.$image.'</a></div>';
		          }else {
		              $html .= '<a href="'.get_the_permalink(get_the_ID()).'"><img src="'.get_bloginfo('stylesheet_directory').'/default.jpg" /></a>';
		          }

		         $html .= '</div>';
		         $html .= '<div class="cont-right-cls">';
		        $html .= '<h4 data-fontsize="24" data-lineheight="32"><a href="'.get_the_permalink(get_the_ID()).'">'.get_the_title().'</a></h4>';

		          if( get_field('price_summary') ){
		            $html .= '<p><i class="fas fa-tag" style="transform: scaleX(-1);-moz-transform: scaleX(-1);    -webkit-transform: scaleX(-1);-ms-transform: scaleX(-1);padding: 0;"></i> '.get_field('price_summary').'</p>';
		          }
		          if ( get_field('min-age') || get_field('max-age') ) {
		              $html .= '<p><i class="fa fa-child" aria-hidden="true"></i> Ages: ' . get_field('min-age') . '-'.get_field('max-age').'</p>';
		          } 
		          if ( get_field('duration') ) {
		              $html .= '<p><i class="fas fa-hourglass-start"></i>' . get_field('duration') . '</p>';
		          } 
		          if ( ! empty( get_field('difficulty') ) ) {
		              $html .= '<p><i class="fas fa-tachometer-alt"></i>' . get_field('difficulty'). '</p>';
		          }

		          if ( ! empty( get_field('parentsupervision') ) ) {
		              $html .= '<p><i class="fas fa-user-shield"></i>' . get_field('parentsupervision'). '</p>';
		          } 
					/*if( ! empty( get_field( 'covid-19_safety_measures' ) ) ){

						$html .= "<div class='_safety_measures_main'><h2 class='covid_19 title'>COVID-19 Safety Measures</h2>";

						$html .= '<ul class="_safety_measures">';
						$html .= '<li>' . implode( '</li><li>', get_field( 'covid-19_safety_measures' )) . '</li>';
						$html .= '</ul></div>';
					}*/
		          $html .= '</div>';

		         $html .= '</div>';
		        $html .= '</div>';
		      endwhile;
		        

		      $big = 999999999;
		      $html .= '<div class="paginate_links">';
		     /* $html .= paginate_links( array(
		        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		        'format' => '?paged=%#%',
		        'current' => max( 1, get_query_var('paged') ),
		        'total' => $the_query->max_num_pages
		      ) );*/

		        $paginate_base = get_pagenum_link(1);
		        if (strpos($paginate_base, '?') || ! $wp_rewrite->using_permalinks()) {
		            $paginate_format = '';
		            $paginate_base = add_query_arg('paged', '%#%');
		        } else {
		            $paginate_format = '?paged=%#%';
		            $paginate_base .= '%_%';
		        }

		        $nextPage = $paged + 1;
		        $prevPage = $paged - 1;
		        
		        $html .= paginate_links( array(
		            'base' => $paginate_base,
		            'format' => $paginate_format,
		            'total' => $the_query->max_num_pages,
		            'mid_size' => 2,
		            'current' => ($paged ? $paged : 1),
		            'type' => '',
		            'prev_text' => __('<p data-page="'. $prevPage .'" class="readmore">&laquo; Previous</p>', 'default'),
		            'next_text' => __('<p data-page="'. $nextPage .'" class="readmore">Next &raquo;</p>', 'default'),
		        ));
		   
		      $html .= '</div>';
		      $html .= '</div>';
		      wp_reset_postdata();

		      else :
		        $html .= __('<p>Sorry, no posts matched your criteria.</p>', 'default');
		      endif;

		      echo $html;

		?>
	</div>
	<?php 

	?>
</section>
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer(); ?>
<div id="overlay">
  <div class="cv-spinner">
    <span class="spinner"></span>
  </div>
</div>
<script type='text/javascript'> 
jQuery(document).ready(function(){
  jQuery('select.change-event').change(function(){
    jQuery('#acf-adv').submit();
  });
});
</script>
<!-- <script type="text/javascript">

jQuery(document).ready(function() {
 
  	jQuery("select.change-event").on('change', function() {
  		ajax_resultspageinfo();
	});

  	jQuery(document).ajaxSend(function() {
		jQuery("#overlay").fadeIn(300);　
	});

	jQuery(document).on('click', '.paginate_links a.page-numbers', function(e){
		var post_type = jQuery('#post_type').val();
		var price_summary = jQuery('#price_summary').val();
		var min_age = jQuery('#min-age').val();
		var max_age = jQuery('#max-age').val();

		var current_page = jQuery(this).text();

		if(jQuery(this).children('p').length) {
			current_page = jQuery(this).children('p').data('page');
		}

		ajax_resultspageinfo(current_page);
		e.preventDefault();
	});

});

ajax_resultspageinfo();

function ajax_resultspageinfo(pagination){

	var price_summary = jQuery('#price_summary').val();
	var min_age = jQuery('#min-age').val();
	var max_age = jQuery('#max-age').val();
	var post_type = jQuery('#post_type').val();

	var data = {
	    action: 'resultspageinfo',
	    post_type: post_type,
	    price_summary: price_summary,
	    min_age: min_age,
	    max_age: max_age,
	    page: pagination,
	};

	jQuery.ajax({
	    type: "post",
	    url: '<?php echo admin_url('admin-ajax.php'); ?>',
	    data: data,
	    success: function ( response ) {
	        jQuery('div.search-data-result').html(response);
	    }
	}).done(function() {
		setTimeout(function(){
			jQuery("#overlay").fadeOut(300);
		},500);
	});	
}
</script> -->