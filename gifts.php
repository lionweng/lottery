<?php
include_once("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Christmas Night</title>
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
                    <li><a href="index.php">Members</a></li>
                    <li><a href="gifts.php" class="selected">Gifts</a></li>
                </ul>
                <br style="clear: left" />
            </div>

            <div id="templatemo_header">
                <div id="site_title"><h1>>>>>>>>>>Gifts</h1></div>
            </div> <!-- end of header -->

            <div id="templatemo_main">
                <div class="col_fw">
            <?php
            if($_POST['pass'] == '20221211'){
                $gift_id = $_POST['gift_id'];
                if($gift_id == 'all'){
                    $update_sql = 'UPDATE records SET status=0';
                    $update_rs = $pdo->prepare($update_sql);
                    $update_rs->execute();
                }else{
                    $update_sql = 'UPDATE records SET status=0 WHERE gift_id=:GIFT_ID';
                    $update_rs = $pdo->prepare($update_sql);
                    $update_rs->execute([':GIFT_ID'=>$gift_id]);
                }
            }


            if($_POST['group']){
                $group = $_POST['group'];
            }else{
                $group = 1;
            }

            if(isset($_POST['edit1'])){
                $insert_sql = 'INSERT INTO gifts (groups, name, amount) VALUES (:GROUPS, :NAME, :AMOUNT)';
                $insert_rs = $pdo->prepare($insert_sql);
                try {
                    $insert_rs->execute([':GROUPS'=>$group, ':NAME' => $_POST['name'], ':AMOUNT' => $_POST['amount']]);
                }catch (Exception $e){
                    throw $e;
                }
            }
            $sql = 'SELECT * FROM gifts WHERE groups = :Groups AND status = :Enable';
            $rs = $pdo->prepare($sql);
            $rs->execute([':Groups' => $group, ':Enable' => '1']);
            $gifts = $rs->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <form action="gifts.php" method="post">
                群組：<select name="group">
                    <option <?php if($group==1) echo 'selected'; ?>>1</option>
                    <option <?php if($group==2) echo 'selected'; ?>>2</option>
                    <option <?php if($group==3) echo 'selected'; ?>>3</option>
                </select>
                &nbsp;&nbsp;&nbsp;<br>
                <input id="edit" type="checkbox" name="edit1" value="1" onchange="edit_form_control()"/>
                <label for="edit"> 新增</label>
                <div id="edit_form" style="display: none;">
                    名稱：<input name="name" type="text" size="30"/>&nbsp;&nbsp;&nbsp;數量：<input name="amount" type="text" size="10"/>
                </div>
                    &nbsp;&nbsp;&nbsp;<br><input type="submit" name="submit" value="OK" />
            </form>
                    <script type="text/javascript">
                        function edit_form_control(){
                            if($("#edit").is(":checked")){
                                $("#edit_form").show();
                            }else{
                                $("#edit_form").hide();
                            }
                        }
                    </script>
      		<table border="1" align="center">
                <thead>
                <th nowrap>
                    <h3>編號</h3>
                </th>
                <th>
                    <h3>名稱</h3>
                </th>
                <th nowrap>
                    <h3>數量</h3>
                </th>
                <th nowrap>
                    <h3>抽獎</h3>
                </th>
                </thead>
                <?php
                $i=0;
                foreach($gifts as $gift){
                    $i++;
                ?>
                <tr>
                    <td align="center">
                        <h3><?php echo $i; ?></h3>
                    </td>
                    <td align="center">
                        <h3><?php echo $gift['name']; ?></h3>
                    </td>
                    <td align="center">
                        <h3><?php echo $gift['amount']?></h3>
                    </td>
                    <td align="center">
                        <h3><a href="lottery.php?g_id=<?php echo $gift['id']?>">
                        <?php
                        $sql = 'SELECT * FROM records WHERE gift_id = :GIFT_ID AND status=1';
                        $rs = $pdo->prepare($sql);
                        $rs->execute([':GIFT_ID' => $gift['id']]);
                        $rs_amount = $rs->rowCount();
                        if($rs_amount >= $gift['amount']){
                            echo '已抽出'.$rs_amount.'人';
                        }else{
                            echo '抽獎';
                        }
                        ?>
                        </a>
                        </h3>
                    </td>
                </tr>
                <?php
                }
                ?>
            </table>
                    <div class="cleaner h50"></div>
                    <div class="cleaner"></div>
                    <form action="gifts.php" method="post">
                        <input id="clear" type="checkbox" name="clear" value="1" onchange="clear_form_control()"/>
                        <label for="clear"> 清除名單</label>
                        <div id="clear_form" style="display: none;">
                            密碼：<input name="pass" type="password" size="30"/>
                            <input type="hidden" name="gift_id" value="all"/>
                            <input type="hidden" name="group" value="<?php echo $group; ?>"/>
                        </div>
                        <input type="submit" name="submit" value="OK" />
                    </form>
                    <script type="text/javascript">
                        function clear_form_control(){
                            if($("#clear").is(":checked")){
                                $("#clear_form").show();
                            }else{
                                $("#clear_form").hide();
                            }
                        }
                    </script>
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