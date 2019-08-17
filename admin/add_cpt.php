<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'spb_post_types');
function spb_post_types()
{

    $labels = array(
        'name' => __('Smart PopUp Blaster', 'smart-popup-blaster'),
        'singular_name' => __('Smart PopUp Blaster', 'smart-popup-blaster'),
        'add_new' => __('Add New PopUp', 'smart-popup-blaster'),
        'add_new_item' => __('Add New PopUp', 'smart-popup-blaster'),
        'edit_item' => __('Edit PopUp', 'smart-popup-blaster'),
        'new_item' => __('New PopUp', 'smart-popup-blaster'),
        'all_items' => __('All PopUps', 'smart-popup-blaster'),
        'view_item' => __('View PopUp', 'smart-popup-blaster'),
        'search_items' => __('Search PopUps', 'smart-popup-blaster'),
        'not_found' => __('No PopUp found', 'smart-popup-blaster'),
        'not_found_in_trash' => __('No PopUp found in Trash', 'smart-popup-blaster'),
        'menu_name' => __('Smart PopUp Blaster', 'smart-popup-blaster'),
    );

    $supports = array('title', 'editor', 'revisions', 'author');

    $args = array(
        'labels' => $labels,
        'show_ui' => true,
        'query_var' => false,
        'rewrite' => array('slug' => 'spb'),
        'capability_type' => 'post',
        'menu_position' => 30,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => $supports,
    );

    register_post_type('spb', $args);
}

//manage the columns of the `page` post type
function manage_columns_for_spb($columns)
{
    //add new columns
    $columns['popup_id'] = 'PopUp ID';
    $columns['popup_data_id'] = 'Data ID';
    $columns['popup_shortcode'] = 'Shortcode';
    return $columns;
}
add_action('manage_spb_posts_columns', 'manage_columns_for_spb');

//Populate custom columns for `page` post type
function populate_page_columns($column, $post_id)
{
    $the_id = get_the_ID();

    //page content column
    if ($column == 'popup_id') {
        echo '<pre><code>spb-popup-' . $the_id . '</code><pre>';
    }
    if ($column == 'popup_data_id') {
        echo '<pre><code>data-id="' . $the_id . '"</code><pre>';
    }
    if ($column == 'popup_shortcode') {
        $shortcode = '[spb-button id="' . $the_id . '" text="Click Me!"]';
        echo '<input onClick="this.setSelectionRange(0, this.value.length)" value="' . htmlentities($shortcode) . '" />';
    }
}
add_action('manage_spb_posts_custom_column', 'populate_page_columns', 10, 2);

/* Meta Boxes */

function settings_box($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce1");
    ?>
    <div>

      <label for="spb_popup_effect"><?php _e("PopUp Effect: ", 'smart-popup-blaster');?></label>
      <select name="spb_popup_effect">
          <?php
$option_values = array(
        'bounce',
        'bounceIn',
        'bounceInDown',
        'bounceInLeft',
        'bounceInRight',
        'bounceInUp',
        'fadeIn',
        'flash',
        'flipInX',
        'flipInY',
        'lightSpeedIn',
        'pulse',
        'rollIn',
        'rotateIn',
        'shake',
        'swing',
        'tada',
        'wobble',
    );
    foreach ($option_values as $key => $value) {
        if ($value == get_post_meta($object->ID, "spb_popup_effect", true)) {
            ?>
                          <option selected><?php echo $value; ?></option>
                      <?php
} else {
            ?>
                          <option><?php echo $value; ?></option>
                      <?php
}
    }
    ?>
      </select>

    </div>
<?php
}

