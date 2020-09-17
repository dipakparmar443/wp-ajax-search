<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_action("wp_ajax_resultspageinfo", "resultspageinfo");
add_action("wp_ajax_nopriv_resultspageinfo", "resultspageinfo");
function resultspageinfo(){
    global $wp_rewrite,$wp_query;

    $post_type = ( isset( $_REQUEST['post_type'] ) && $_REQUEST['post_type'] != 'all' ) ? array($_REQUEST['post_type']) : array( 'tribe_venue','products','activities', 'tribe_events');    
    
    $paged = ( isset( $_REQUEST['page'] ) && !empty( $_REQUEST['page'] ) ) ? $_REQUEST['page'] : 1;    
    $price_summary = ( isset($_REQUEST['price_summary']) && $_REQUEST['price_summary'] != 'all' ) ? $_REQUEST['price_summary'] : '';    
    $min_age = ( isset($_REQUEST['min_age']) && $_REQUEST['min_age'] != 'all' ) ? $_REQUEST['min_age'] : '';
    $max_age = ( isset($_REQUEST['max_age']) && $_REQUEST['max_age'] != 'all' ) ? $_REQUEST['max_age'] : '';

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
        $meta_query,$meta_query1,$meta_query2
      )
    );

    // the query
    $the_query = new WP_Query( $args ); 
    $html = '';
    if ( $the_query->have_posts() ) :
      $html .= '<div class="acf-advance-main">';
      while ( $the_query->have_posts() ) : $the_query->the_post();

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
            $html .= '<p><img clss="img-icon-cmmn-cls" src="/wp-content/uploads/2020/06/price.png">'.get_field('price_summary').'</p>';
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
          $html .= '</div>';

         $html .= '</div>';
        $html .= '</div>';
      endwhile;
        

      $big = 999999999;
      $html .= '<div class="paginate_links">';
      /*$html .= paginate_links( array(
        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
        'format' => '?paged=%#%',
        'current' => max( 1, get_query_var('paged') ),
        'total' => $the_query->max_num_pages
      ) );*/

        $paginate_base = get_pagenum_link(1);
        if (strpos($paginate_base, '?') || ! $wp_rewrite->using_permalinks()) {
            $paginate_format = '';
            $paginate_base = add_query_arg('page', '%#%');
        } else {
            $paginate_format = '?page=%#%';
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
    
    die();
}