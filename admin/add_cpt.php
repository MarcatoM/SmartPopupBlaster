<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'init', 'spb_post_types' );
function spb_post_types() {

  $labels = array(
    'name'                => __( 'Smart PopUp Blaster', 'mahat' ),
    'singular_name'       => __( 'Smart PopUp Blaster', 'mahat' ),
    'add_new'             => __( 'Add New PopUp', 'mahat' ),
    'add_new_item'        => __( 'Add New PopUp', 'mahat' ),
    'edit_item'           => __( 'Edit PopUp', 'mahat' ),
    'new_item'            => __( 'New PopUp', 'mahat' ),
    'all_items'           => __( 'All PopUps', 'mahat' ),
    'view_item'           => __( 'View PopUp', 'mahat' ),
    'search_items'        => __( 'Search PopUps', 'mahat' ),
    'not_found'           => __( 'No PopUp found', 'mahat' ),
    'not_found_in_trash'  => __( 'No PopUp found in Trash', 'mahat' ),
    'menu_name'           => __( 'Smart PopUp Blaster', 'mahat' ),
  );

  $supports = array( 'title', 'editor', 'revisions', 'author' );  

  $args = array(
    'labels'              => $labels,
    'show_ui'             => true,
    'query_var'           => false,
    'rewrite'             => array( 'slug' => 'spb' ),
    'capability_type'     => 'post',
    'menu_position'       => 30,    
    'menu_icon'           => 'dashicons-megaphone',
    'supports'            => $supports,
  );

  register_post_type( 'spb', $args );
}

//manage the columns of the `page` post type
function manage_columns_for_spb($columns){
    //add new columns
    $columns['popup_id'] = 'PopUp ID';
    $columns['popup_data_id'] = 'Data ID';
    return $columns;
}
add_action('manage_spb_posts_columns','manage_columns_for_spb');

//Populate custom columns for `page` post type
function populate_page_columns($column,$post_id){

    //page content column
    if($column == 'popup_id'){       
        $the_id = get_the_ID();   
        echo '<pre><code>spb-popup-'.$the_id.'</code><pre>';       
    }
    if($column == 'popup_data_id'){       
        $the_id = get_the_ID();           
        echo '<pre><code>data-id="'.$the_id.'"</code><pre>';     
    }
}
add_action('manage_spb_posts_custom_column','populate_page_columns',10,2);



/* Meta Boxes */

