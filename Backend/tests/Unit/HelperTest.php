<?php

namespace Tests\Unit;

use App\Helpers\VeletlenVaros;
use Tests\TestCase;

class HelperTest extends TestCase
{
    public function test_helper_function_veletlenvaros()
    {
        for ($i = 0; $i < 100; $i++) {
            $veletlenVaros = VeletlenVaros::randomVaros();
            $varosok = ["Budapest", "Debrecen", "Pécs", "Szolnok"];
            $this->assertContains($veletlenVaros, $varosok, "Hibás -> ". $veletlenVaros);
        }
    }
}