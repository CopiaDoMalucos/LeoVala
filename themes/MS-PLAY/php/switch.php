<?php 

    if ( isset( $_GET["switch"] ) )
	{
	     switch( $_GET["switch"])
		 {
		     case "small":
			      setcookie("fontswitcher", "small", time() + 604800, "/"); 
			 break;
			 
			 case "medium":
			      setcookie("fontswitcher", "medium", time() + 604800, "/");
		     break;
			 
			 case "large":
			      setcookie("fontswitcher", "large", time() + 604800, "/");
		     break;
			 
			 case "98%":
	              setcookie("widthswitcher", "98%", time() + 604800, "/");		 
			 break;
			 
			 case "1024px";		  
			      setcookie("widthswitcher", "1024px", time() + 604800, "/");
		     break;
			 
			 case "rcol":
			      setcookie("columswitcher", "rcol", time() + 604800, "/");
			 break;
			 
			 case "lcol":
			      setcookie("columswitcher", "lcol", time() + 604800, "/");
			 break;
			 
			 case "left":
			      setcookie("blockswitcher", "left", time() + 604800, "/");
			 break;
			 
			 case "right":
			      setcookie("blockswitcher", "right", time() + 604800, "/");
			 break;
			 
			 case "red":
		          setcookie("colorswitcher", "red", time() + 604800, "/");	 
			 break;
		
			 case "blue":
		          setcookie("colorswitcher", "blue", time() + 604800, "/");	
			 break;
			 
			 case "pink":
		          setcookie("colorswitcher", "pink", time() + 604800, "/");	
			 break;
			 
			 case "green":
		          setcookie("colorswitcher", "green", time() + 604800, "/");	
			 break;
	     }
	}  
	
	header("Location: ".$_SERVER['HTTP_REFERER']);
?>