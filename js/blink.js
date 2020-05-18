if ( document.all ) { 

    function blink_show() { 
        blink_tags = document.all.tags('blink'); 
        blink_count = blink_tags.length; 
        for ( i = 0; i < blink_count; i++ ) { 
            blink_tags[i].style.visibility = 'visible'; 
        } 
        window.setTimeout( 'blink_hide()', 700 ); 
    } 

    function blink_hide() { 
        blink_tags = document.all.tags('blink'); 
        blink_count = blink_tags.length; 
        for ( i = 0; i < blink_count; i++ ) { 
            blink_tags[i].style.visibility = 'hidden'; 
        } 
        window.setTimeout( 'blink_show()', 250 ); 
    } 

    window.onload = blink_show; 

}