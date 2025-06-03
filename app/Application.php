<?php

namespace FluentSupportCustomAI\App;

class Application
{
    public function __construct($app)
    {
        $this->boot($app);
    }

    public function boot($app)
    {
        $router = $app->router;
        require_once FLUENTSUPPORTCUSTOMAI_PLUGIN_PATH.'app/Hooks/actions.php';
        require_once FLUENTSUPPORTCUSTOMAI_PLUGIN_PATH.'app/Hooks/filters.php';
        require_once FLUENTSUPPORTCUSTOMAI_PLUGIN_PATH.'app/Http/routes.php';
    }
}