function settings_box($object){
      wp_nonce_field(basename(__FILE__), "meta-box-nonce1");
    ?>
    <div> 

      <label for="spb_popup_effect"><?php _e("PopUp Effect: ", 'popup_settings' ); ?></label>
      <select name="spb_popup_effect">
          <?php 
              $option_values = array('bounce', 'flash', 'pulse', 'shake', 'swing', 'tada', 'wobble', 'bounceIn' );
              foreach($option_values as $key => $value){
                  if($value == get_post_meta($object->ID, "spb_popup_effect", true)){
                      ?>
                          <option selected><?php echo $value; ?></option>
                      <?php    
                  }else{
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

function show_on_box($object){
      wp_nonce_field(basename(__FILE__), "meta-box-nonce2");
    ?>
    <div>      

      <?php 
        $show_exclude = get_post_meta($object->ID, "_spb_popup_exclude", true);
        if (empty($show_exclude)) {
          $show_exclude = ['On Entire Site'];
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

function trigger_box($object){
    wp_nonce_field(basename(__FILE__), "meta-box-nonce3");

    $popup_trigger = get_post_meta($object->ID, "spb_popup_trigger", true);
    $delay_value = get_post_meta($object->ID, "spb_popup_delay_value", true);
    $scroll_value = get_post_meta($object->ID, "spb_popup_scroll_value", true); 

    if($popup_trigger == ""){
      $popup_trigger = "none";
    }          
    ?>
    <script>
      var trigger_value = <?php echo json_encode($popup_trigger) ?>;
      var delay_value = <?php echo json_encode($delay_value) ?>;
      var scroll_value = <?php echo json_encode($scroll_value) ?>;
    </script>

    <table>
      <tr>
      <td>None: </td>
        <td><input type="radio" name="spb_popup_trigger" value="none" <?php if ($popup_trigger == "none"){echo "checked";} ?> /></td> 
      </tr>
      <tr>
        <td>Delay: </td>
        <td><input type="radio" name="spb_popup_trigger" value="time" <?php if ($popup_trigger == "time"){echo "checked";} ?> /></td>  
        <td><div id="delay_slider" style="width:90px; margin-left:10px;"></div></td>
        <td><input type="text" id="delay_slider_value" name="spb_popup_delay_value" value="<?php echo $delay_value; ?>" size="1" /></td>
        <td>sec</td>
      </tr>
      <tr>
        <td>Scroll: </td>
        <td><input type="radio" name="spb_popup_trigger" value="scroll" <?php if ($popup_trigger == "scroll"){echo "checked";} ?> /></td>  
        <td><div id="scroll_slider" style="width:90px; margin-left:10px;"></div></td>
        <td><input type="text" id="scroll_slider_value" name="spb_popup_scroll_value" value="<?php echo $scroll_value; ?>" size="1" /></td>
        <td>%</td>
      </tr>
    </table> 

    <?php
}

function bcg_color_box($object){
    wp_nonce_field(basename(__FILE__), "meta-box-nonce4");
    $bcg_color = get_post_meta($object->ID, "spb_bcg_color", true);
    if($bcg_color == ''){
      $bcg_color = '#fff';
    }      
    ?>
    <table>
      <tr>
        <td><h3>Content Background Color </h3></td>
        <td><input type="text" id="color" name="spb_bcg_color" value="<?php echo $bcg_color; ?>" /></td>
        <td><div id="colorpicker"></div></td>
      </tr>
    </table>
    <?php
}

function cookie_box($object){
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
        <th colspan="2" style="text-align:right;"><input type="checkbox" name="spb_set_cookie" value="set" <?php if ($set_cookie == "set"){echo "checked";} ?> /></th>        
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

function overlay_box($object){
    wp_nonce_field(basename(__FILE__), "meta-box-nonce6");
    $overlay_color = get_post_meta($object->ID, "spb_overlay_color", true);
    if($overlay_color == ''){
      $overlay_color = '#000';
    }      
    ?>
    <table>
      <tr>
        <td><h3>Overlay Background Color </h3></td>
        <td><input type="text" id="overlay_color" name="spb_overlay_color" value="<?php echo $overlay_color; ?>" /></td>
        <td><div id="overlay_colorpicker"></div></td>
        <td><h3>Overlay Background Opacity </h3></td>
        <td>
        <select name="spb_overlay_opacity">
          <?php 
              $opacity_value = get_post_meta($object->ID, "spb_overlay_opacity", true);
              if($opacity_value == ""){
                $opacity_value = 7;
              }
              for ($i=1; $i < 11; $i++) { 
                if($i == $opacity_value){
                      ?>
                          <option selected><?php echo $opacity_value; ?></option>
                      <?php    
                  }else{
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

function close_button_box($object){
    wp_nonce_field(basename(__FILE__), "meta-box-nonce7");
    $button_color = get_post_meta($object->ID, "spb_button_color", true);
    $button_color_hover = get_post_meta($object->ID, "spb_button_hover_color", true);
    $button_text = get_post_meta($object->ID, "spb_button_text", true);
    if($button_color == ''){
      $button_color = '#fff';
    }
    if($button_color_hover == ''){
      $button_color_hover = '#fff';
    }
    if($button_text == ''){
      $button_text = 'X';
    }      
    ?>
    <table>
      <tr>
        <td><h3>Close Button Color </h3></td>
        <td><input type="text" id="button_color" name="spb_button_color" value="<?php echo $button_color; ?>" /></td>
        <td><div id="button_colorpicker"></div></td>
        <td><h3>Close Button Hover Color </h3></td>
        <td><input type="text" id="button_color_hover" name="spb_button_hover_color" value="<?php echo $button_color_hover; ?>" /></td>
        <td><div id="button_hover_colorpicker"></div></td>
      </tr>
      <tr>
        <td><h3>Close Button Text </h3></td>
        <td><input type="text" name="spb_button_text" value="<?php echo $button_text; ?>" maxlength="10" /></td>
      </tr>
    </table>
    <?php  
}

function add_settings_box(){    
    add_meta_box("show_settings_box", "Targeting Conditions", "show_on_box", "spb", "side", "high", null);
    add_meta_box("effect_settings_box", "PopUp Effect", "settings_box", "spb", "side", "high", null);
    add_meta_box("trigger_settings_box", "PopUp Trigger", "trigger_box", "spb", "side", "high", null);
    add_meta_box("bcg_color_settings_box", "PopUp Background Color", "bcg_color_box", "spb", "normal", "high", null);
    add_meta_box("cookie_settings_box", "Set Cookie", "cookie_box", "spb", "side", "high", null);
    add_meta_box("overlay_settings_box", "Overlay Background", "overlay_box", "spb", "normal", "high", null);
    add_meta_box("close_button_settings_box", "Close Button", "close_button_box", "spb", "normal", "high", null);
}
add_action("add_meta_boxes", "add_settings_box");


function save_settings_box($post_id, $post, $update){
    if( (!isset($_POST["meta-box-nonce1"]) || !wp_verify_nonce($_POST["meta-box-nonce1"], basename(__FILE__))) && 
        (!isset($_POST["meta-box-nonce2"]) || !wp_verify_nonce($_POST["meta-box-nonce2"], basename(__FILE__))) && 
        (!isset($_POST["meta-box-nonce3"]) || !wp_verify_nonce($_POST["meta-box-nonce3"], basename(__FILE__))) && 
        (!isset($_POST["meta-box-nonce4"]) || !wp_verify_nonce($_POST["meta-box-nonce4"], basename(__FILE__))) && 
        (!isset($_POST["meta-box-nonce5"]) || !wp_verify_nonce($_POST["meta-box-nonce5"], basename(__FILE__))) && 
        (!isset($_POST["meta-box-nonce6"]) || !wp_verify_nonce($_POST["meta-box-nonce6"], basename(__FILE__))) && 
        (!isset($_POST["meta-box-nonce7"]) || !wp_verify_nonce($_POST["meta-box-nonce7"], basename(__FILE__))) ){
         return $post_id;
      }

    if(!current_user_can("edit_post", $post_id)){
        return $post_id;
      }

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE){
        return $post_id;
      }

    $slug = "spb";
    if($slug != $post->post_type){
        return $post_id;
      }


    $meta_popup_effect_value = ""; 
    $meta_popup_trigger_value = "";
    $meta_popup_delay_value = "";
    $meta_popup_scroll_trigger_value = "";
    $meta_popup_scroll_value = "";
    $meta_bcg_color_value = "";
    $meta_set_cookie_value = "";
    $meta_cookie_value = "";   
    $meta_overlay_color_value = ""; 
    $meta_overlay_opacity_value = "";

    $meta_button_color_value = "";
    $meta_button_hover_color_value = "";
    $meta_button_text_value = "";

    if(isset($_POST["spb_popup_effect"])){
        $meta_popup_effect_value = $_POST["spb_popup_effect"]; 

        update_post_meta($post_id, "spb_popup_effect", $meta_popup_effect_value);          
    }   
       


    if(isset($_POST["spb_popup_exclude"])){
        $meta_popup_exclude_value = $_POST["spb_popup_exclude"];
        $old_meta = get_post_meta($post->ID, '_spb_popup_exclude', true);

         if(!empty($old_meta)){
            update_post_meta($post->ID, '_spb_popup_exclude', $meta_popup_exclude_value);
            } else {
                add_post_meta($post->ID, '_spb_popup_exclude', $meta_popup_exclude_value, true);
            }
    } 

    if(isset($_POST["spb_popup_trigger"]) && (isset($_POST["spb_popup_delay_value"]) || isset($_POST["spb_popup_scroll_value"])) ){

        $meta_popup_trigger_value = $_POST["spb_popup_trigger"];          
        $meta_popup_delay_value = $_POST["spb_popup_delay_value"];
        $meta_popup_scroll_value = $_POST["spb_popup_scroll_value"]; 

        if ($meta_popup_trigger_value == "none") {
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

    if(isset($_POST["spb_cookie_value"])){
        $meta_set_cookie_value = $_POST["spb_set_cookie"];  
        $meta_cookie_value = $_POST["spb_cookie_value"]; 

        if($meta_set_cookie_value == "set"){
          update_post_meta($post_id, "spb_set_cookie", $meta_set_cookie_value); 
          update_post_meta($post_id, "spb_cookie_value", $meta_cookie_value); 
        } else {
          update_post_meta($post_id, "spb_set_cookie", ""); 
          update_post_meta($post_id, "spb_cookie_value", "");
        }       
    }   
    

    if(isset($_POST["spb_bcg_color"])){
        $meta_bcg_color_value = $_POST["spb_bcg_color"];  

        update_post_meta($post_id, "spb_bcg_color", $meta_bcg_color_value);     
    }

    if(isset($_POST["spb_overlay_color"]) && isset($_POST["spb_overlay_opacity"]) ){
        $meta_overlay_color_value = $_POST["spb_overlay_color"];
        $meta_overlay_opacity_value = $_POST["spb_overlay_opacity"];  

        update_post_meta($post_id, "spb_overlay_color", $meta_overlay_color_value);
        update_post_meta($post_id, "spb_overlay_opacity", $meta_overlay_opacity_value);     
    }

    if(isset($_POST["spb_button_color"]) && isset($_POST["spb_button_hover_color"]) && isset($_POST["spb_button_text"])){
        $meta_button_color_value = $_POST["spb_button_color"];
        $meta_button_hover_color_value = $_POST["spb_button_hover_color"];
        $meta_button_text_value = $_POST["spb_button_text"];

        update_post_meta($post_id, "spb_button_color", $meta_button_color_value);
        update_post_meta($post_id, "spb_button_hover_color", $meta_button_hover_color_value);
        update_post_meta($post_id, "spb_button_text", $meta_button_text_value);    
    }
       
}

add_action("save_post", "save_settings_box", 10, 3);

?>