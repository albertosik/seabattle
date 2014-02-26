<?php
namespace PHPDaemon\Applications;
use PHPDaemon\Core\Daemon;
use PHPDaemon\Core\Timer;

class SBWebSocket extends \PHPDaemon\Core\AppInstance {

    public $enableRPC=true; // Без этой строчки не будут работать широковещательные вызовы
    public $sessions=array(); // Здесь будем хранить указатели на сессии подключившихся клиентов

    // С этого метода начинается работа нашего приложения
    public function onReady() {
        $appInstance = $this;

        // Метод timerTask() будет вызываться каждые 5 секунд
        //$this->timerTask($appInstance);

        // Наше приложение будет доступно по адресу ws://site.com:8047/myws
        \PHPDaemon\Servers\WebSocket\Pool::getInstance()->addRoute('sbws', function ($client) use ($appInstance) {
            $session=new SBWebSocketRoute($client, $appInstance); // Создаем сессию
            $session->id=uniqid(); // Назначаем ей уникальный ID
            $this->sessions[$session->id]=$session; //Сохраняем в массив
            return $session;
        });

    }

    function timerTask($appInstance) {
        // Отправляем каждому клиенту свое сообщение
        foreach($this->sessions as $id=>$session) {
            $session->client->sendFrame('This is private message to client with ID '.$id, 'STRING');
        }

        // После отправляем всем клиентам сообщение от каждого воркера (широковещательный вызов)
        $appInstance->broadcastCall('sendBcMessage', array(Daemon::$process->getPid()));

        // Перезапускаем наш метод спустя 5 секунд
        Timer::add(function($event) use ($appInstance) {
            $this->timerTask($appInstance);
            $event->finish();
        }, 5e6); // Время задается в микросекундах
    }

    // Функция для широковещательного вызова (при вызове срабатывает во всех воркерах)
    public function sendBcMessage($pid) {
        foreach($this->sessions as $id=>$session) {
            $session->client->sendFrame('==This is broadcast message from worker #'.$pid, 'STRING');
        }
    }

}

class SBWebSocketRoute extends \PHPDaemon\WebSocket\Route {

    public $client;
    public $appInstance;
    public $id; // Здесь храним ID сессии
	public $iduser;
	public $idrival;
	public $points;

    public function __construct($client,$appInstance) {
        $this->client=$client;
        $this->appInstance=$appInstance;
//        include dirname(__FILE__).'/../yiiapp.php';
    }

    // Этот метод срабатывает сообщении от клиента
    public function onFrame($data, $type) {
		if($data[0]=='s')
		{
			$sess = explode('_',$data);
			$this->appInstance->sessions[$sess[1]]->idrival = $this->id;
			$this->idrival = $sess[1];
			$this->client->sendFrame('{"rivalId":"'.$this->appInstance->sessions[$sess[1]]->iduser.'"}', 'STRING');
			$this->client->sendFrame('{"sess2id":"'.$this->id.'"}', 'STRING');
			$this->appInstance->sessions[$sess[1]]->client->sendFrame('{"rivalId":"'.$this->iduser.'"}', 'STRING');
		}
		else if($data[0] == 'b')
		{
			$this->appInstance->sessions[$this->idrival]->client->sendFrame('{"cell":"'.$data.'"}', 'STRING');
		}
		else if($data == 'new')
		{
                        $arr = array('myid'=>$this->id);
			$this->client->sendFrame(json_encode($arr), 'STRING');
		}
		else if($data[0] == 'i')
		{
			$id = explode('_',$data);
			$this->iduser = $id[1];
		}
                else if($data[0] == 'h')
                {
                    $this->appInstance->sessions[$this->idrival]->client->sendFrame('{"hit":"'.$data.'"}', 'STRING');
                }

    }

    // Этот метод срабатывает при закрытии соединения клиентом
    public function onFinish() {
        // Удаляем сессию из массива
        unset($this->appInstance->sessions[$this->id]);
    }

}