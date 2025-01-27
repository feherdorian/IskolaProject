<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QueryOsztalynevObjTest extends TestCase
{
    private $startTime;
    private $endTime;
    private $responseTime;

    public function test_the_queryOsztalynevObj_response_time(): void
    {
        $this->startTime = microtime(true);
        $response = $this->get('/api/queryOsztalynevsorObj');
        $this->endTime = microtime(true);
        $this->responseTime = round(($this->endTime - $this->startTime)*1000, 2);
        $response->assertStatus(200);
        $this->assertLessThan(200, $this->responseTime);
    }

    public function tearDown(): void {
        parent::tearDown();
        echo PHP_EOL."\tA válaszidő(queryOsztalynevsorObj): {$this->responseTime} ms";
    }
}