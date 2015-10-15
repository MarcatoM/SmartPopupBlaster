<?php
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

function popup_setup() {    

    $args = array(
      'post_type'           => 'spb',
      'post_status'         => 'publish',
      'posts_per_page'      => 10
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

        $opacity_value = $overlay_color_opacity / 10;
        $overlay_color_rgba = hex2rgba($overlay_color, $opacity_value);

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

    <style type="text/css">
        .overlay-bg-<?php echo the_ID(); ?> {
            display: none;
            position: absolute;
            top: 0;
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
            padding: 1%;
            width: 40%;
            position: fixed;
            top: 15%;
            left: 50%;
            margin-left: -20%; /* add negative left margin for half the width to center the div */
            cursor: default;
            z-index: <?php echo $z_index+1; ?>;
            border-radius: 4px;
            box-shadow: 0 0 5px rgba(0,0,0,0.9);
        }

    </style>

    <div id="spb-popup-<?php the_ID(); ?>" class="overlay-bg overlay-bg-<?php the_ID(); ?> <?php echo $trigger; ?>" data-id="<?php echo $the_post_id; ?>" data-effect="<?php echo $effect; ?>" data-delay="<?php echo $delay_value; ?>" data-scroll="<?php echo $scroll_value; ?>" data-cookie="<?php echo $cookie_value; ?>"></div>
    <div class="overlay-content overlay-content-<?php the_ID(); ?> spb-popup-class-<?php the_ID(); ?>">
    <p><?php the_content(); ?></p>
    <span class="close-btn close-btn-<?php the_ID(); ?>" data-id="<?php echo $the_post_id; ?>"></span>
    </div> 

    <?php
    //array_push($popup_data_a, get_the_ID());
    }
    endwhile;

    wp_reset_postdata();
    

}

add_action('wp_footer', 'popup_setup', 1);
?>