<?php
session_start();
require_once 'conf.php';
require_once 'classDb.php';

if($_POST['cmd']=='newGame')
{
    $db = new classDb($_SESSION['host'], USER, PASSWORD, $_SESSION['db']);
    $_SESSION['gameid'] = $db->INSERT('INSERT INTO `game` SET `id_gamer_1`=\''.$_SESSION['userid'].'\', `id_gamer_2`=\'-1\', `session_1`=\''.mysql_real_escape_string($_POST['id']).'\'');
    $db->close();
}
else if($_POST['cmd']=='connect')
{
    $db = new classDb($_SESSION['host'], USER, PASSWORD, $_SESSION['db']);
    $game = $db->SELECT('SELECT `id` FROM `game` WHERE `session_1`=\''.mysql_real_escape_string($_POST['session_1']).'\'');
    $_SESSION['gameid'] = $game[0]['id'];
    $db->UPDATE('UPDATE `game` SET `id_gamer_2`=\''.$_SESSION['userid'].'\', `session_2`=\''.mysql_real_escape_string($_POST['id']).'\' WHERE `session_1`=\''.mysql_real_escape_string($_POST['session_1']).'\'');
    $db->close();
}
else if($_POST['cmd']=='win')
{
    $db = new classDb($_SESSION['host'], USER, PASSWORD, $_SESSION['db']);
    $db->UPDATE('UPDATE `game` SET `id_winner`=\''.$_SESSION['userid'].'\' WHERE `id`=\''.$_SESSION['gameid'].'\'');
    $db->close();
}
else if($_POST['cmd']=='getName')
{
    $db = new classDb(HOST, USER, PASSWORD, DB);
    $name = $db->SELECT('SELECT `name` FROM `user` WHERE `id`=\''.mysql_real_escape_string($_POST['id']).'\'');
    echo $name[0]['name'];
    $db->close();
}
?>
