<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/', function() {
    return 'Hello!';
});

$app->get('/todo', function() use ($app){

  return $app->json([
    ['task' => 'Hello', 'completed' => true],
    ['task' => 'Hello2', 'completed' => false]
  ]);
});

$app->put('/todo/{id}', function($id) use ($app){

  return 'Ok';
});

$app->delete('/todo/{id}', function($id) use ($app){
  return 'Ok';
});

$app->post('/todo', function() use ($app){
  return 'Ok';
});

$app->run();
