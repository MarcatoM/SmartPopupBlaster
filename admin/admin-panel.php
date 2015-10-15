<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action('admin_menu', 'spb');
 
function spb(){
        // add_menu_page( 'Smart PopUp Blaster', 'Smart PopUp Blaster', 'manage_options', 's-popup-b', 'spb_init','dashicons-megaphone',30 );
        add_submenu_page( 'edit.php?post_type=spb', 'Help', 'Help', 'manage_options', 'submenu-handle', 'sub_spb_init');

}
/* 
function spb_init(){
        if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		echo '<div class="wrap">';
		echo '<h1>Here is where the form would go if I actually had options.</h1>';
		echo '</div>';
}
*/
// sub_spb_init() displays the page content for the Test settings submenu
function sub_spb_init($object) {

    echo '<div class="wrap">';
    echo '<h1>Help Section...</h1>';
    echo '</div>';

  /*

    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    // variables for the field and option names
    $hidden_field_name = 'spb_submit_hidden'; 

    $opt_name_color = 'spb_favorite_color';    
    $data_field_name_color = 'spb_favorite_color';

    $opt_name_effect = 'spb_effect';    
    $data_field_name_effect = 'spb_effect';

    // Read in existing option value from database
    $opt_val_color = get_option( $opt_name_color, '' );
    $opt_val_effect = get_option( $opt_name_effect, 'bounce' );

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value         
        $opt_val_color = $_POST[ $data_field_name_color ];
        $opt_val_effect = $_POST[ $data_field_name_effect ];

        // Save the posted value in the database
        update_option( $opt_name_color, $opt_val_color );
        update_option( $opt_name_effect, $opt_val_effect );

        // Put a "settings saved" message on the screen
?>
<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Menu Test Plugin Settings', 'menu-test' ) . "</h2>";

    // settings form
    
    ?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">


<p><?php _e("Favorite Color: ", 'menu-test' ); ?> 
<input type="color" name="<?php echo $data_field_name_color; ?>" value="<?php echo $opt_val_color; ?>" size="20">
</p><hr />

<p><?php _e("PopUp Effect: ", 'menu-test' ); ?> 
<select name="<?php echo $data_field_name_effect; ?>">
  <option value="bounce" <?php if($opt_val_effect == "bounce"){echo "selected";} ?>>Bounce</option>
  <option value="flash" <?php if($opt_val_effect == "flash"){echo "selected";} ?>>Flash</option>
  <option value="pulse" <?php if($opt_val_effect == "pulse"){echo "selected";} ?>>Pulse</option>
  <option value="shake" <?php if($opt_val_effect == "shake"){echo "selected";} ?>>Shake</option>
  <option value="swing" <?php if($opt_val_effect == "swing"){echo "selected";} ?>>Swing</option>
  <option value="tada" <?php if($opt_val_effect == "tada"){echo "selected";} ?>>Tada</option>
  <option value="wobble" <?php if($opt_val_effect == "wobble"){echo "selected";} ?>>Wobble</option>
  <option value="bounceIn" <?php if($opt_val_effect == "bounceIn"){echo "selected";} ?>>Bounce In</option>
  
</select>
</p><hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>

<?php

	$args = array(
	  'post_type' 			=> 'spb',
	  'post_status' 		=> 'publish',
	  'posts_per_page'  	=> 1
	  );
	$loop = new WP_Query( $args );
	while ( $loop->have_posts() ) : $loop->the_post();
	// var_dump(get_post_meta(the_ID(), 'spb_popup_effect', true));
	// echo get_post_meta(get_the_ID(), 'spb_popup_effect', true);
	//var_dump(the_ID());
	//get_post_meta(the_ID(), 'spb_popup_effect', true); 
	//var_dump(get_post_meta(32, 'spb_popup_effect', true));

	$show_exclude = get_post_meta(get_the_ID(), "_spb_popup_exclude", true);
	$show_exclude_homepage = in_array('Exclude on Home Page', $show_exclude);

	var_dump($show_exclude_homepage);
 
	endwhile;


  */
}

?>