<?php
function auth($login,$pass,$db)
{
    $login = mysql_real_escape_string($login);
    $pass = mysql_real_escape_string($pass);
    $auth = $db->SELECT('SELECT `u`.`id`,`name`, `host`, `db`, `wsserver` FROM `user` `u`, `shard` WHERE `shard`.`id`=`u`.`id_shard` AND `login`=\''.$login.'\' AND `password`=\''.$pass.'\'');
    if(sizeof($auth)==0)
    {
        return false;
    }
    else
    {        
        return $auth[0];
    }
}

function newGameCheck($db)
{
    $check = $db->SELECT('SELECT * FROM `game` ORDER BY `id` DESC');
    if(sizeof($check)==0)
    {
        return false;
    }
    else if($check[0]['id_gamer_2']==-1)
    {
        return $check[0]['session_1'];
    }
    else
    {
        return false;
    }
}

function registration($name,$login,$password,$db)
{
    $name = mysql_real_escape_string($name);
    $login = mysql_real_escape_string($login);
    $shard = $db->SELECT('SELECT `id` FROM `shard` WHERE `size` = (SELECT MIN(`size`) FROM `shard`)');
    if(checkLogin($login,$db))
    {
        $db->INSERT('INSERT INTO `user` SET `name`=\''.$name.'\',`login`=\''.$login.'\', `password`=\''.md5($password).'\', `id_shard`=\''.$shard[0]['id'].'\'');
        $db->UPDATE('UPDATE `shard` SET `size`=`size`+1 WHERE `id`=\''.$shard[0]['id'].'\'');
        return true;
    }
    else
    {
        return false;
    }
}
function checkLogin($login,$db)
{
    $login = mysql_real_escape_string($login);
    $check = $db->SELECT('SELECT * FROM `user` WHERE `login`=\''.$login.'\'');
    if(sizeof($check)==0)
    {
        return true;
    }
    else
    {
        return false;
    }
}
?>
