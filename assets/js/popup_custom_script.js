jQuery(document).ready(function() {  

   jQuery( "#delay_slider" ).slider({
   		range: "seconds",
   		value: delay_value,
   		step: 1,
   		min: 0,
   		max: 120,
   		slide: function(event, ui){
   			jQuery("#delay_slider_value").val(ui.value);
   		}
   });

   jQuery("#delay_slider_value").change(function () {
    var value = this.value.substring(1);
    console.log(value);
    jQuery("#delay_slider").slider("value", parseInt(value));
   });

   jQuery( "#scroll_slider" ).slider({
      range: "percentage",
      value: scroll_value,
      step: 1,
      min: 0,
      max: 100,
      slide: function(event, ui){
        jQuery("#scroll_slider_value").val(ui.value);
      }
   });

   jQuery("#scroll_slider_value").change(function () {
    var value = this.value.substring(1);
    console.log(value);
    jQuery("#scroll_slider").slider("value", parseInt(value));
   });


   jQuery( "#cookie_slider" ).slider({
      range: "days",
      value: cookie_value,
      step: 1,
      min: 0,
      max: 365,
      slide: function(event, ui){
        jQuery("#cookie_slider_value").val(ui.value);
      }
   });

   jQuery("#cookie_slider_value").change(function () {
    var value = this.value.substring(1);
    console.log(value);
    jQuery("#cookie_slider").slider("value", parseInt(value));
   });

/*
   jQuery('#colorpicker').farbtastic('#color');
   jQuery('#overlay_colorpicker').farbtastic('#overlay_color');
   jQuery('#button_colorpicker').farbtastic('#button_color');
   jQuery('#button_hover_colorpicker').farbtastic('#button_color_hover');
   jQuery('#button_text_colorpicker').farbtastic('#button_text_color');
*/
 
});