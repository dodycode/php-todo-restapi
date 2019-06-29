<?php

include 'db.php';

//create table users
$query = "create table todos(
    id int(11) AUTO_INCREMENT PRIMARY KEY,
    todo varchar(255) NOT NULL,
    completed int(11) NOT NULL DEFAULT 0 
);";

$execute = mysqli_query($conn, $query);

if ($execute) {
	echo json_encode(["success" => "Schema succesfully generated!"]);
}else{
	echo json_encode(["error" => mysqli_error($conn)]);
}