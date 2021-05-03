<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        TestResponse::macro('assertResource', function($resource) {
            $this->assertJson(function(AssertableJson $json) use ($resource) {
                $json->where('data', $resource->response()->getData(true)['data'])->etc();
            });
        });

        return $app;
    }
}
