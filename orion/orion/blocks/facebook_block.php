<?php
############################################################
#######                                             ########
#######                                             ########
#######           brshares.com 2.0                  ########
#######                                             ########
#######                                             ########
############################################################ 
if ($CURUSER){ 
begin_blockl("Facebook");
?>

<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fbr2brasil&amp;width=170&amp;height=500&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:170px; height:500px;" allowTransparency="true"></iframe>
<?php

end_blockl(); 
}
?>