function show_on_box($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce2");
    ?>
    <div>

      <?php
$show_exclude = get_post_meta($object->ID, "_spb_popup_exclude", true);
    if (empty($show_exclude)) {
        $show_exclude = [];
        $show_exclude[] = 'On Entire Site';
    }
    ?>

        <input type="checkbox" name="spb_popup_exclude[]" value="On Entire Site" <?php echo (in_array('On Entire Site', $show_exclude)) ? 'checked="checked"' : ''; ?> /><span>Show on Entire Site</span><br><br>
        <input type="checkbox" name="spb_popup_exclude[]" value="Exclude on Home Page" <?php echo (in_array('Exclude on Home Page', $show_exclude)) ? 'checked="checked"' : ''; ?> /><span> Exclude on Home Page</span><br>
        <input type="checkbox" name="spb_popup_exclude[]" value="Exclude on Posts" <?php echo (in_array('Exclude on Posts', $show_exclude)) ? 'checked="checked"' : ''; ?> /><span> Exclude on Posts</span><br>
        <input type="checkbox" name="spb_popup_exclude[]" value="Exclude on Pages" <?php echo (in_array('Exclude on Pages', $show_exclude)) ? 'checked="checked"' : ''; ?> /><span> Exclude on Pages</span><br>
        <input type="checkbox" name="spb_popup_exclude[]" value="Exclude on Categories" <?php echo (in_array('Exclude on Categories', $show_exclude)) ? 'checked="checked"' : ''; ?> /><span> Exclude on Categories</span><br>
        <input type="checkbox" name="spb_popup_exclude[]" value="Exclude on Tags" <?php echo (in_array('Exclude on Tags', $show_exclude)) ? 'checked="checked"' : ''; ?> /><span> Exclude on Tags</span><br>
        <input type="checkbox" name="spb_popup_exclude[]" value="Exclude on Search Pages" <?php echo (in_array('Exclude on Search Pages', $show_exclude)) ? 'checked="checked"' : ''; ?> /><span> Exclude on Search Pages</span><br>
        <input type="checkbox" name="spb_popup_exclude[]" value="Exclude on 404 Pages" <?php echo (in_array('Exclude on 404 Pages', $show_exclude)) ? 'checked="checked"' : ''; ?> /><span> Exclude on 404 Pages</span><br>

    </div>
<?php
}

function trigger_box($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce3");

    $popup_trigger = get_post_meta($object->ID, "spb_popup_trigger", true);
    $delay_value = get_post_meta($object->ID, "spb_popup_delay_value", true);
    $scroll_value = get_post_meta($object->ID, "spb_popup_scroll_value", true);
    $exit_intent = get_post_meta($object->ID, "spb_popup_exit_intent", true);

    if ($popup_trigger == "") {
        $popup_trigger = "none";
    }
    ?>
    <script>
      var trigger_value = <?php echo json_encode($popup_trigger) ?>;
      var delay_value = <?php echo json_encode($delay_value) ?>;
      var scroll_value = <?php echo json_encode($scroll_value) ?>;
      var exit_intent = <?php echo json_encode($exit_intent) ?>;
    </script>

    <table>
      <tr>
      <td>Click: </td>
        <td><input type="radio" id="no-trigger" name="spb_popup_trigger" value="none" <?php if ($popup_trigger == "none") {echo "checked";}?> /></td>
      </tr>
      <tr>
      <td colspan="5"><span id="click-help" style="background-color:#F1F1F1;padding:4px;<?php if ($popup_trigger !== 'none') {echo 'display:none;';}?>">
        <?php $shortcode = '[spb-button id="' . get_the_ID() . '" text="Click Me!"]';?>
        <small>Button Shortcode: </small> <input onClick="this.setSelectionRange(0, this.value.length)" value="<?php echo htmlentities($shortcode); ?>" />
        <br>
      <small>For more options goto help page.</small>
      </span></td>
      </tr>
      <td>Exit Intent: </td>
        <td><input type="radio" id="exit-trigger" name="spb_popup_trigger" value="exit_intent" <?php if ($popup_trigger == "exit_intent") {echo "checked";}?> /></td>
      </tr>
      <tr>
      <td colspan="5"><span id="exit_intent-help" style="background-color:#F1F1F1;padding:4px;<?php if ($popup_trigger !== 'exit_intent') {echo 'display:none;';}?>"><small>When mouse leaving upper browser window, popup will be triggered. <strong>This trigger is available 4 seconds after page load.</strong></small></span></td>
      </tr>
      <tr>
        <td>Delay: </td>
        <td><input type="radio" id="delay-trigger" name="spb_popup_trigger" value="time" <?php if ($popup_trigger == "time") {echo "checked";}?> /></td>
        <td><div id="delay_slider" style="width:90px; margin-left:10px;"></div></td>
        <td><input type="text" id="delay_slider_value" name="spb_popup_delay_value" value="<?php echo $delay_value; ?>" size="1" /></td>
        <td>sec</td>
      </tr>
      <tr>
        <td>Scroll: </td>
        <td><input type="radio" id="scroll-trigger" name="spb_popup_trigger" value="scroll" <?php if ($popup_trigger == "scroll") {echo "checked";}?> /></td>
        <td><div id="scroll_slider" style="width:90px; margin-left:10px;"></div></td>
        <td><input type="text" id="scroll_slider_value" name="spb_popup_scroll_value" value="<?php echo $scroll_value; ?>" size="1" /></td>
        <td>%</td>
      </tr>
    </table>
    <p id="scroll-help" style="background-color:#F1F1F1;padding:4px;<?php if ($popup_trigger !== 'scroll') {echo 'display:none;';}?>"><small><strong>Lower percentage:</strong> bottom of the page </br><strong>Higher percentage:</strong> top of the page</small></p>

    <?php
}

