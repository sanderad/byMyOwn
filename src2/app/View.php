<?php

declare (strict_types=1);

namespace App;

use App\Exceptions\ViewNotFoundException;

class View
{
    public function __construct(
        protected string $viewPath,
        protected array $params = []
    ) {

    }
    public static function make(string $viewPath, array $params = [])
    {
        return new static($viewPath, $params);
    }

    public function render(): string
    {
        $path = VIEW_PATH . '/' . $this->viewPath . '.php';

        if(! file_exists($path)) {
            throw new ViewNotFoundException();
        }

        ob_start();
        include $path;

        return (string) ob_get_clean();
    }

    public function __toString()
    {
        return $this->render();
    }

    public function __get($name)
    {
        return $this->params[$name] ?? null;
    }
}