<?php 
$conn_string = "host=ec2-54-221-244-62.compute-1.amazonaws.com port=5432 dbname=dc1l04vbvokmlr user=jihnymieqgdkut password=rhTxZwKWuAKdvqaU21oziua-MX";
$dbconn4 = pg_connect($conn_string);
//connect to a database named "dcdhmp2hbn5ol8" on the host "ec2-174-129-223-35.compute-1.amazonaws.com" with a username and password
if(!$dbconn4){ 
	echo "Error : Unable to open database\n"; 
}

?>
