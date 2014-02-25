<div id="sidebar">
<div id="name" class="status"><?=$_SESSION['name']?></div>
<div id="server" class="status"></div>
<div id="win" class="status"></div>
<div id="logout" class="status"><a href="index.php?logout=1">Выход</a></div>
</div>

<div id="game">
    <table id="field_a" border="0" cellspacing="1" class="field"></table>
    <table id="field_b" border="0" cellspacing="1" class="field"></table>
</div>
<script>
    

    for(var i=0; i<10; i++)
    {
        $('#field_a').append('<tr id="a'+i+'"></tr>');
        $('#field_b').append('<tr id="b'+i+'"></tr>');
        for(var j=0; j<10; j++)
        {
            $('#a'+i).append('<td id="a'+i+j+'"></td>');
            $('#b'+i).append('<td id="b_'+i+'_'+j+'" class="cell"></td>');
        }
    }
    var ships = new Array();
    var game = createLayout();
    <?php
    $mydb = new classDb($_SESSION['host'], USER, PASSWORD, $_SESSION['db']);
    $check = newGameCheck($mydb);
    if($check)
    {
    ?>
    rivalsession = '<?=$check?>';
    websocket.onopen = function(evt){onOpen(evt, 's_'+rivalsession)};  
    <?php     
    }
    else
    {
    ?>
    websocket.onopen = function(evt){onOpen(evt, 'new')}; 
    <?php
    }
    ?>

</script>