<?php

require_once __DIR__ . '/../vendor/autoload.php';


$app = new Silex\Application();

// database init
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__ . '/../data/app.db',
    ),
));
$schema = $app['db']->getSchemaManager();
if (!$schema->tablesExist('todo')) {
    $todo = new \Doctrine\DBAL\Schema\Table('todo');

    $todo->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
    $todo->setPrimaryKey(['id']);
    $todo->addColumn('created_on', 'bigint');
    $todo->addIndex(['created_on'], 'time_index');
    $todo->addColumn('task', 'string');
    $todo->addColumn('completed', 'integer');

    $schema->createTable($todo);
}

$app->get('/', function () {
    return 'Hello!';
});

$app->get('/todo', function () use ($app) {

    $sql = "SELECT * FROM todo ORDER by created_on DESC";
    $result = $app['db']->fetchAll($sql);
    return $app->json($result);
});

$app->put('/todo/{id}', function ($id, \Symfony\Component\HttpFoundation\Request $request) use ($app) {

    $data = json_decode($request->getContent(), true);

    try {
        $app['db']->update('todo', $data, ['id' => $id]);
    } catch (Exception $exception) {
        return new \Symfony\Component\HttpFoundation\Response("FAIL: " . $exception->getMessage(), 400);
    }

    return $app->json($data);
});

$app->delete('/todo/{id}', function ($id) use ($app) {

    try {
        $app['db']->delete('todo', ['id' => (int)$id]);
    } catch (Exception $exception) {
        return new \Symfony\Component\HttpFoundation\Response("FAIL: " . $exception->getMessage(), 400);
    }

    return 'OK';
});

$app->post('/todo', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {

    $data = json_decode($request->getContent(), true);

    try {
        $data['id'] = $app['db']->insert('todo', $data);
    } catch (Exception $exception) {
        return new \Symfony\Component\HttpFoundation\Response("FAIL: " . $exception->getMessage(), 400);
    }

    return $app->json($data);
});

$app->run();