function content_box($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce4");
    $bcg_color = get_post_meta($object->ID, "spb_bcg_color", true);
    $content_padding_lr = get_post_meta($object->ID, "spb_content_padding_lr", true);
    $content_padding_tb = get_post_meta($object->ID, "spb_content_padding_tb", true);
    $content_box_shadow_horizontal = get_post_meta($object->ID, "spb_content_box_shadow_horizontal", true);
    $content_box_shadow_vertical = get_post_meta($object->ID, "spb_content_box_shadow_vertical", true);
    $content_box_shadow_spread = get_post_meta($object->ID, "spb_content_box_shadow_spread", true);
    $content_box_shadow_color = get_post_meta($object->ID, "spb_content_box_shadow_color", true);
    $content_box_shadow_opacity = get_post_meta($object->ID, "spb_content_box_shadow_opacity", true);

    if ($bcg_color == '') {
        $bcg_color = '#fff';
    }
    if ($content_padding_lr == '') {
        $content_padding_lr = 10;
    }
    if ($content_padding_tb == '') {
        $content_padding_tb = 10;
    }
    if ($content_box_shadow_horizontal == '') {
        $content_box_shadow_horizontal = 0;
    }
    if ($content_box_shadow_vertical == '') {
        $content_box_shadow_vertical = 0;
    }
    if ($content_box_shadow_spread == '') {
        $content_box_shadow_spread = 5;
    }
    if ($content_box_shadow_color == '') {
        $content_box_shadow_color = '#000';
    }
    if ($content_box_shadow_opacity == '') {
        $content_box_shadow_opacity = 9;
    }
    ?>
    <table>
      <tr>
        <td style="width:250px;"><h3>Content Background Color </h3></td>
        <td><input type="text" class="color-field" name="spb_bcg_color" value="<?php echo $bcg_color; ?>" /><div class="clear"></div></td>
        <td style="width:150px;"></td>
        <td style="width:250px;">
        <span><h3>Content Padding Top and Bottom</h3></span>
        <span><h3>Content Padding Left and Right</h3></span>
        </td>
        <td>
        <span style="display:block; margin-bottom:7px;"><input type="text" name="spb_content_padding_tb" value="<?php echo $content_padding_tb; ?>" size="1" /> px</span>
        <span style="display:block;"><input type="text" name="spb_content_padding_lr" value="<?php echo $content_padding_lr; ?>" size="1" /> px</span>
        </td>
      </tr>
      <tr>
        <td colspan="4" style="padding-top:30px;"></td>
      </tr>
      <tr>
        <td style="width:250px;"><h3>Box Shadow Color </h3></td>
        <td><input type="text" class="color-field" name="spb_content_box_shadow_color" value="<?php echo $content_box_shadow_color; ?>" /><div class="clear"></div></td>
      </tr>
      <tr>
        <td style="width:250px;"><h3>Box Shadow Opacity </h3></td>
        <td>
        <select name="spb_content_box_shadow_opacity">
          <?php
$content_box_shadow_opacity = get_post_meta($object->ID, "spb_content_box_shadow_opacity", true);
    if ($content_box_shadow_opacity == "") {
        $content_box_shadow_opacity = 9;
    }
    for ($i = 1; $i < 11; $i++) {
        if ($i == $content_box_shadow_opacity) {
            ?>
                          <option selected><?php echo $content_box_shadow_opacity; ?></option>
                      <?php
} else {
            ?>
                          <option><?php echo $i; ?></option>
                      <?php
}
    }
    ?>
        </select>
        </td>
      </tr>
      <tr>
        <td style="width:250px;"><h3>Box Shadow Spread </h3></td>
        <td><input type="text" name="spb_content_box_shadow_spread" value="<?php echo $content_box_shadow_spread; ?>" size="1" /> px</td>
      </tr>
      <tr>
        <td style="width:250px;"><h3>Box Shadow Horizontal Spread </h3></td>
        <td><input type="text" name="spb_content_box_shadow_horizontal" value="<?php echo $content_box_shadow_horizontal; ?>" size="1" /> px</td>
      </tr>
      <tr>
        <td style="width:250px;"><h3>Box Shadow Vertical Spread </h3></td>
        <td><input type="text" name="spb_content_box_shadow_vertical" value="<?php echo $content_box_shadow_vertical; ?>" size="1" /> px</td>
      </tr>

    </table>

    <script>
      (function( $ ) {
        // Add Color Picker to all inputs that have 'color-field' class
        $(function() {
        $('.color-field').wpColorPicker();
        });
      })( jQuery );
    </script>
    <?php
}

