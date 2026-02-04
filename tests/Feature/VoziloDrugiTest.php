<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Vozilo;
class VoziloDrugiTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /** @test */
    public function vozilo_ne_smije_biti_kreirano_bez_namjene(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Vozilo::create([
            'naziv' => 'Test vozilo',
            'tip' => 'Test',
            'motor' => 'dizel',
            'registracija' => 'TEST123',
            'istek_registracije' => now()->addYear(),

            // ❌ namjenaid NAMJERNO NEDOSTAJE
        ]);
    }
}
