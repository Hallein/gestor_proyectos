<?php
define('FILES', '../public/files/');

require '../../vendor/autoload.php';	// sistema/public/api

$config['displayErrorDetails'] = true;

$config['db']['host']   = "localhost";
$config['db']['user']   = "root";		//vidrier3_pretzel
$config['db']['pass']   = "";			//sagiribestwaifu
$config['db']['dbname'] = "calculadora";//vidrier3_calculadora

//Instancia de Slim
$app = new Slim\App(["settings" => $config]);
$container = $app->getContainer();

//Configuración de la BD con PDO dentro del container
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    try{
	    $pdo = new PDO("mysql:host=" . $db['host'] . ";
				    	dbname=" . $db['dbname'], 
				    	$db['user'], 
				    	$db['pass'],
				    	array('charset'=>'utf8'));

		$pdo->query("SET CHARACTER SET utf8");
		$pdo->query("SET lc_time_names = 'es_ES'");
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);    	
    }
    catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
    return $pdo;
};

	require 'routes.php';
	require 'controllers.php';
	require 'models.php';
	require 'container.php';
// require 'middlewares.php';

$app->get('/test', function ($request, $response, $args){
	$json = array('random' => rand(0, 1000));
	$response->write(json_encode($json));
	return $response;
});

$app->run();