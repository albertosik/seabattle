 var wsUri = "ws://"+wsserver+":8047/sbws"; 
 websocket = new WebSocket(wsUri); 
 websocket.onclose = function(evt){onClose(evt);}; 
 websocket.onmessage = function(evt) { onMessage(evt); }; 
 websocket.onerror = function(evt) { onError(evt); }; 
 
 function onOpen(evt, msg) 
 { 
     doSend('id_'+userid); 
     doSend(msg); 
 } 
 function onClose(evt) 
 {
     writeToScreen("DISCONNECTED"); 
 }  
 function onMessage(evt) 
 {    
     var command = JSON.parse(evt.data);
     console.log(command);
     if(command.cell)
     {
         var pos = command.cell.split('_');
         x = pos[1];
         y = pos[2];
         
         if(hit(x,y))
         {
             doSend('hitok_'+x+'_'+y);
             $('#a'+x+y).append('&#10008;');
         }
         else
         {
             doSend('hitfalse_'+x+'_'+y);
             $('#a'+x+y).css('background-color', 'rgba(0,150,155,0.9)'); 
             $('#field_b').attr('class','field');
             $('#field_b').animate({opacity:1});
             $('#win').empty().append('<p>Ваш ход</p>');
         }
     }
     else if(command.hit)
     { 
         var pos = command.hit.split('_');
         x = pos[1];
         y = pos[2];
         if(command.hit.charAt(3) === 'o')
         {    
            $('#b_'+x+'_'+y).append('&#10008;');
            $('#b_'+x+'_'+y).css('background-color', '#0000ff');
         }
         else
         {
            $('#b_'+x+'_'+y).css('background-color', 'rgba(0,150,155,0.9)'); 
            wait();
         }
     }
     else if(command.rivalId)
     {
         if(command.stroke === 'false')
         {
             wait();
         }
         else
         {
             $('#win').empty().append('<p>Ваш ход</p>');
         }
         $.post('ajax.php',{cmd:'getName',id:command.rivalId}, function(data){
             $('#server').append('<p>Ваш противник: '+data+'</p>');
         });
     }
     else if(command.myid)
     {
         $.post('ajax.php',{cmd:'newGame',id:command.myid}, function(data){
             $('#server').append('<p>Ожидается подключение проивника</p>');
         });
     }
     else if(command.sess2id)
     {
         $.post('ajax.php', {cmd:'connect',id:command.sess2id,session_1:rivalsession});
     }
     else if(command.win)
     {
         $('#win').empty().append('<p>Победа!!!</p>');
         $('#field_b').attr('class','block');
         $('#field_b').animate({opacity:0.6});
         $.post('ajax.php', {cmd:'win'});
     }
     else
    {
        writeToScreen('<span>' + evt.data+'</span>'); 
    }
 } 
 function onError(evt)
 { 
     writeToScreen('<span style="color: red;">ERROR:</span> ' + evt.data); 
 } 
 
 function doSend(message)
 {   
    websocket.send(message); 
 } 
 function writeToScreen(message) 
 {
     $('#server').append('<p>'+message+'</p>');
 }  
 function mail(obj)
 {	
	doSend(obj.value);
 }

