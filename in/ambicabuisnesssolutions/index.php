<?php
@ob_start();
@session_start();

error_reporting (E_ALL ^ E_NOTICE);
require_once("../../includes/GPSFunction.php");

$url = explode("/",dirname($_SERVER['PHP_SELF']));
$fold = $url[count($url)-1];

//	DB Connection
require("../../includes/config.inc.php"); 
require("../../includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

$getUserInfo = "SELECT * FROM tb_clientinfo";
$resUserInfo = $db->query($getUserInfo);

if($db->affected_rows > 0){
	while($recordUserInfo = $db->fetch_array($resUserInfo))
	{
	
		$clientName = strtolower(str_replace(" ","",$recordUserInfo[ci_clientName]));
		if($clientName == $fold)
		{
			$clientOrgName = $recordUserInfo[ci_clientName];
			$clientLogo = $recordUserInfo[ci_clientLogo];
			$clientContent = $recordUserInfo[ci_aboutUs];
			$clientFooter = $recordUserInfo[ci_footerText];
		}
	}

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GPS Application</title>
<link href="../../css/style.css" rel="stylesheet" type="text/css" />
<link media="screen" rel="stylesheet" href="../../css/colorbox.css" />
<script type="text/javascript" src="../../js/ajax.js"></script>
<script type="text/javascript" src="../../js/loginValid.js"></script>
<script type="text/javascript" src="../../js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../../js/jquery.colorbox.js"></script>
<script>
	$(document).ready(function(){
		
		$(".login_frm").colorbox();
		
	});
</script>

<script type="text/javascript">

$(document).ready(function(){
	
  //hide toolbar and make visible the 'show' button
	$("span.downarr a").click(function() {
    $("#toolbar").slideToggle("fast");
    $("#toolbarbut").fadeIn("slow");    
  });
  
  //show toolbar and hide the 'show' button
  $("span.showbar a").click(function() {
    $("#toolbar").slideToggle("fast");
    $("#toolbarbut").fadeOut();    
  });
  
  //show tooltip when the mouse is moved over a list element 
  $("ul#social li").hover(function() {
		$(this).find("div").fadeIn("fast").show(); //add 'show()'' for IE
    $(this).mouseleave(function () { //hide tooltip when the mouse moves off of the element
        $(this).find("div").hide();
    });
  });
  
  //don't jump to #id link anchor 
  $(".facebook, .twitter, .delicious, .digg, .rss, .stumble, .menutit, span.downarr a, span.showbar a").click(function() {
   return false;                                         
	});
	
  //show quick menu on click 
	$("span.menu_title a").click(function() {
		if($(".quickmenu").is(':hidden')){ //if quick menu isn't visible 
			$(".quickmenu").fadeIn("fast"); //show menu on click
		}
		else if ($(".quickmenu").is(':visible')) { //if quick menu is visible 
      $(".quickmenu").fadeOut("fast"); //hide menu on click
    }
	});
	
	//hide menu on casual click on the page
	$(document).click(function() {
			$(".quickmenu").fadeOut("fast");
			$(".quickmenu").css({'vivibility': 'hidden'});
	});
	$('.quickmenu').click(function(event) { 
		event.stopPropagation(); //use .stopPropagation() method to avoid the closing of quick menu panel clicking on its elements 
	});

		
});
	
</script>
</head>
<body>
<div class="wrapper"><!--wrapper div start here -->

    <div class="introduce"><!-- introduce div START here-->
	<span class="version_top">v 1.0</span>
    	<div class="logoplace"><img src="../../modules/user/client_logo/<?php echo $clientLogo;?>" width="168" height="53" /></div>
    	<h3>Spot It! &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Track Me!&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Manage Me!</h3>
        <p class="text"> <?php echo $clientContent;?>
        <br /></p><br /><br />
    	<p><a href="login.php" class="login_frm"><img src="../../images/view.png" width="99" height="54"  align="right"/></a></p>
    
    
    </div><!-- introduce div end here-->


<div id="toolbarbut"> <!-- hide button -->
<span class="showbar"><a href="#">show bar</a></span>
</div><!-- hide button END-->

<div id="toolbar"> <!-- toolbar container -->

<div class="leftside"> <!-- all things in floating left side -->
  <ul id="social">
    
    <li><a href="login.php" id="facebook" class="login_frm"></a></li>
    <li><a class="twitter" href="#"></a>

  </ul>
</div>

    <div class="rightside"> <!-- all things in floating left side -->
          <span class="downarr"> <!-- hide button -->
          <a href="#"></a>
          </span>
          <span class="menu_title">
            <a href="#"><?php echo $clientFooter;?></a> <!-- quick menu title -->
          </span>
    </div>

</div><!-- toolbar container END-->

</div><!--wrapper div END here -->
</body>
</html>
