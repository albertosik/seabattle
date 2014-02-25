<?php
class classDb extends mysqli
{
	//private $dbHandler = NULL;
	private $dbResult = NULL;
	
	function __construct($host, $user, $password, $dbname)
	{
            parent::__construct($host, $user, $password, $dbname);

                    if (mysqli_connect_error()) {
                        die('Connect Error (' . mysqli_connect_errno() . ') '
                                . mysqli_connect_error());
                    }
	}

	
	private function qry($_com)
	{
		$this->dbResult = $this->query($_com);
		if(!$this->dbResult)
		{
			echo '<p style="color:red">'.$this->error.'</p>';
		}
	}
	
	private function getResultToArray()
	{
		$rows = array();
		while($row = $this->dbResult->fetch_assoc())
		{
			$rows[] = $row;
		}
		return $rows;
	}
	 public function UPDATE($_com)
	 {
		$this->qry($_com);
	 }
	 
	 public function DELETE($_com)
	 {
		$this->qry($_com);
	 }
	 
	 public function INSERT($_com)
	 {
		$this->qry($_com);
		return $this->insert_id;
	 }
	 
	 public function SELECT($_com)
	 {
		$this->qry($_com);
		return $this->getResultToArray();
	 }
}



?>