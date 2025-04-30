<?php

namespace GX4\Whoops;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class TWhoops
{
    private bool $debug = false; // Inicialize aqui com um valor padrão (false)

    public function __construct()
    {
        $this->loadIni();

        if ($this->debug) {
            $this->registerWhoops();
        }
    }

    /**
     * Lê o arquivo INI e define se o modo debug está ativado.
     *
     * @return void
     */
    private function loadIni(): void
    {
        if (file_exists('app/config/application.ini')) {
            $ini = parse_ini_file('app/config/application.ini', true);
            if (!empty($ini['general']['debug']) && $ini['general']['debug'] === '1') {
                $this->debug = true;
            }
        }
    }

    /**
     * Registra o Whoops como handler de erros.
     *
     * @return void
     */
    private function registerWhoops(): void
    {
        $handler = new PrettyPageHandler();
        $handler->setEditor('vscode');

        $whoops = new Run();
        $whoops->prependHandler($handler);
        $whoops->register();
    }
}
