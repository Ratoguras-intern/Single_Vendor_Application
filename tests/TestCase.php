<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $cache = dirname(__DIR__) . '/bootstrap/cache';

        foreach (['config.php', 'events.php', 'routes-v7.php'] as $file) {
            if (file_exists($cache . '/' . $file)) {
                @unlink($cache . '/' . $file);
            }
        }

        return parent::createApplication();
    }
}
