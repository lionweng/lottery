<?php
include_once("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Christmas Night - free css template</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="templatemo_style.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="ddsmoothmenu.css" />

<script type="text/javascript" src="js/jquery.min.js"></script>
</head>
<body>

<div id="templatemo_wrapper_outter">
<div id="templatemo_wrapper_inner">
<div id="templatemo_wrapper">

	<div id="templatemo_menu" class="ddsmoothmenu">
        <ul>
            <li><a href="index.php" class="selected">Members</a></li>
            <li><a href="gifts.php">Gifts</a></li>
        </ul>
        <br style="clear: left" />
    </div>
    
    <div id="templatemo_header">
    	<div id="site_title"><h1>>>>>>>>>>Members</h1></div>
	</div> <!-- end of header -->

    <div id="templatemo_main">
        <div class="col_fw">
            <?php
            $sql = 'SELECT * FROM members WHERE status = :Enable';
            $rs = $pdo->prepare($sql);
            $rs->execute([':Enable' => '1']);
            $users = $rs->fetchAll(PDO::FETCH_ASSOC);
            foreach($users as $user){
            ?>
      		<div class="col_allw300 fp_service_box">
                <img src="images/icon-05.png" alt="image" />
                <h3><?php echo $user['name']; ?></h3>
            </div>
            <?php
            }
            ?>
            
            <div class="cleaner h50"></div>
            
            <div class="cleaner"></div>
            
        </div>
        
    </div> <!-- end of templatemo main -->
    <div id="templatemo_main_bottom"></div>
    
    <div id="templatemo_bottom">
     	<div class="col_allw300">
        	<h4>Pages</h4>
            <ul class="bottom_list">
                <li><a href="index.php" class="selected">Members</a></li>
                <li><a href="gifts.php">Gifts</a></li>
			</ul>
        </div>
    	<div class="cleaner"></div>
    </div>    
    
    <div id="templatemo_footer">
        Copyright © 2022 <a href="#">ICPSI</a>
        <div class="cleaner"></div>
    </div>
	
</div>
<div class="cleaner"></div>    
</div>	
</div>
</body>
</html>