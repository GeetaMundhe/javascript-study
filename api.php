<?php
header("Content-Type: application/json");

$file = "users.json";

// Create file if not exists
if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

// Get request type
$action = $_GET['action'] ?? '';

// Get request data
$data = json_decode(file_get_contents("php://input"), true);

// Read users
$users = json_decode(file_get_contents($file), true);

// ================= ROUTER =================

switch($action){

    case 'getUsers':
        echo json_encode($users);
        break;

    case 'saveUser':
        saveUser($users, $data, $file);
        break;

    case 'login':
        loginUser($users, $data);
        break;

    case 'deleteUser':
        deleteUser($users, $data, $file);
        break;

    case 'updateUser':
    updateUser($users, $data, $file);
    break;

    default:
        echo json_encode([
            "status" => "error",
            "message" => "Invalid action"
        ]);
}

// ================= FUNCTIONS =================

// SAVE USER
function saveUser($users, $data, $file){

    foreach($users as $user){

        if($user['email'] === $data['email']){

            echo json_encode([
                "status" => "error",
                "message" => "Email already exists"
            ]);

            return;
        }
    }

    $data['id'] = count($users) + 1;

    // HASH PASSWORD
    $data['password'] = password_hash(
        $data['password'],
        PASSWORD_DEFAULT
    );

    $users[] = $data;

    file_put_contents(
        $file,
        json_encode($users, JSON_PRETTY_PRINT)
    );

    echo json_encode([
        "status" => "success"
    ]);
}

// LOGIN
function loginUser($users, $data){

    foreach($users as $user){

        if(
            $user['email'] === $data['email'] &&
            password_verify($data['password'], $user['password'])
        ){

            echo json_encode([
                "status" => "success"
            ]);

            return;
        }
    }

    echo json_encode([
        "status" => "error",
        "message" => "Invalid credentials"
    ]);
}

// DELETE USER
function deleteUser($users, $data, $file){

    // Get ID safely
    $id = isset($data['id']) ? (int)$data['id'] : 0;

    $updatedUsers = [];

    foreach($users as $user){

        // Keep all users EXCEPT the one clicked
        if((int)$user['id'] !== $id){
            $updatedUsers[] = $user;
        }
    }

    // Save updated data back to JSON
    file_put_contents($file, json_encode($updatedUsers, JSON_PRETTY_PRINT));

    echo json_encode(["success" => true]);
}

function updateUser($users, $data, $file){

    $id = (int)$data['id'];

    foreach($users as &$user){
        if((int)$user['id'] === $id){
            $user['name'] = $data['name'];
            $user['email'] = $data['email'];
            $user['mobile'] = $data['mobile'];
        }
    }

    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

    echo json_encode(["success" => true]);
}

?>