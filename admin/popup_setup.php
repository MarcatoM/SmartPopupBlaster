<?php
function popup_setup()
{

    $args = array(
        'post_type' => 'spb',
        'post_status' => 'publish',
        'posts_per_page' => 999,
    );
    $loop = new WP_Query($args);

    while ($loop->have_posts()): $loop->the_post();

        $show_exclude = get_post_meta(get_the_ID(), "_spb_popup_exclude", true);

        $show_exclude_homepage = in_array('Exclude on Home Page', $show_exclude);
        $show_exclude_page = in_array('Exclude on Pages', $show_exclude);
        $show_exclude_post = in_array('Exclude on Posts', $show_exclude);
        $show_exclude_category = in_array('Exclude on Categories', $show_exclude);
        $show_exclude_tags = in_array('Exclude on Tags', $show_exclude);
        $show_exclude_search = in_array('Exclude on Search Pages', $show_exclude);
        $show_exclude_404 = in_array('Exclude on 404 Pages', $show_exclude);

        if (($show_exclude_homepage && is_front_page()) ||
        ($show_exclude_homepage && is_home()) ||
        ($show_exclude_page && is_page() && !(is_front_page() || is_home())) ||
        ($show_exclude_post && is_single()) ||
        ($show_exclude_category && is_category()) ||
        ($show_exclude_tags && is_tag()) ||
        ($show_exclude_search && is_search()) ||
        ($show_exclude_404 && is_404())) {

            continue;
        } else {
            $the_post_id = get_the_ID();
            $effect = get_post_meta(get_the_ID(), 'spb_popup_effect', true);
            $popup_trigger = get_post_meta(get_the_ID(), 'spb_popup_trigger', true);
            $delay_value = get_post_meta(get_the_ID(), "spb_popup_delay_value", true);
            $scroll_value = get_post_meta(get_the_ID(), "spb_popup_scroll_value", true);
            $cookie_value = get_post_meta(get_the_ID(), "spb_cookie_value", true);

            if ($popup_trigger == "exit_intent") {
                $trigger = "spb-exit_intent";
            } elseif ($popup_trigger == "time") {
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


    <div id="spb-popup-<?php the_ID();?>" class="overlay-bg overlay-bg-<?php the_ID();?> <?php echo $trigger; ?>" data-id="<?php echo $the_post_id; ?>" data-effect="<?php echo $effect; ?>" data-delay="<?php echo $delay_value; ?>" data-scroll="<?php echo $scroll_value; ?>" data-cookie="<?php echo $cookie_value; ?>"></div>
    <div class="overlay-content overlay-content-<?php the_ID();?> spb-popup-class-<?php the_ID();?>">
    <p><?php the_content();?></p>
    <span class="close-btn close-btn-<?php the_ID();?>" data-id="<?php echo $the_post_id; ?>"></span>
    </div>

    <?php
//array_push($popup_data_a, get_the_ID());
    }
    endwhile;

    wp_reset_postdata();

}

add_action('wp_footer', 'popup_setup', 1);
