<?php
function result($err, $data){
	return array('err' => $err, 'data' => $data);
}
function createConnection(){	
	$localhost = false;
	$db = null;
	if ($localhost){
		$db = new PDO("mysql:host=127.0.0.1;dbname=OperationDoge;charset=utf8mb4;", "root", "root");
	} else {
		$db = new PDO("mysql:host=mysqlsemi1.space2u.com;dbname=d36234_db6;", "d36234-db6", "jn9MGrOC");
	}
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $db;
}
function createStatement($conn, $q, $p){
	$statement = $conn->prepare($q);
	$statement->setFetchMode(PDO::FETCH_ASSOC);
	bindParams($statement, $p);
	return $statement;
}
function bindParams($statement, $p){
	if (!is_null($p)){
		foreach ($p as $k => $v){
			$statement->bindParam($k, $v);
		}
	}
}
function fetchAll($statement){
	return $statement->fetchAll();
}
function fetch($statement){
	return $statement->fetch();
}
function query($q, $p = NULL, $single = false){
	$err = null;
	$data = null;
	try {
		$conn = createConnection();
		$statement = createStatement($conn, $q, $p);	
		$statement->execute($p);
		$data = ($single ? fetch($statement) : fetchAll($statement));
	} catch(PDOException $e){
		$err = $e;
	}	
	return result($err, $data);
}
function nonQuery($q, $p){
	$err = null;
	$data = null;
	try{
		$conn = createConnection();
		$statement = createStatement($conn, $q, $p);	
		
		$data = $statement->execute($p);
	} catch(PDOException $e) {
		$err = $e;
	}
	return result($err, $data);
}
?>