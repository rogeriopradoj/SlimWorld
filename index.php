<?php
require 'vendor/Slim/Slim.php';
require 'vendor/NotORM/NotORM.php';

$dsn = 'sqlite:' . realpath('./data/db.sqlite');

$pdo = new PDO($dsn);
$db = new NotORM($pdo);

$app = new Slim(
    array(
        'MODE' => 'development',
        'TEMPLATES.PATH' => './templates'
    )
);

$app->get('/', function() {
    echo '<h1>Hello Slim World</h1>';
});

$app->get('/books', function() use ($app, $db) {
    $books = array();
    foreach ($db->books() as $book) {
        $books[] = array(
            'id' => $book['id'],
            'title' => $book['title'],
            'author' => $book['author'],
            'summary' => $book['summary']
        );
    }
    $app->response()->header('Content-Type', 'application/json');
    echo json_encode($books);
});

$app->get('/book/:id', function($id) use ($app, $db) {
    $app->response()->header('Content-Type', 'application/json');
    $book = $db->books()->where('id', $id);
    if ($data = $book->fetch()) {
        echo json_encode(
            array(
                'id' => $data['id'],
                'title' => $data['title'],
                'author' => $data['author'],
                'summary' => $data['summary']
            )
        );
    } else {
        echo json_encode(
            array(
                'status' => false,
                'message' => 'Book ID ' . $id . ' does not exist'
            )
        );
    }
});

$app->run();