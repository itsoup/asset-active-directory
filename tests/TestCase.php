<?php

namespace Tests;

use Laravel\Lumen\Application;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function decodedJsonResponse(): array
    {
        return json_decode($this->response->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}
