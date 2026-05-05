<?php

$data = json_decode(file_get_contents("php://input"), true);

$file = "users.json";

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$users = json_decode(file_get_contents($file), true);



$users[] = $data;

file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));

echo json_encode(["status" => "success"]);