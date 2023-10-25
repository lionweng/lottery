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
                    <li><a href="gifts.php">Gifts</a></li>
                </ul>
                <br style="clear: left" />
            </div>

            <div id="templatemo_header">
                <div id="site_title"><h1>>>>>>>>>>Lottery</h1></div>
            </div> <!-- end of header -->

            <div id="templatemo_main">
                <div class="col_fw">
                    <div style="width: 60%; margin:0px auto;">
                        <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
                        <lottie-player src="https://assets4.lottiefiles.com/private_files/lf30_lsg0irlz.json"  background="transparent"  speed="0.3"  style="width: 90%;"  loop  autoplay></lottie-player>
                    </div>
            <?php
            if($_GET['g_id']){
                $gift_id = $_GET['g_id'];
            }else{
                echo '請先選擇要抽出的獎項！';
                exit();
            }

            $g_sql = 'SELECT * FROM gifts WHERE id = :GIFT_ID AND status = :Enable';
            $g_rs = $pdo->prepare($g_sql);
            $g_rs->execute([':GIFT_ID' => $gift_id, ':Enable' => '1']);
            $gift = $g_rs->fetch(PDO::FETCH_ASSOC);

            $r_sql = 'SELECT * FROM records WHERE gift_id = :GIFT_ID AND status=1';
            $r_rs = $pdo->prepare($r_sql);
            $r_rs->execute([':GIFT_ID' => $gift_id]);
            $r_amount = $r_rs->rowCount();

            if($r_amount <= $gift['amount']) {
                $num = $gift['amount'] - $r_amount;
                for($i=0; $i < $num; $i++) {
                    $m_sql = 'SELECT id FROM members WHERE id NOT IN (
                              SELECT member_id FROM records WHERE groups = :GROUP AND status=1)';
                    $m_rs = $pdo->prepare($m_sql);
                    $m_rs->execute([':GROUP' => $gift['groups']]);
                    $m_total = $m_rs->rowCount();
                    if ($m_total >= 2) {
                        $offset = rand(0, $m_total - 1);
                    } else {
                        $offset = 0;
                    }

                    $m_sql = 'SELECT id FROM members WHERE id NOT IN (
                              SELECT member_id FROM records WHERE groups = :GROUP AND status=1) 
                              ORDER BY id
                              LIMIT :OFFSET, 1';
                    $m_rs = $pdo->prepare($m_sql);
                    $m_rs->execute([':GROUP' => $gift['groups'], ':OFFSET' => $offset]);
                    $member = $m_rs->fetch(PDO::FETCH_ASSOC);

                    $insert_sql = 'INSERT INTO records (groups, gift_id, member_id) VALUES (:GROUPS, :GIFT_ID, :MEMBER_ID)';
                    $insert_rs = $pdo->prepare($insert_sql);
                    try {
                        $insert_rs->execute([':GROUPS'=>$gift['groups'], ':GIFT_ID' => $gift['id'], ':MEMBER_ID' => $member['id'] ]);
                    }catch (Exception $e){
                        print_r($insert_rs->errorInfo());
                        throw $e;

                    }
                }
            }

            $sql = 'SELECT * FROM records WHERE gift_id = :GIFT_ID AND status = :Enable
                    ORDER BY time ASC';
            $rs = $pdo->prepare($sql);
            $rs->execute([':GIFT_ID' => $gift['id'], ':Enable' => '1']);
            $records = $rs->fetchAll(PDO::FETCH_ASSOC);
            echo '<image width="300" align="right" src="gifts/'.$gift_id.'.jpg">';
            echo '<h2>'.$gift['name'].'</h2><h3>中獎名單</h3>';
            ?>

      		<table border="1" align="center" cellpadding="5" cellspacing="1" width="90%">
                <thead>
                <tr valign="middle" bgcolor="FFB700">
                <th nowrap>
                    <h3>編號</h3>
                </th>
                <th>
                    <h3>名稱</h3>
                </th>
                <th>
                    <h3>抽出時間</h3>
                </th>
                </tr>
                </thead>
                <?php
                $i=0;
                foreach($records as $record){
                    $i++;
                ?>
                <tr valign="middle" bgcolor="<?php echo $i%2==0?'EC061D':'00896C'; ?>">
                    <td align="center">
                        <h3><?php echo $record['member_id']; ?></h3>
                    </td>
                    <td align="center" nowrap>
                        <h3><?php
                            $m_sql = 'SELECT * FROM members WHERE id = :ID';
                            $m_rs = $pdo->prepare($m_sql);
                            $m_rs->execute([':ID' => $record['member_id']]);
                            $member = $m_rs->fetch(PDO::FETCH_ASSOC);
                            echo $member['name'];
                            ?>
                        </h3>
                    </td>
                    <td align="center">
                        <h3><?php echo $record['time']; ?></h3>
                    </td>
                </tr>
                <?php
                }
                ?>
            </table>
                    <form action="gifts.php" method="post">
                        <input id="edit" type="checkbox" name="edit1" value="1" onchange="edit_form_control()"/>
                        <label for="edit"> 清除名單</label>
                        <div id="edit_form" style="display: none;">
                            密碼：<input name="pass" type="password" size="30"/>
                            <input type="hidden" name="gift_id" value="<?php echo $gift['id']; ?>"/>
                        </div>
                        <input type="submit" name="submit" value="OK" />
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