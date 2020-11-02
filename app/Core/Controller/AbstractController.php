<?php

namespace App\Core\Controller;

use App\Core\Template\TemplateEngine;

abstract class AbstractController
{
    protected function render(string $templatePath, array $params = []): string
    {
        $engine = TemplateEngine::instance();

        return $engine->render($templatePath, $params);
    }
}
