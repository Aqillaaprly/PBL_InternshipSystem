<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mahasiswa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
    $faker = \Faker\Factory::create('id_ID'); // Tambahkan instance faker lokal jika belum

    // Buat user baru untuk setiap mahasiswa dari factory
    $user = User::factory()->create([
        'role_id' => $mahasiswaRole ? $mahasiswaRole->id : null,
        'username' => $faker->unique()->numerify('214172####'), // Pastikan ini unik
        'name' => $faker->name(),
        'email' => $faker->unique()->safeEmail(), // Pastikan ini unik
    ]);

    return [
        'user_id' => $user->id,
        'nim' => $user->username,
        'nama' => $user->name,
        'email' => $user->email,
        'kelas' => $faker->randomElement(['TI-3A', 'TI-3B', 'TI-3C', 'SIB-3A', 'SI-3B']),
        'program_studi' => $faker->randomElement(['Teknik Informatika', 'Sistem Informasi Bisnis']),
        'nomor_hp' => $faker->unique()->e164PhoneNumber(),
        'alamat' => $faker->address(),
    ];
}
}