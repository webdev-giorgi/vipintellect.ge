<?php 
class tasks
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
		$select = "SELECT * FROM `tasks` WHERE `status`=:status ORDER BY `id` DESC";
		$prepare = $this->conn->prepare($select);
		$prepare->execute(array(
			":status"=>$args['status']
		));
		if($prepare->rowCount()){
			$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
		}
		return $fetch;
	}

	private function add($args)
	{
		$fetch = array();
		$select = "INSERT INTO `tasks` SET `title`=:title, `description`=:description, `type`=:type";
		$prepare = $this->conn->prepare($select);
		$prepare->execute(array(
			":title"=>$args['title'],
			":description"=>$args['description'],
			":type"=>$args['type'] 
		));
		return true;
	}

	private function changestatus($args)
	{
		$fetch = array();
		$select = "UPDATE `tasks` SET `status`=:status WHERE `id`=:id";
		$prepare = $this->conn->prepare($select);
		$prepare->execute(array(
			":status"=>2,
			":id"=>$args['id']
		));
		return true;
	}

	private function remove($args)
	{
		$fetch = array();
		$select = "DELETE FROM `tasks` WHERE `id`=:id";
		$prepare = $this->conn->prepare($select);
		$prepare->execute(array(
			":id"=>$args['id']
		));
		return true;
	}

}