<?php 

require 'db.php';

//CORS Allow Sets (To allow access in localhost)
header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");


switch($_SERVER['REQUEST_METHOD']){
    case 'GET':
        //ex: ?id=id - get specific todo
        if(isset($_GET['id']) && !isset($_GET['complete'])){
            getOneTodo($conn, $_GET['id']);
        }
        //noparam - get all todos
        else{
            getTodos($conn);
        }
        break;
    case 'POST':
        //ex: ?id=id - update todo
        if($_GET['id'] !== null 
            && $_POST['todo'] !== null 
            && $_POST['complete'] !== null){
            updateTodo($conn, $_GET['id'], $_POST['todo'], $_POST['complete']);
        }
        //noparam - add new todo
        else{
            addTodo($conn, $_POST['todo']);
        }
        break;
    case 'DELETE':
        //ex: ?id=id - delete todo
        if($_GET['id'] !== null){
            deleteTodo($conn, $_GET['id']);
        }
        break;
}

//functions
function getTodos($conn){
    $sql = "SELECT * FROM todos";
    $result = $conn->query($sql);
    if ($result) {
        $data = array();

        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    }else{
        echo json_encode(["error" => mysqli_error($conn)]);
    }
}


function getOneTodo($conn, $id) {
    $sql = "SELECT * FROM todos WHERE id='$id'";
    $result = $conn->query($sql);
    if ($result) {
        $data = array();
        
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        echo json_encode($data);
    }else{
        echo json_encode(["error" => mysqli_error($conn)]);
    }
}

function addTodo($conn, $todo){
    $sql = "INSERT INTO todos(todo) VALUES('$todo')";
    $result = $conn->query($sql);
    if($result) {
        echo json_encode(["success" => "berhasil menambah todo!"]);
    }else{
        echo json_encode(["error" => mysqli_error($conn)]);
    }
}

function updateTodo($conn, $id, $todo, $complete){
    //converted to string to prevent bugs xD. https://stackoverflow.com/a/1956618
    switch ($complete) {
        case "true":
            $complete = "1"; 
            break;
        
        default:
            $complete = "0";
            break;
    }

    $sql = "UPDATE todos SET todo='$todo', completed=$complete WHERE id='$id'";
    $result = $conn->query($sql);
    if($result) {
        getOneTodo($conn, $id);
    }else{
        echo json_encode(["error" => mysqli_error($conn)]);
    }
}

function deleteTodo($conn, $id){
    $sql = "DELETE FROM todos WHERE id='$id'";
    $result = $conn->query($sql);
    if($result) {
        echo json_encode(["success" => "berhasil menghapus todo !"]);
    }else{
        echo json_encode(["error" => mysqli_error($conn)]);
    }
}

