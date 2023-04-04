<?php

require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

//$app = AppFactory::create();

// User registration
$app->post('/register', function (Request $request, Response $response) {
    // Get the name, email, and password from the request body
    $data = $request->getParsedBody();
    $name = $data['name'];
    $email = $data['email'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    // Insert the user data into the database
    $db = new PDO('sqlite:chat.db');
    $stmt = $db->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

    // Return a success response
    $response->getBody()->write('User registered successfully');
    return $response->withHeader('Content-Type', 'text/plain')->withStatus(200);
});

// User login
$app->post('/login', function (Request $request, Response $response) {
    // Get the email and password from the request body
    $data = $request->getParsedBody();
    $email = $data['email'];
    $password = $data['password'];

    // Get the user data from the database
    $db = new PDO('sqlite:chat.db');
    $stmt = $db->prepare('SELECT * FROM users WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the password
    if (!$user || !password_verify($password, $user['password'])) {
        // Return an error response
        $response->getBody()->write('Invalid email or password');
        return $response->withHeader('Content-Type', 'text/plain')->withStatus(401);
    }
});

$app->get('/users', function (Request $request, Response $response) {
    $db = new PDO('sqlite:chat.db');

    $users = [];
    $query = "SELECT id, email FROM users";
    $result = $db->query($query);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $users[] = $row;
    }

    $response->getBody()->write(json_encode($users));
    return $response->withHeader('Content-Type', 'application/json');
});

