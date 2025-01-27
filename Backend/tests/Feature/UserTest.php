<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{

    use DatabaseTransactions;
    public function test_login(): void
    {
        $name = 'test99';
        $email = 'test99@example.com';
        $password = '123';

        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->postJson('/api/users/login', data: [
                'email' => $email,
                'password' => $password
            ]);

        $response->assertStatus(200);

        $token = $response->json('data')['token'];
        $this->assertNotNull($token);

        $response = $this
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->get('/api/users');
        $response->assertStatus(200);

    }

    private function login(string $email = "test@example.com", string $password = "123")
    {
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->postJson('/api/users/login', [
                'email' => $email,
                'password' => $password
            ]);

        $token = $response->json('data')['token'];
        // dd($token);
        return $token;
    }

    public function test_sport_create()
    {
        $token = $this->login();
        $sportNev = '111';
        $response = $this
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ])
            ->postJson('/api/sports', [
                "sportNev" => $sportNev
            ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('sports', ['sportNev' => $sportNev]);
        $sport = Sport::where('sportNev', $sportNev)->first();
        $this->assertNotNull($sport);

    }
}