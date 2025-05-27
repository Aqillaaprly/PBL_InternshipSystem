<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Jika Anda ingin mengaitkan dengan user perusahaan yang sudah ada atau membuat user baru
            // 'user_id' => User::whereHas('role', fn($q) => $q->where('name', 'perusahaan'))->inRandomOrder()->first()?->id,
            // Atau buat user baru dengan role perusahaan jika belum ada
            // 'user_id' => function () {
            //     return User::factory()->create(['role_id' => Role::where('name', 'perusahaan')->firstOrFail()->id])->id;
            // },
            'nama_perusahaan' => $this->faker->company,
            'alamat' => $this->faker->address,
            'kota' => $this->faker->city,
            'provinsi' => $this->faker->state,
            'kode_pos' => $this->faker->postcode,
            'telepon' => $this->faker->unique()->phoneNumber,
            'email_perusahaan' => $this->faker->unique()->companyEmail,
            'website' => 'https://www.' . $this->faker->domainName,
            'deskripsi' => $this->faker->paragraph(3),
            'logo_path' => null, // Anda bisa set path ke logo placeholder jika ada
            'status_kerjasama' => $this->faker->randomElement(['Aktif', 'Non-Aktif', 'Review']),
        ];
    }
}