<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Sport;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SportsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_the_get_sports_table_all_record_example(): void
    {
        $row = Sport::create([
            'sportNev' => "xxx"
        ]);

        $response = $this->get('/api/sports');
        $response->assertStatus(200);
        $response->assertSee('xxx');


        $this->assertDatabaseHas('sports', ['sportNev' => 'xxx']);
    }
}
