<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouteNotFoundException;
use PDO;

class App
{
    private static DB $db;

    public function __construct(protected router $router, protected array $request, protected Config $config) 
    {
        static::$db = new DB($config->db ?? []);
    }

    public static function db(): DB
    {
        return static::$db;
    }    

    public function run()
    {
        try {
            $response = $this->router->resolve(
                $this->request['uri'],
                strtolower($this->request['method'])
            );
    
            if (is_string($response)) {
                // Check if the response contains HTML or CSS content
                if (strpos($response, '<style>') !== false) {
                    // If it contains <style> tags, treat it as CSS and set the appropriate header
                    header('Content-Type: text/css');
                } else {
                    // Otherwise, treat it as HTML
                    header('Content-Type: text/html');
                }
                echo $response;
            } else {
                // Handle other types of responses (e.g., JSON, etc.) as needed
                // You can add more content types here if necessary
                echo $response;
            }
        } catch (RouteNotFoundException $e) {
            http_response_code(404);
            echo View::make('error/404');
        }
    }
    
}