function cookie_box($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce5");
    $set_cookie = get_post_meta($object->ID, "spb_set_cookie", true);
    $cookie_value = get_post_meta($object->ID, "spb_cookie_value", true);
    ?>
    <script>
      var set_cookie = <?php echo json_encode($set_cookie) ?>;
      var cookie_value = <?php echo json_encode($cookie_value) ?>;
    </script>

    <table>
      <tr>
        <th colspan="2" style="text-align:left;">Set Cookie </th>
        <th colspan="2" style="text-align:right;"><input type="checkbox" name="spb_set_cookie" value="set" <?php if ($set_cookie == "set") {echo "checked";}?> /></th>
      </tr>
      <tr>
        <td>Expire:</td>
        <td><div id="cookie_slider" style="width:90px; margin-left:10px;"></div></td>
        <td><input type="text" id="cookie_slider_value" name="spb_cookie_value" value="<?php echo $cookie_value; ?>" size="1" /></td>
        <td>days</td>
      </tr>
    </table>

    <?php
}

function overlay_box($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce6");
    $overlay_color = get_post_meta($object->ID, "spb_overlay_color", true);
    if ($overlay_color == '') {
        $overlay_color = '#000';
    }
    ?>
    <table>
      <tr>
        <td style="width:250px;"><h3>Overlay Background Color </h3></td>
        <td><input type="text" class="color-field" name="spb_overlay_color" value="<?php echo $overlay_color; ?>" /><div class="clear"></div></td>
        <td style="width:150px;"></td>
        <td style="width:250px;"><h3>Overlay Background Opacity </h3></td>
        <td>
        <select name="spb_overlay_opacity">
          <?php
$opacity_value = get_post_meta($object->ID, "spb_overlay_opacity", true);
    if ($opacity_value == "") {
        $opacity_value = 7;
    }
    for ($i = 1; $i < 11; $i++) {
        if ($i == $opacity_value) {
            ?>
                          <option selected><?php echo $opacity_value; ?></option>
                      <?php
} else {
            ?>
                          <option><?php echo $i; ?></option>
                      <?php
}
    }
    ?>
        </select>
        </td>
      </tr>
    </table>
    <?php

}

