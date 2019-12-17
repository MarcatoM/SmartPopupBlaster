<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
  exit;
}

add_action('admin_menu', 'spb');

function spb()
{
  // add_menu_page( 'Smart PopUp Blaster', 'Smart PopUp Blaster', 'manage_options', 's-popup-b', 'spb_init','dashicons-megaphone',30 );
  add_submenu_page('edit.php?post_type=spb', 'Help', 'Help', 'manage_options', 'submenu-handle', 'sub_spb_init');
}

// sub_spb_init() displays the page content for the Test settings submenu
function sub_spb_init($object)
{ ?>

  <div class="wraper">
    <h1>How to trigger popup on mouse click</zh1>
      <hr>

      <br>
      <p><strong>Button Shortcode:</strong></p>
      Add an id <code><strong>id="YOUR_ID"</strong></code> and button text <code><strong>text="BUTTON_TEXT"</strong></code>. Optionaly you can also add additional class <code><strong>class="SOME_CLASS"</strong></code>
      <p>
        <xmp style="background-color:yellow; display:inline; font-size:1.2em;">[spb-button id="YOUR_ID" text="BUTTON_TEXT"]</xmp>
      </p>
      <br>

      <p>
        <h3>If you want to trigger a popup when link, button or image are clicked, you'll need to copy popup class and data-id to certain element</h3>
      </p>

      <p>Add class <code><strong>show-popup</strong></code> and data-id <code><strong>data-id="1"</strong></code> to link, button or image tag</p>
      <p><strong>Note:</strong> class <code><strong>show-popup</strong></code> is always the same!</p>

      <br>
      <p><strong>Example for Links:</strong></p>
      <p>If your popup has data-id value of 1 then your link structure must be something like this</p>
      <p>
        <xmp style="background-color:yellow; display:inline; font-size:1.2em;"><a href="#" class="show-popup" data-id="1">Show PopUp on Click</a></xmp>
      </p>

      <br>
      <p><strong>Example for Buttons:</strong></p>
      <p>If your popup has data-id value of 10 then your button structure must be something like this</p>
      <p>
        <xmp style="background-color:yellow; display:inline; font-size:1.2em;"><button class="show-popup" data-id="10" value="">Show Popup</button></xmp>
      </p>

      <br>
      <p><strong>Example for Images:</strong></p>
      <p>If your popup has data-id value of 100 then your img structure must be something like this</p>
      <p>
        <xmp style="background-color:yellow; display:inline; font-size:1.2em;"><img src="#" class="show-popup" data-id="100" alt="" /></xmp>
      </p>

  </div>

<?php
}
