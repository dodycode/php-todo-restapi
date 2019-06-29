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
        //ex: ?id=id&complete=trueorfalse - set complete specific todo
        elseif(isset($_GET['id']) && isset($_GET['complete'])){
            completeTodo($conn, $_GET['id'], $_GET['complete']);
        }
        //noparam - get all todos
        else{
            getTodos($conn);
        }
        break;
    case 'POST':
        //ex: ?id=id - update todo
        if($_GET['id'] !== null && $_POST['todo'] !== null){
            updateTodo($conn, $_GET['id'], $_POST['todo']);
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

function updateTodo($conn, $id, $todo){
    $sql = "UPDATE todos SET todo='$todo' WHERE id='$id'";
    $result = $conn->query($sql);
    if($result) {
        echo json_encode(["success" => "berhasil mengedit todo!"]);
    }else{
        echo json_encode(["error" => mysqli_error($conn)]);
    }
}

function completeTodo($conn, $id, $complete){
    //convert to string to prevent bugs xD. https://stackoverflow.com/a/1956618
    if ($complete == "false") { 
        $complete = "0"; 
    }else{
        $complete = "1";
    }

    $sql = "UPDATE todos SET completed=$complete WHERE id='$id'";
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

