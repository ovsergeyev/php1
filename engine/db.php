<?php
function getAssocResult($sql){

	$db = mysqli_connect(HOST, USER, PASS, DB);

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	
	$result = mysqli_query($db, $sql);

	$array_result = array();
	while($row = mysqli_fetch_assoc($result)) {
		$array_result[] = $row;
	}

	mysqli_close($db);
	
	return $array_result;
}

function executeQuery($sql){
    $db = mysqli_connect(HOST, USER, PASS, DB);

    $result = mysqli_query($db, $sql);
    mysqli_close($db);
	return $result;
}

function getConnection(){
    $db = mysqli_connect(HOST, USER, PASS, DB);
    mysqli_query($db, "SET NAMES utf8");
    return $db;
}

function getRowResult($sql, $db = null){
    $array_result = getAssocResult($sql, $db);

    if(isset($array_result[0]))
        $result = $array_result[0];
    else
        $result = [];

    return $result;
}