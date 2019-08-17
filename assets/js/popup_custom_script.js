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


   jQuery("#scroll-trigger").click(function(){
      jQuery("#scroll-help").slideDown(200);     
      jQuery("#exit_intent-help").slideUp(200);
      jQuery("#click-help").slideUp(200);
   });

   jQuery("#exit-trigger").click(function(){
      jQuery("#exit_intent-help").slideDown(200);   
      jQuery("#scroll-help").slideUp(200);
      jQuery("#click-help").slideUp(200);
   });

   jQuery("#delay-trigger").click(function(){
      jQuery("#scroll-help").slideUp(200);
      jQuery("#exit_intent-help").slideUp(200);
      jQuery("#click-help").slideUp(200);
   });

   jQuery("#no-trigger").click(function(){
      jQuery("#click-help").slideDown(200);     
      jQuery("#scroll-help").slideUp(200);
      jQuery("#exit_intent-help").slideUp(200);
   });

});