<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Role; // Import Role
use Illuminate\Support\Facades\Storage; // Import Storage

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
        $placeholderLogo = 'logos/default_factory_logo.png';

        // Optional: ensure placeholder exists for factory too
        if (!Storage::disk('public')->exists($placeholderLogo)) {
            // Storage::disk('public')->makeDirectory('logos'); // Ensure directory exists
            // Storage::disk('public')->put($placeholderLogo, 'This is a factory placeholder image.');
            // For CLI context, just use the path string if file presence is not critical for seeding itself
        }
        
        // Find or create a user with the 'perusahaan' role
        $perusahaanRole = Role::where('name', 'perusahaan')->first();
        if (!$perusahaanRole) {
            // This should not happen if RoleSeeder runs first, but as a fallback:
            $perusahaanRole = Role::firstOrCreate(['name' => 'perusahaan']);
        }

        return [
            // If you want to ensure each factory-created company has its own user:
            'user_id' => function () use ($perusahaanRole) {
                return User::factory()->create([
                    'name' => $this->faker->name, // Add name for user
                    'email' => $this->faker->unique()->safeEmail, // Add unique email
                    'username' => $this->faker->unique()->userName, // Add unique username
                    'role_id' => $perusahaanRole->id
                ])->id;
            },
            'nama_perusahaan' => $this->faker->company,
            'alamat' => $this->faker->address,
            'kota' => $this->faker->city,
            'provinsi' => $this->faker->state,
            'kode_pos' => $this->faker->postcode,
            'telepon' => $this->faker->unique()->phoneNumber,
            'email_perusahaan' => $this->faker->unique()->companyEmail,
            'website' => 'https://www.' . $this->faker->domainName,
            'deskripsi' => $this->faker->paragraph(3),
            'logo_path' => $placeholderLogo, // Crucial: Provide a default logo path
            'status_kerjasama' => $this->faker->randomElement(['Aktif', 'Non-Aktif', 'Review']),
        ];
    }
}