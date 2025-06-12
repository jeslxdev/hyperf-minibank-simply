<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Zipkin\Samplers\BinarySampler;

use function Hyperf\Support\env;

return [
    'default' => env('TRACER_DRIVER', 'zipkin'),
    'enable' => [
        'guzzle' => (bool)env('TRACER_ENABLE_GUZZLE', false),
        'redis' => (bool)env('TRACER_ENABLE_REDIS', false),
        'db' => (bool)env('TRACER_ENABLE_DB', false),
        'method' => (bool)env('TRACER_ENABLE_METHOD', false),
        'exception' => (bool)env('TRACER_ENABLE_EXCEPTION', true),
        'error' => (bool)env('TRACER_ENABLE_ERROR', true),
    ],
    'tracer' => [
        'zipkin' => [
            'driver' => \Hyperf\Tracer\Adapter\ZipkinTracerFactory::class,
            'app' => [
                'name' => env('APP_NAME', 'php-minibank-hyperf-us'),
                'ipv4' => '127.0.0.1',
                'ipv6' => null,
                'port' => 80,
            ],
            'options' => [
                'endpoint_url' => env('ZIPKIN_ENDPOINT_URL', 'http://localhost:9411/api/v2/spans'),
                'timeout' => env('ZIPKIN_TIMEOUT', 1),
            ],
            'sampler' => BinarySampler::createAsAlwaysSample(),
        ],
        'jaeger' => [
            'driver' => \Hyperf\Tracer\Adapter\JaegerTracerFactory::class,
            'name' => env('APP_NAME', 'skeleton'),
            'options' => [
                'local_agent' => [
                    'reporting_host' => env('JAEGER_REPORTING_HOST', 'localhost'),
                    'reporting_port' => env('JAEGER_REPORTING_PORT', 5775),
                ],
            ],
        ],
    ],
];