function close_button_box($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce7");
    $button_color = get_post_meta($object->ID, "spb_button_color", true);
    $button_color_hover = get_post_meta($object->ID, "spb_button_hover_color", true);
    $button_text = get_post_meta($object->ID, "spb_button_text", true);
    $button_text_color = get_post_meta($object->ID, "spb_button_text_color", true);
    if ($button_color == '') {
        $button_color = '#fff';
    }
    if ($button_color_hover == '') {
        $button_color_hover = '#fff';
    }
    if ($button_text == '') {
        $button_text = 'X';
    }
    if ($button_text_color == '') {
        $button_text_color = '#000';
    }
    ?>
    <table>
      <tr>
        <td style="width:250px;"><h3>Close Button Color </h3></td>
        <td><input type="text" class="color-field" name="spb_button_color" value="<?php echo $button_color; ?>" /><div class="clear"></div></td>
        <td style="width:150px;"></td>
        <td style="width:250px;"><h3>Close Button Text </h3></td>
        <td><input type="text" name="spb_button_text" value="<?php echo $button_text; ?>" maxlength="10" /></td>
      </tr>
      <tr>
        <td style="width:250px;"><h3>Close Button Hover Color </h3></td>
        <td><input type="text" class="color-field" name="spb_button_hover_color" value="<?php echo $button_color_hover; ?>" /><div class="clear"></div></td>
        <td style="width:150px;"></td>
        <td style="width:250px;"><h3>Close Button Text Color</h3></td>
        <td><input type="text" class="color-field" name="spb_button_text_color" value="<?php echo $button_text_color; ?>" /><div class="clear"></div></td>
      </tr>
    </table>
    <?php
}

function add_settings_box()
{
    add_meta_box("show_settings_box", "Targeting Conditions", "show_on_box", "spb", "side", "high", null);
    add_meta_box("effect_settings_box", "PopUp Effect", "settings_box", "spb", "side", "high", null);
    add_meta_box("trigger_settings_box", "PopUp Trigger", "trigger_box", "spb", "side", "high", null);
    add_meta_box("content_settings_box", "PopUp Content", "content_box", "spb", "normal", "high", null);
    add_meta_box("cookie_settings_box", "Set Cookie", "cookie_box", "spb", "side", "high", null);
    add_meta_box("overlay_settings_box", "Overlay Background", "overlay_box", "spb", "normal", "high", null);
    add_meta_box("close_button_settings_box", "Close Button", "close_button_box", "spb", "normal", "high", null);
}
add_action("add_meta_boxes", "add_settings_box");

