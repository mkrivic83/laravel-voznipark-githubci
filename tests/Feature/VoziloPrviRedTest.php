<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\NamjenaVozila;

class VoziloPrviRedTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /** @test */
    public function nije_dozvoljeno_kreirati_vozilo_bez_naziva(): void
    {
        $namjena = NamjenaVozila::create([
            'naziv' => 'Osobno',
        ]);

        $response = $this->post('/vozila', [
            // ❌ NAMJERNO NEMA 'naziv'
            'tip' => 320,
            'motor' => 'dizel',
            'registracija' => 'ZG0001AA',
            'istek_registracije' => now()->addYear()->format('Y-m-d'),
            'namjenaid' => $namjena->id,
        ]);

        // 🔴 OČEKUJEMO VALIDACIJSKU GREŠKU
        $response->assertSessionHasErrors('naziv');
    }
}
