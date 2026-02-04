<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Vozilo;
use App\Models\NamjenaVozila;

class VoziloPrviTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    

    /** @test */
    public function lista_vozila_mora_biti_blokirana_ako_postoji_istekla_registracija(): void
    {
        // 1️⃣ Namjena
        $namjena = NamjenaVozila::create([
            'naziv' => 'Putničko',
        ]);

        // 2️⃣ Vozilo s isteklom registracijom
        Vozilo::create([
            'naziv' => 'Iveco',
            'tip' => 'Daily',
            'motor' => 'dizel',
            'registracija' => 'ST1111AA',
            'istek_registracije' => now()->subDays(5),
            'namjenaid' => $namjena->id,
        ]);

        // 3️⃣ Pristup listi vozila
        $response = $this->get('/vozila');

        // 4️⃣ OČEKUJEMO BLOKADU (ali zasad će pasti!)
        $response->assertStatus(403);
    }
}
