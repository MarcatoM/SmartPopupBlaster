<?php
header( "Content-type: text/css; charset: UTF-8" );


/* Convert hexdec color string to rgb(a) string */ 
function hex2rgba($color, $opacity = false) { 
 $default = 'rgb(0,0,0)'; 
 //Return default if no color provided
 if(empty($color))
          return $default;  
 //Sanitize $color if "#" is provided 
        if ($color[0] == '#' ) {
         $color = substr( $color, 1 );
        } 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        } else {
                return $default;
        } 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex); 
        //Check if opacity is set(rgba or rgb)
        if($opacity){
         if(abs($opacity) > 1)
         $opacity = 1.0;
         $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        } else {
         $output = 'rgb('.implode(",",$rgb).')';
        } 
        //Return rgb(a) color string
        return $output;
}




   $args = array(
     'post_type'           => 'spb',
     'post_status'         => 'publish',
     'posts_per_page'      => 999
     );
   $loop = new WP_Query( $args );
   while ( $loop->have_posts() ) : $loop->the_post();  
   $show_exclude = get_post_meta(get_the_ID(), "_spb_popup_exclude", true);
   $show_exclude_homepage = in_array('Exclude on Home Page', $show_exclude);
   $show_exclude_page = in_array('Exclude on Pages', $show_exclude);
   $show_exclude_post = in_array('Exclude on Posts', $show_exclude);
   $show_exclude_category = in_array('Exclude on Categories', $show_exclude);
   $show_exclude_tags = in_array('Exclude on Tags', $show_exclude);
   $show_exclude_search = in_array('Exclude on Search Pages', $show_exclude);
   $show_exclude_404 = in_array('Exclude on 404 Pages', $show_exclude);
   if( ($show_exclude_homepage && is_front_page()) || 
       ($show_exclude_page && is_page()) ||
       ($show_exclude_post && is_singular()) ||
       ($show_exclude_category && is_category()) ||
       ($show_exclude_tags && is_tag()) ||
       ($show_exclude_search && is_search()) ||
       ($show_exclude_404 && is_404()) ){
       continue;
   } else {
       $the_post_id = get_the_ID();
       $effect = get_post_meta(get_the_ID(), 'spb_popup_effect', true);
       $popup_trigger = get_post_meta(get_the_ID(), 'spb_popup_trigger', true);
       $delay_value = get_post_meta(get_the_ID(), "spb_popup_delay_value", true);
       $scroll_value = get_post_meta(get_the_ID(), "spb_popup_scroll_value", true);
       $cookie_value = get_post_meta(get_the_ID(), "spb_cookie_value", true);
       $bcg_color = get_post_meta(get_the_ID(), "spb_bcg_color", true);
       $overlay_color = get_post_meta(get_the_ID(), "spb_overlay_color", true);
       $overlay_color_opacity = get_post_meta(get_the_ID(), "spb_overlay_opacity", true);
       $button_color = get_post_meta(get_the_ID(), "spb_button_color", true);
       $button_color_hover = get_post_meta(get_the_ID(), "spb_button_hover_color", true);
       $button_text = get_post_meta(get_the_ID(), "spb_button_text", true);
       $button_text_color = get_post_meta(get_the_ID(), "spb_button_text_color", true);
       $content_padding_lr = get_post_meta(get_the_ID(), "spb_content_padding_lr", true);
       $content_padding_tb = get_post_meta(get_the_ID(), "spb_content_padding_tb", true);
       $content_box_shadow_horizontal = get_post_meta(get_the_ID(), "spb_content_box_shadow_horizontal", true);
       $content_box_shadow_vertical = get_post_meta(get_the_ID(), "spb_content_box_shadow_vertical", true);
       $content_box_shadow_spread = get_post_meta(get_the_ID(), "spb_content_box_shadow_spread", true);
       $content_box_shadow_color = get_post_meta(get_the_ID(), "spb_content_box_shadow_color", true);
       $content_box_shadow_opacity = get_post_meta(get_the_ID(), "spb_content_box_shadow_opacity", true);
       $opacity_value = $overlay_color_opacity / 10;
       $overlay_color_rgba = hex2rgba($overlay_color, $opacity_value);
       $box_shadow_opacity_value = $overlay_color_opacity / 10;
       $box_shadow_color_rgba = hex2rgba($content_box_shadow_color, $box_shadow_opacity_value);
       $box_shadow = $content_box_shadow_horizontal.'px '.$content_box_shadow_vertical.'px '.$content_box_shadow_spread.'px '.$box_shadow_color_rgba;
       if($popup_trigger == "time"){
           $trigger = "spb-delay";
       } elseif ($popup_trigger == "scroll") {
           $trigger = "spb-scroll";
       } else {
           $trigger = "";
       }
       if ($cookie_value == "") {
           $cookie_value = 1.1;
       }
       $z_index = 1000000000 + $the_post_id;
   ?>
   
       .overlay-bg-<?php echo the_ID(); ?> {
           display: none;
           position: fixed;
           top: 0;
           right: 0;
           bottom: 0;
           left: 0;
           height:100%;
           width: 100%;
           cursor: pointer;
           z-index: <?php echo $z_index; ?>; /* high z-index */
           background: <?php echo $overlay_color; ?>; /* fallback */
           background: <?php echo $overlay_color_rgba; ?>;            
       }   
       .overlay-content-<?php echo the_ID(); ?> {
           display: none;
           background: <?php echo $bcg_color; ?>;
           padding: <?php echo $content_padding_tb; ?>px <?php echo $content_padding_lr; ?>px;
           width: 40%;
           max-height: 100%;
           position: fixed;
           top: 15%;
           left: 50%;
           margin-left: -20%; /* add negative left margin for half the width to center the div */
           cursor: default;
           z-index: <?php echo $z_index+1; ?>;
           border-radius: 4px;
           box-shadow: 0 0 5px rgba(0,0,0,0.9); /* fallback */
           box-shadow: <?php echo $box_shadow; ?>;
       } 
       .overlay-content p:first-child{
           margin-top: 30px;
       } 
       .close-btn-<?php echo the_ID(); ?>{
           cursor: pointer;
           position: absolute;
           top: 0;
           right: 0;
           padding: 5px;
           text-align: center;
           font-size: 1em;
           font-family: arial;
           color: <?php echo $button_text_color; ?>;  
           background: <?php echo $button_color; ?>;
           /* border-radius: 100%; */
           box-shadow: 0 0 4px rgba(0,0,0,0.3);
       }
       .close-btn-<?php echo the_ID(); ?>:after{
           content: '<?php echo $button_text; ?>';
       }
       .close-btn-<?php echo the_ID(); ?>:hover {
           background: <?php echo $button_color_hover; ?>;
       }
       /* media query for most mobile devices */
       @media only screen and (min-width: 480px) and (max-width: 980px){         
           .overlay-content-<?php echo the_ID(); ?> {
               width: 70%;
               margin: 0 15%;
               left: 0;
           }
       }
       @media only screen and (min-width: 0px) and (max-width: 480px){         
           .overlay-content-<?php echo the_ID(); ?> {
               width: 92%;
               margin: 0 0% 0 2%;
               left: 0;
           }
       }
  
   <?php
   //array_push($popup_data_a, get_the_ID());
   }
   endwhile;
   wp_reset_postdata();
    
?>