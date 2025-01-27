<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueryOsztalynevTest extends TestCase
{
    private $startTime;
    private $endTime;
    private $responseTime;

    public function test_the_queryOsztalynev_response_time(): void
    {
        $this->startTime = microtime(true);
        $response = $this->get('/api/queryOsztalynevsorok');
        $this->endTime = microtime(true);
        $this->responseTime = round(($this->endTime - $this->startTime)*1000, 2);
        $response->assertStatus(200);
        $this->assertLessThan(200, $this->responseTime);
    }

    public function tearDown(): void {
        parent::tearDown();
        echo PHP_EOL."\tA válaszidő(queryOsztalynevsorok): {$this->responseTime} ms";
    }
}