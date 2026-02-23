<?php
class language
{
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
		
		require_once("app/functions/strings.php");
		$out = "[]";
		$strings = new functions\strings();
		$utf82lat = $strings->cut(functions\strings::utf82lat(implode("_",$_SESSION['URL'])), 20);
		$json = Config::CACHE."language_".str_replace(array("-"," ",",","..."), "", $utf82lat).".json";
		// echo $json;
		if(file_exists($json)){
			$out = @file_get_contents($json); 
		}else{
			$select = "SELECT * FROM `languages`";
			$prepare = $this->conn->prepare($select);
			$prepare->execute();
			if($prepare->rowCount()){
				$db_fetch = $prepare->fetchAll(PDO::FETCH_ASSOC);

				$fh = fopen($json, 'w') or die("Error opening output file");
				@fwrite($fh, json_encode($db_fetch,JSON_UNESCAPED_UNICODE));
				@fclose($fh);

				$out = @file_get_contents($json); 
			}
		}

		return json_decode($out, true);
	}

	private function current()
	{
		$out = "[]";

		$json = Config::CACHE."language_current_".str_replace(array("-"," "), "", implode("_",$_SESSION['URL'])).".json";

		if(file_exists($json)){
			$out = @file_get_contents($json); 
		}else{
			$select = "SELECT * FROM `languages` WHERE `title`=:title";
			$prepare = $this->conn->prepare($select);
			$prepare->execute(array(
				":title"=>$_SESSION["LANG"]
			));
			if($prepare->rowCount()){
				$db_fetch = $prepare->fetch(PDO::FETCH_ASSOC);

				$fh = @fopen($json, 'w') or die("Error opening output file");
				@fwrite($fh, json_encode($db_fetch,JSON_UNESCAPED_UNICODE));
				@fclose($fh);

				$out = @file_get_contents($json); 
			}
		}
		return json_decode($out, true);
	}
}
?>