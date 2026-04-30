<?php

$data = json_decode(file_get_contents("php://input"), true);

$users = json_decode(file_get_contents("users.json"), true);

foreach ($users as $user) {
    if ($user['email'] === $data['email'] && $user['password'] === $data['password']) {
        echo json_encode(["status" => "success"]);
        exit;
    }
}

echo json_encode(["status" => "error", "message" => "Invalid credentials"]);