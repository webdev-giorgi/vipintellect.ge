<?php 
class cities
{
	public function __construct()
	{

	}

	public function index($conn, $args)
	{
		$out = 0;
		$this->conn = $conn;
		if (
			isset($args['method']) && 
			is_string($args['method']) && 
			method_exists($this, $args['method'])
		) {
			$method = $args['method'];
			$out = $this->$method($args);
		}
		return $out;
	}

	private function select($args)
	{
		$fetch = array();
		$select = "SELECT `id`, `names` FROM `cities` ORDER BY `names` ASC";
		$prepare = $this->conn->prepare($select);
		$prepare->execute();
		if($prepare->rowCount()){
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
		}
		return $fetch;
	}

	private function selectById($args)
	{
		$fetch = array();
		$select = "SELECT `names` FROM `cities` WHERE `id`=:id";
		$prepare = $this->conn->prepare($select);
		$prepare->execute(array(
			":id"=>$args['id']
		));
		if($prepare->rowCount()){
			$fetch = $prepare->fetch(PDO::FETCH_ASSOC);
		}
		return $fetch['names'];
	}

}