function save_settings_box($post_id, $post, $update)
{
    if ((!isset($_POST["meta-box-nonce1"]) || !wp_verify_nonce($_POST["meta-box-nonce1"], basename(__FILE__))) &&
        (!isset($_POST["meta-box-nonce2"]) || !wp_verify_nonce($_POST["meta-box-nonce2"], basename(__FILE__))) &&
        (!isset($_POST["meta-box-nonce3"]) || !wp_verify_nonce($_POST["meta-box-nonce3"], basename(__FILE__))) &&
        (!isset($_POST["meta-box-nonce4"]) || !wp_verify_nonce($_POST["meta-box-nonce4"], basename(__FILE__))) &&
        (!isset($_POST["meta-box-nonce5"]) || !wp_verify_nonce($_POST["meta-box-nonce5"], basename(__FILE__))) &&
        (!isset($_POST["meta-box-nonce6"]) || !wp_verify_nonce($_POST["meta-box-nonce6"], basename(__FILE__))) &&
        (!isset($_POST["meta-box-nonce7"]) || !wp_verify_nonce($_POST["meta-box-nonce7"], basename(__FILE__)))) {
        return $post_id;
    }

    if (!current_user_can("edit_post", $post_id)) {
        return $post_id;
    }

    if (defined("DOING_AUTOSAVE") && DOING_AUTOSAVE) {
        return $post_id;
    }

    $slug = "spb";
    if ($slug != $post->post_type) {
        return $post_id;
    }

    $meta_popup_effect_value = "";
    $meta_popup_trigger_value = "";
    $meta_popup_delay_value = "";
    $meta_popup_scroll_trigger_value = "";
    $meta_popup_scroll_value = "";
    $meta_bcg_color_value = "";
    $meta_conntent_padding_tb_value = "";
    $meta_conntent_padding_lr_value = "";
    $meta_conntent_box_shadow_color = "";
    $meta_conntent_box_shadow_opacity = "";
    $meta_conntent_box_shadow_spread = "";
    $meta_conntent_box_shadow_spread_horizontal = "";
    $meta_conntent_box_shadow_spread_vertical = "";
    $meta_set_cookie_value = "";
    $meta_cookie_value = "";
    $meta_overlay_color_value = "";
    $meta_overlay_opacity_value = "";
    $meta_button_color_value = "";
    $meta_button_hover_color_value = "";
    $meta_button_text_value = "";
    $meta_button_text_color_value = "";

    if (isset($_POST["spb_popup_effect"])) {
        $meta_popup_effect_value = $_POST["spb_popup_effect"];

        update_post_meta($post_id, "spb_popup_effect", $meta_popup_effect_value);
    }

    if (isset($_POST["spb_popup_exclude"])) {
        $meta_popup_exclude_value = $_POST["spb_popup_exclude"];
        $old_meta = get_post_meta($post->ID, '_spb_popup_exclude', true);

        if (!empty($old_meta)) {
            update_post_meta($post->ID, '_spb_popup_exclude', $meta_popup_exclude_value);
        } else {
            add_post_meta($post->ID, '_spb_popup_exclude', $meta_popup_exclude_value, true);
        }
    }

    if (isset($_POST["spb_popup_trigger"]) && (isset($_POST["spb_popup_delay_value"]) || isset($_POST["spb_popup_scroll_value"]))) {

        $meta_popup_trigger_value = $_POST["spb_popup_trigger"];
        $meta_popup_delay_value = $_POST["spb_popup_delay_value"];
        $meta_popup_scroll_value = $_POST["spb_popup_scroll_value"];

        if ($meta_popup_trigger_value == "none") {
            update_post_meta($post_id, "spb_popup_trigger", $meta_popup_trigger_value);
            update_post_meta($post_id, "spb_popup_delay_value", "");
            update_post_meta($post_id, "spb_popup_scroll_value", "");
        }
        if ($meta_popup_trigger_value == "exit_intent") {
            update_post_meta($post_id, "spb_popup_trigger", $meta_popup_trigger_value);
            update_post_meta($post_id, "spb_popup_delay_value", "");
            update_post_meta($post_id, "spb_popup_scroll_value", "");
        }
        if ($meta_popup_trigger_value == "time") {
            update_post_meta($post_id, "spb_popup_trigger", $meta_popup_trigger_value);
            update_post_meta($post_id, "spb_popup_delay_value", $meta_popup_delay_value);
            update_post_meta($post_id, "spb_popup_scroll_value", "");
        }
        if ($meta_popup_trigger_value == "scroll") {
            update_post_meta($post_id, "spb_popup_trigger", $meta_popup_trigger_value);
            update_post_meta($post_id, "spb_popup_delay_value", "");
            update_post_meta($post_id, "spb_popup_scroll_value", $meta_popup_scroll_value);
        }
    }

    if (isset($_POST["spb_cookie_value"])) {
        $meta_cookie_value = $_POST["spb_cookie_value"];

        if (isset($_POST["spb_set_cookie"]) && $_POST["spb_set_cookie"] == "set") {
            update_post_meta($post_id, "spb_set_cookie", $_POST["spb_set_cookie"]);
            update_post_meta($post_id, "spb_cookie_value", $meta_cookie_value);
        } else {
            update_post_meta($post_id, "spb_set_cookie", "");
            update_post_meta($post_id, "spb_cookie_value", "");
        }
    }

    if (isset($_POST["spb_bcg_color"]) &&
        isset($_POST["spb_content_padding_tb"]) &&
        isset($_POST["spb_content_padding_lr"]) &&
        isset($_POST["spb_content_box_shadow_color"]) &&
        isset($_POST["spb_content_box_shadow_opacity"]) &&
        isset($_POST["spb_content_box_shadow_vertical"]) &&
        isset($_POST["spb_content_box_shadow_horizontal"]) &&
        isset($_POST["spb_content_box_shadow_spread"])) {

        $meta_bcg_color_value = $_POST["spb_bcg_color"];
        $meta_conntent_padding_tb_value = $_POST["spb_content_padding_tb"];
        $meta_conntent_padding_lr_value = $_POST["spb_content_padding_lr"];

        $meta_conntent_box_shadow_color = $_POST["spb_content_box_shadow_color"];
        $meta_conntent_box_shadow_opacity = $_POST["spb_content_box_shadow_opacity"];
        $meta_conntent_box_shadow_spread = $_POST["spb_content_box_shadow_vertical"];
        $meta_conntent_box_shadow_spread_horizontal = $_POST["spb_content_box_shadow_horizontal"];
        $meta_conntent_box_shadow_spread_vertical = $_POST["spb_content_box_shadow_spread"];

        update_post_meta($post_id, "spb_bcg_color", $meta_bcg_color_value);
        update_post_meta($post_id, "spb_content_padding_tb", $meta_conntent_padding_tb_value);
        update_post_meta($post_id, "spb_content_padding_lr", $meta_conntent_padding_lr_value);

        update_post_meta($post_id, "spb_content_box_shadow_color", $meta_conntent_box_shadow_color);
        update_post_meta($post_id, "spb_content_box_shadow_opacity", $meta_conntent_box_shadow_opacity);
        update_post_meta($post_id, "spb_content_box_shadow_vertical", $meta_conntent_box_shadow_spread);
        update_post_meta($post_id, "spb_content_box_shadow_horizontal", $meta_conntent_box_shadow_spread_horizontal);
        update_post_meta($post_id, "spb_content_box_shadow_spread", $meta_conntent_box_shadow_spread_vertical);
    }

    if (isset($_POST["spb_overlay_color"]) && isset($_POST["spb_overlay_opacity"])) {
        $meta_overlay_color_value = $_POST["spb_overlay_color"];
        $meta_overlay_opacity_value = $_POST["spb_overlay_opacity"];

        update_post_meta($post_id, "spb_overlay_color", $meta_overlay_color_value);
        update_post_meta($post_id, "spb_overlay_opacity", $meta_overlay_opacity_value);
    }

    if (isset($_POST["spb_button_color"]) && isset($_POST["spb_button_hover_color"]) && isset($_POST["spb_button_text"]) && isset($_POST["spb_button_text_color"])) {
        $meta_button_color_value = $_POST["spb_button_color"];
        $meta_button_hover_color_value = $_POST["spb_button_hover_color"];
        $meta_button_text_value = $_POST["spb_button_text"];
        $meta_button_text_color_value = $_POST["spb_button_text_color"];

        update_post_meta($post_id, "spb_button_color", $meta_button_color_value);
        update_post_meta($post_id, "spb_button_hover_color", $meta_button_hover_color_value);
        update_post_meta($post_id, "spb_button_text", $meta_button_text_value);
        update_post_meta($post_id, "spb_button_text_color", $meta_button_text_color_value);
    }

}

add_action("save_post", "save_settings_box", 10, 3);
