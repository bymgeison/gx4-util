<?php

namespace GX4\Util\Tests;

use GX4\Whoops\TWhoops;
use PHPUnit\Framework\TestCase;

class TWoopsTest extends TestCase
{
    private string $configDir = 'app/config';
    private string $configPath;

    protected function setUp(): void
    {
        $this->configPath = $this->configDir . '/application.ini';

        if (!is_dir($this->configDir)) {
            mkdir($this->configDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        if (file_exists($this->configPath)) {
            unlink($this->configPath);
        }
    }

    public function testConstructorWithDebugEnabled()
    {
        file_put_contents($this->configPath, "[general]\ndebug = 1");

        // Só estamos garantindo que a classe carrega sem erros com debug = 1
        $this->expectNotToPerformAssertions();
        new TWhoops();
    }

    public function testConstructorWithDebugDisabled()
    {
        file_put_contents($this->configPath, "[general]\ndebug = 0");

        // Só estamos garantindo que a classe carrega sem erros com debug = 0
        $this->expectNotToPerformAssertions();
        new TWhoops();
    }
}
