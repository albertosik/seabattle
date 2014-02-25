<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" href="css/style.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <?php
        require_once 'lib.php';
        require_once 'classDb.php';
        require_once 'conf.php';
        $db = new classDb(HOST, USER, PASSWORD, DB);
        if(isset($_POST['submit']))
        {
            $auth = auth($_POST['login'],md5($_POST['password']),$db);    
            if($auth)
            {
                $_SESSION['userid'] = $auth['id'];
                $_SESSION['name'] = $auth['name'];
                $_SESSION['host'] = $auth['host'];
                $_SESSION['db'] = $auth['db'];
                ?>
                <script>var wsserver = '<?=$auth['wsserver'];?>'</script>
                <?php
            }
        }
        $db->close();
        ?>
        <script>
            var userid = <?=$_SESSION['userid'];?>;
            var rivalsession;
        </script>
        <script src="js/script.js"></script>
        <script src="js/ws.js"></script>
    </head>
    <body>
              