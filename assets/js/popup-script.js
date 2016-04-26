jQuery(document).ready(function(){

// function to show our popups
var whichpopup_id;
function showPopup(whichpopup, effect){   
    whichpopup_id = whichpopup;
    var docHeight = jQuery(document).height(); //grab the height of the page
    // var scrollTop = jQuery(window).scrollTop(); //grab the px value from the top of the page to where you're scrolling
    var windowHeight = jQuery(window).height(); //grab the height of the browser window
    var popUpHeight = jQuery('.overlay-content').height(); //grab the height of the popUp        
    jQuery('.overlay-bg-'+whichpopup).show().css({'height' : docHeight}); //display your popup background and set height to the page height
    jQuery('.spb-popup-class-'+whichpopup).show(); //.css({'top': scrollTop+100+'px'}); //show the appropriate popup and set the content 20px from the window top    
    if (popUpHeight >= windowHeight) {
    	jQuery('.overlay-content').css({'position': 'absolute', "top": Math.max(0, ((jQuery(window).height() - jQuery(this).outerHeight()) / 2) + jQuery(window).scrollTop()) + "px"});        
    }
    jQuery('.spb-popup-class-'+whichpopup).addClass(effect);
}
// function to close our popups
var post_id;
var d_id;

function closePopup(id){ 
    jQuery('.overlay-bg-'+id+', .overlay-content-'+id).hide(); //hide the overlay     
}

function on_exit_intent(id, fx){
    d_id = id;
    var fired_e = 0;
    setTimeout(function(){
    document.addEventListener("mouseleave", function(e){
        if( e.clientY < 0 && fired_e === 0){            
            showPopup(id, fx);
            fired_e = 1;                        
        }
    }, false);
    },4000);

}

function on_delay(id, fx, s){
    setTimeout(function() {
        d_id = id;
        showPopup(id, fx);
    }, s); 
}

function on_scroll(id, fx, scroll){
    var fired = 0;  
    var docHeight = jQuery(document).height();       
    var percentage = scroll / 100;
            
    var num = parseFloat(docHeight);        
    var val = num - (num * percentage);  

    jQuery(window).scroll(function(){ 
    var topOfWindow = jQuery(window).scrollTop();       
        if(fired == 0){
            if(topOfWindow >= val){
                d_id = id;
                showPopup(id, fx);   
                fired = 1;         
            }
        }
        
    });     
}

// hide popup when user clicks on close button or if user clicks anywhere outside the container
jQuery('.overlay-bg').click(function(){
    var popup_id = jQuery(this).data("id");
    closePopup(popup_id);
});
jQuery('.close-btn').click(function(){
    var close_id = jQuery(this).data("id");
    closePopup(close_id);
}); 
 
// hide the popup when user presses the esc key
jQuery(document).keyup(function(e) {
    if (e.keyCode == 27) { // if user presses esc key
        closePopup(whichpopup_id);
    }
});


jQuery( document ).on( 'click', '.show-popup', function() {
	post_id = jQuery(this).data("id");	
	
	jQuery.ajax({
		url : cookieAjax.ajax_url,
		type : 'post',
		data : {
			action : 'popup_effect',
			the_post_id : post_id
		},
		success : function( response ) {						
			//var selectedPopup = jQuery(this).data('showpopup'); //get the corresponding popup to show 
            showPopup(post_id, response[1]);		 
		}

	});

	return false;
});




var popup_data = []; 
var popup_data_e = []; 
var popup_data_s = []; 

var spb_exit_intent = document.getElementsByClassName('spb-exit_intent');

var spb_delay = document.getElementsByClassName('spb-delay');
var spb_scroll = document.getElementsByClassName('spb-scroll');  


function popup_on_exit_intent(stop,exclude){
    for (var i = 0; i < spb_exit_intent.length; i++) {
        var each = spb_exit_intent[i];
        var d_id = each.getAttribute("data-id");
        var effect = each.getAttribute("data-effect");        
        var cookie_value = Number(each.getAttribute("data-cookie"));    
                      
        var c_d = [d_id, effect, cookie_value];        
        popup_data_e.push(c_d);
    };

    for(var y = 0; y < popup_data_e.length; y++){
        if( popup_data_e[y][0] != ""){             
            if(stop === "stop"){
                return;
            } else {
                if (exclude == popup_data_e[y][0]){
                    continue;
                } else {
                   on_exit_intent(popup_data_e[y][0], popup_data_e[y][1]);
                }                                 
            }                                                 
        }
    };    
}


function popup_on_scroll(stop,exclude){      

    for (var i = 0; i < spb_scroll.length; i++) {
        var each = spb_scroll[i];
        var d_id = each.getAttribute("data-id");
        var effect = each.getAttribute("data-effect");
        var scroll = Number(each.getAttribute("data-scroll"));
        var cookie_value = Number(each.getAttribute("data-cookie"));    
                      
        var c_d = [d_id, effect, scroll, cookie_value];        
        popup_data_s.push(c_d);
    };

    for(var y = 0; y < popup_data_s.length; y++){
        if( popup_data_s[y][2] != ""){             
            if(stop === "stop"){
                return;
            } else {
                if (exclude == popup_data_s[y][0]){
                    continue;
                } else {                   
                   on_scroll(popup_data_s[y][0], popup_data_s[y][1], popup_data_s[y][2]);
                }                                 
            }                                                 
        }
    };
}

function popup_on_delay(stop,exclude){      

    for (var i = 0; i < spb_delay.length; i++) {
        var each = spb_delay[i];
        var d_id = each.getAttribute("data-id");
        var effect = each.getAttribute("data-effect");
        var delay = Number(each.getAttribute("data-delay") * 1000);
        var cookie_value = Number(each.getAttribute("data-cookie"));    
                      
        var c_d = [d_id, effect, delay, cookie_value];        
        popup_data.push(c_d);
    };

    for(var y = 0; y < popup_data.length; y++){
        if( popup_data[y][2] != ""){             
            if(stop === "stop"){
                return;
            } else {
                if (exclude == popup_data[y][0]){
                    continue;
                } else {
                   on_delay(popup_data[y][0], popup_data[y][1], popup_data[y][2]);
                }                                 
            }                                                 
        }
    };
}


checkCookie_e();
checkCookie_d();
checkCookie_s();


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires+"; path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}


function checkCookie_e() {

  if(spb_exit_intent.length > 0){ 
    popup_on_exit_intent("stop");
    for(var d = 0; d < popup_data_e.length; d++){ 
        var cookie = getCookie("spb-popup"+popup_data_e[d][0]);       
        var exclude_popup;
        if (cookie) { 
            exclude_popup = popup_data_e[d][0];
            continue;            
        } else {
            popup_on_exit_intent("go", exclude_popup);
            for(var i = 0; i < popup_data_e.length; i++){                 
                seen = "Seen_PopUp_"+popup_data_e[i][0];
                if (popup_data_e[i][2] == 1.1) {
                    setCookie("spb-popup"+popup_data_e[i][0], seen, 0.00001);
                } else {                    
                    setCookie("spb-popup"+popup_data_e[i][0], seen, popup_data_e[i][2]);
                }
            }               
        } 
    } 
  }else {   
        return;
    }

}


function checkCookie_d() {

  if(spb_delay.length > 0){ 
    popup_on_delay("stop");
    for(var d = 0; d < popup_data.length; d++){ 
        var cookie = getCookie("spb-popup"+popup_data[d][0]);       
        var exclude_popup;
        if (cookie) { 
            exclude_popup = popup_data[d][0];
            continue;            
        } else {
            popup_on_delay("go", exclude_popup);
            for(var i = 0; i < popup_data.length; i++){                 
                seen = "Seen_PopUp_"+popup_data[i][0];
                if (popup_data[i][3] == 1.1) {
                    setCookie("spb-popup"+popup_data[i][0], seen, 0.00001);
                } else {                    
                    setCookie("spb-popup"+popup_data[i][0], seen, popup_data[i][3]);
                }
            }               
        } 
    } 
  }else {   
        return;
    }

}


function checkCookie_s() {

  if(spb_scroll.length > 0){
    popup_on_scroll("stop");
        for(var d = 0; d < popup_data_s.length; d++){ 
            var cookie = getCookie("spb-popup"+popup_data_s[d][0]);       
            var exclude_popup;
            if (cookie) { 
                exclude_popup = popup_data_s[d][0];
                continue;            
            } else {
                popup_on_scroll("go", exclude_popup);
                for(var i = 0; i < popup_data_s.length; i++){                 
                    seen = "Seen_PopUp_"+popup_data_s[i][0];
                    if (popup_data_s[i][3] == 1.1) {
                        setCookie("spb-popup"+popup_data_s[i][0], seen, 0.000001);
                    } else {                    
                        setCookie("spb-popup"+popup_data_s[i][0], seen, popup_data_s[i][3]);
                    }
                }               
            } 
        }
  } else {   
        return;
    }

}

});