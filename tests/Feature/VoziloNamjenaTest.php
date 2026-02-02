<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Vozilo;
use App\Models\NamjenaVozila;

class VoziloNamjenaTest extends TestCase
{
    /**
     * A basic feature test example.
     */
 use RefreshDatabase;

    /** @test */
    public function vozilo_pripada_namjeni(): void
    {
        // 1️⃣ Kreiramo namjenu
        $namjena = NamjenaVozila::create([
            'naziv' => 'Osobno',
        ]);

        // 2️⃣ Kreiramo vozilo koje pripada toj namjeni
        $vozilo = Vozilo::create([
            'naziv' => 'BMW',
            'tip' => '320D',
            'motor' => 'dizel',
            'registracija' => 'ZG1234AA',
            'istek_registracije' => now()->addYear(),
            'namjenaid' => $namjena->id,
        ]);

        // 3️⃣ Dohvatimo vozilo s eager loadingom
        $vozilo = Vozilo::with('namjena')->first();

        // 4️⃣ Provjere
        $this->assertNotNull($vozilo->namjena);
        $this->assertEquals('Osobno', $vozilo->namjena->id);
    }

    /** @test */
    public function middleware_blokira_prikaz_vozila_ako_postoji_istekla_registracija(): void
    {
        // 1️⃣ Namjena
        $namjena = NamjenaVozila::create([
            'naziv' => 'Putničko',
        ]);

        // 2️⃣ Vozilo s ISTEKLOM registracijom
        Vozilo::create([
            'naziv' => 'Iveco',
            'tip' => 'Daily',
            'motor' => 'dizel',
            'registracija' => 'ST9999TT',
            'istek_registracije' => now()->subDays(10), // isteklo
            'namjenaid' => $namjena->id,
        ]);

        // 3️⃣ Pokušaj pristupa ruti
        $response = $this->get(route('vozila.index'));

        // 4️⃣ Middleware mora blokirati
        $response->assertStatus(403);
        $response->assertSee('Postoji vozilo s isteklom registracijom');
    }
}
