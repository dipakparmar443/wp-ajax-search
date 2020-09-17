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
		<form action="" method="post">
			<select id="post_type" name="post_type" class="change-event">
				<option value="all">-- Select Post type --</option>
				<option value="products">Products</option>
				<option value="activities">Activities</option>
				<option value="tribe_events">Events</option>
				<option value="tribe_venue">Venue</option>
			</select>
			<?php 
				// Price Summary
				$field = get_field_object('field_5eeb8837e92ea');
	
				if( $field['choices'] ): ?>
			   <select id="price_summary" name="<?php echo $field['name']; ?>" class="change-event">
			   	<option value="all">-- <?php echo $field['label']; ?> --</option>
			        <?php foreach( $field['choices'] as $value => $label ): ?>
			            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
			        <?php endforeach; ?>
			    </select>
			<?php endif; ?>
			
			<?php 
				// Min. Age
				$field = get_field_object('field_5e44424bd1a8c');
	
				if( $field['choices'] ): ?>
			   <select id="min-age" name="<?php echo $field['name']; ?>" class="change-event">
			   	<option value="all">-- <?php echo $field['label']; ?> --</option>
			        <?php foreach( $field['choices'] as $value => $label ): ?>
			            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
			        <?php endforeach; ?>
			    </select>
			<?php endif; ?>
			
			<?php 
				// Max Age
				$field = get_field_object('field_5eeb8a8ed369e');
	
				if( $field['choices'] ): ?>
			   <select id="max-age" name="<?php echo $field['name']; ?>" class="change-event">
			   	<option value="all">-- <?php echo $field['label']; ?> --</option>
			        <?php foreach( $field['choices'] as $value => $label ): ?>
			            <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
			        <?php endforeach; ?>
			    </select>
			<?php endif; ?>
			
		</form>
		
	</div>
	
	<div id="search-data-result" class="search-data-result">
		<div class="loading"><p>Please wait...</p></div>
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
<script type="text/javascript">

jQuery(document).ready(function() {
  /*jQuery(".paginate_links a.page-numbers").click(function(event) {
  	event.preventDefault();
    var pagination = jQuery(this).text();
    ajax_resultspageinfo(pagination);
  });*/
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
	    	//console.log(response);
	        jQuery('div.search-data-result').html(response);
	    }
	}).done(function() {
		setTimeout(function(){
			jQuery("#overlay").fadeOut(300);
		},500);
	});	
}
</script>