<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Container\Attributes\DB as AttributesDB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $valami = true;
        // dd($valami);
        $this->assertTrue($valami);
    }

    public function test_sajat_test(): void
    {
        $valami = 2;
        $this->assertGreaterThan(1,$valami);
    }
}
