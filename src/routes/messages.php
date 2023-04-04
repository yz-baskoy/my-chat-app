<?php

require 'vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

//$app = AppFactory::create();

// Send a message
$app->post('/messages', function (Request $request, Response $response) {
    // Get the author ID, recipient ID, and message content from the request body
    $data = $request->getParsedBody();
    $author_id = $data['author_id'];
    $recipient_id = $data['recipient_id'];
    $content = $data['content'];

    // Insert the message data into the database
    $db = new PDO('sqlite:chat.db');
    $stmt = $db->prepare('INSERT INTO messages (author_id, recipient_id, content) VALUES (:author_id, :recipient_id, :content)');
    $stmt->execute(['author_id' => $author_id, 'recipient_id' => $recipient_id, 'content' => $content]);

    // Return a success response
    $response->getBody()->write('Message sent successfully');
    return $response->withHeader('Content-Type', 'text/plain')->withStatus(200);
});

// Get messages for a user
$app->get('/messages/{recipient_id}', function (Request $request, Response $response, array $args) {
    // Get the recipient ID from the URL parameters
    $recipient_id = $args['recipient_id'];

    // Get the messages for the recipient from the database
    $db = new PDO('sqlite:chat.db');
    $stmt = $db->prepare('SELECT m.*, u.name as author_name FROM messages m JOIN users u ON m.author_id = u.id WHERE m.recipient_id = :recipient_id ORDER BY m.timestamp ASC');
    $stmt->execute(['recipient_id' => $recipient_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the messages
    $response->getBody()->write(json_encode($messages));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});
