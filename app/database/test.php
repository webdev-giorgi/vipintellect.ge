<?php
class test
{
	public function index($conn, $args)
	{
		$sql = 'SELECT * FROM `test`';
		$prepare = $conn->prepare($sql);
		$prepare->execute();
		$fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);
		return $fetch;
	}
}
?>