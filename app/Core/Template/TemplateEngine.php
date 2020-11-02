<?php

namespace App\Core\Template;

use App\Core\Config\Config;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TemplateEngine
{
    protected static $twig = null;

    public static function instance()
    {
        $config = Config::config();

        if (self::$twig === null) {
            $loader = new FilesystemLoader(Config::config('template_path'));

            $settings = [];

            if ($config['app_env'] === 'prod') {
                $settings['cache'] = $config['template_cache'];
            }

            $twig = new Environment($loader, $settings);

            self::$twig = $twig;
        }

        return self::$twig;
    }
}
