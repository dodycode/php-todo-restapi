<?php 
    //CURL GET OPTIONS
    $options = [
        CURLOPT_URL => 'yourhost/api.php',
        CURLOPT_RETURNTRANSFER => true
    ];

    include 'partials/curl.php';
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>PHP Todo REST API</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css">
        <style>
            body {
                font-family: 'Montserrat', sans-serif;
                font-size: 14px;
            }

            * {
                box-sizing: border-box;
            }
        </style>
    </head>
    <body class="bg-blue-400">

        <div class="w-full px-2 md:w-1/2 md:px-0 mx-auto my-auto flex flex-col content-center h-screen overflow-auto">

            <section class="mt-4 text-center relative">
                <h1 class="text-white font-semibold text-2xl tracking-widest">My Task</h1>
            </section>

            <section class="text-center mt-3">
                <div class="inline-flex">
                    <form id="todo-form" style="display:inherit;" action="/" method="POST">
                        <input id="new-todo-input" name="todo" type="text" class="appearance-none focus:outline-none py-2 px-2 rounded rounded-tr-none rounded-br-none" placeholder="Nama Task" required>
                        <button id="add-btn" type="submit" class="bg-red-500 text-white px-5 rounded rounded-tl-none rounded-bl-none focus:outline-none hover:bg-red-700">Add</button>
                    </form>
                </div>
            </section>

            <section id="todos" class="mt-3">
                <?php if(isset($result['error'])): ?>
                    <script type="text/javascript">
                        console.error(`<?php echo $result["error"]; ?>`);
                    </script>

                <?php else: ?>

                <?php foreach($result as $row): ?>
                <div class="shadow bg-white rounded p-4 mt-4 ml-auto mr-auto md:w-1/2 relative cursor-pointer">
                    <span class="mr-20"><?php echo substr($row->todo, 0, 20); ?> | <?php echo $row->completed === '1' ? 'Completed' : 'Not Completed' ?></span>
                    <div style="top: 15px;right: 10px;" class="absolute">
                        <span onclick="updateTodo(<?php echo $row->id; ?>)">
                            <img class="w-4 inline-block cursor-pointer" src="assets/img/lnr-pencil.svg" alt="Edit">
                        </span>
                        <span onclick="showTodo(<?php echo $row->id; ?>)">
                            <img class="w-5 inline-block cursor-pointer" src="assets/img/lnr-eye.svg" alt="View">
                        </span>
                        <span onclick="deleteTodo(<?php echo $row->id; ?>)">
                            <img class="w-5 inline-block cursor-pointer" src="assets/img/lnr-cross.svg" alt="Delete">
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php endif; ?>
            </section>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
        <script type="text/javascript">
            document.getElementById("todo-form").addEventListener("submit", function(event){
              event.preventDefault();

              addTodo();
            });

            const addTodo = async () => {
                var formData = new FormData();
                formData.append('todo', document.getElementById('new-todo-input').value);

                return fetch('api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    window.location.href = '/';
                });
            }

            const showTodo = async(id) => {
                return fetch('api.php?id='+id, {method: 'GET'})
                .then(function(response) {
                    return response.json();
                })
                .then(function(data){
                    if (data[0].completed === "1") {
                        var status = "Completed";
                    }else{
                        var status = "Not Completed"
                    }

                    Swal.fire(
                      'Show Todo',
                      'Content: '+data[0].todo+"<br />"
                      +'Status: '+status,
                      'info'
                    );    
                });
            }

            const updateTodo = async (id) => {
                const {value: formValues} = await Swal.fire({
                  title: 'Manage Todo',
                  html:
                    `<input type="text" id="todo-input" class="appearance-none focus:outline-none py-4 px-2 rounded rounded-tr-none rounded-br-none w-full border mb-2" placeholder="Todo name">` +
                    `<select id="complete" class="appearance-none bg-white focus:outline-none py-4 px-2 rounded rounded-tr-none rounded-br-none w-full border mb-2">
                        <option value="" hidden selected>Please choose!</option>
                        <option value="true">Completed</option>
                        <option value="false">Not Complete</option>
                    </select>`,
                  focusConfirm: false,
                  preConfirm: () => {
                    return [
                      document.getElementById('todo-input').value,
                      document.getElementById('complete').value
                    ]
                  }
                });

                if (formValues) {
                    var formData = new FormData();
                    formData.append('todo', formValues[0]);
                    formData.append('complete', formValues[1]);

                    return fetch('api.php?id='+id, {
                        method: 'POST',
                        body: formData
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        window.location.href = '/';
                    });
                }
            }

            const deleteTodo = async (id) => {
                await Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.value) {
                    return fetch('api.php?id='+id, {method: 'DELETE'})
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        window.location.href = '/';
                    });
                  }
                });
            }
        </script>
    </body>
</html>
