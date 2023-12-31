<?php

namespace Achraf\framework\View;

use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private Environment $twig;

    public function __construct(FilesystemLoader $loader)
    {
        $this->twig = new Environment($loader);
    }

    public function render($view, array $data = []): string
    {
        try {
            return $this->twig->load($view.'.twig')->render($data);
        } catch (Exception $exception) {
            return '<h1>Something went wrong </h1>'.$exception->getMessage();
        }
    }
}
