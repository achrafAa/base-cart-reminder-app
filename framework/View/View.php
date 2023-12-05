<?php

namespace Achraf\framework\View;

use Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    /**
     * @var Environment
     */
    private Environment $twig;

    /**
     * @param  FilesystemLoader  $loader
     */
    public function __construct(FilesystemLoader $loader)
    {
        $this->twig = new Environment($loader);
    }

    /**
     * @param $view
     * @param  array  $data
     * @return string
     */
    public function render($view, array $data = []): string
    {
        try {
            return $this->twig->load($view.'.twig')->render($data);
        } catch (Exception $exception) {
            return '<h1>Something went wrong </h1>'.$exception->getMessage();
        }
    }
}
