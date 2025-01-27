<?php

namespace Tests\Integration;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use GuzzleHttp\Client;
use Tests\TestCase;

class IntegrationSportTest extends TestCase
{
    public function test_sports_http(): void
    {
        $httpClient = new Client();
        $url = 'http://localhost:8000/api/sports';
        $response = $httpClient->get($url);
        $data = json_decode($response->getBody()->getContents(), true);  
        $data = $data['data'];  
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
        $this->assertGreaterThan(0, count($data));
        dd($data);
    }
}