<?php

namespace App\Foundation;

use App\Common\Patterns\Singleton;
use App\Foundation\Database\Contracts\DatabaseConnectionInterface;
use App\Foundation\Database\DatabaseConnection;
use App\Foundation\Exception\ExceptionHandler;
use App\Foundation\HTTP\Request;
//use App\Foundation\Router\Router;
use GuzzleHttp\Client;
use JetBrains\PhpStorm\NoReturn;
use Bramus\Router\Router;
use PDO;

class Application extends Singleton
{
    protected Router $router;
    protected string $root_path;
    protected DatabaseConnectionInterface $database_connection;

    #Функция запуска приложения
    public function run()
    {
        $this->init();

        try {
//            $this->initRouter();

//            dd($this->router,$this->captureRequest());
            $response = $this->router->run();
            dd($this->router,$response);


//            $response->send();

            $this->terminate();
        } catch (\Throwable $exception) {
            ExceptionHandler::handleException($exception);
        }
    }

    #[NoReturn] public function terminate()
    {
        exit(0);
    }

    /**
     * @return PDO
     */
    public function getDatabaseConnection(): PDO
    {
        return $this->database_connection->getConnection();
    }

    /**
     * @param  DatabaseConnection  $database_connection
     */
    public function setDatabaseConnection(DatabaseConnection $database_connection): void
    {
        $this->database_connection = $database_connection;
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->root_path;
    }

    /**
     * @param  string  $root_path
     */
    public function setRootPath(string $root_path): void
    {
        $this->root_path = $root_path;
    }

    public function captureRequest(): \GuzzleHttp\Psr7\Request
    {
//        $client = new Client(['base_uri' => 'http://localhost:8000']);

        $request = new \GuzzleHttp\Psr7\Request($this->router->getRequestMethod(), $_SERVER["REQUEST_URI"]);
//        dd($request);
//        $response = $client->request($this->router->getRequestMethod(), $this->router->getCurrentUri());
//
//        $request = new Request();
//
//
//        $request->initRequestFromGlobals();


        return $request;
    }

    public function initRouter(): void
    {
//        $this->router->compileRoutes();
        $this->router = require_once path('routes/api.php');

    }


    protected function init(): void
    {
//        $this->router = Router::getInstance();
        $this->router = require_once path('routes/api.php');

        $this->setDatabaseConnection(new DatabaseConnection(...config('database.connection')));
    }
}
