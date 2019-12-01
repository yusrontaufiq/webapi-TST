<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'billboard_db');

class Database
{
    private $server;
    private $user;
    private $password;
    private $dbName;
    private $conn;


    public function __construct()
    {
        $this->server = DB_HOST;
        $this->user = DB_USER;
        $this->password = DB_PASS;
        $this->dbName = DB_NAME;
        $this->conn  =  mysqli_connect($this->server, $this->user, $this->password, $this->dbName);
    }


    public function execute($template, $valueType, $values)
    {
        $stmt = $this->conn->prepare($template);
        $param_length = count($values);
        $param_type = "";
        $params = array();

        for ($i = 0; $i < $param_length; $i++) {
            $param_type .= $valueType[$i];
        }

        $params[] = &$param_type;

        for ($i = 0; $i < $param_length; $i++) {
            $params[] = &$values[$i];
        }

        call_user_func_array(array($stmt, "bind_param"), $params);
        $stmt->execute();

        if ($stmt) {
            $result = $stmt->get_result();

            if ($result) {
                $data = array();
                while ($row = $result->fetch_assoc()) {
                    array_push($data, $row);
                }
                return $data;
            } else {
                return 200;
            }
        } else {
            return 500;
        }
    }
}



if (!isset($_GET["song_id"]) ){
	$db = new Database();
	$sql_query = "SELECT * FROM song WHERE song_id <> ? ORDER BY rating DESC";
	$params_values = array(-1);
	$song = $db->execute($sql_query, array("i"),$params_values );

	for($i = 0 ; $i < count($song) ; $i ++){
		$data["song_id"] = $song[$i]["song_id"] ;
		$data["title"] = $song[$i]["title"] ;
		$data["artist"] = $song[$i]["artist"] ;
		$data["album"] = $song[$i]["album"] ;
		$data["year"] = $song[$i]["year"] ;
		$data["rating"] = $song[$i]["rating"] ;	
		$response[$i] = $data ;
	}

	echo json_encode($response);
}else{
	$song_id = $_GET["song_id"] ;
	$db = new Database();
	$sql_query = "SELECT * FROM song WHERE song_id = ? ";
	$params_values = array($song_id);
	$song = $db->execute($sql_query, array("i"),$params_values );
	
	$data["song_id"] = $song[0]["song_id"] ;
	$data["title"] = $song[0]["title"] ;
	$data["artist"] = $song[0]["artist"] ;
	$data["album"] = $song[0]["album"] ;
	$data["year"] = $song[0]["year"] ;
	$data["rating"] = $song[0]["rating"] ;	
	$response = $data ;


	echo json_encode($response);
}



