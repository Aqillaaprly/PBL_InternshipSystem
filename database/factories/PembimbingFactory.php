<?php

namespace Database\Factories;

use App\Models\Pembimbing;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class PembimbingFactory extends Factory
{
    protected $model = Pembimbing::class;

    public function definition(): array
    {
        // Opsional: Buat atau kaitkan dengan User yang memiliki role dosen
        $dosenRole = Role::where('name', 'dosen')->first();
        $user = null;
        if ($dosenRole) {
             // Cari user dosen yang belum punya detail pembimbing atau buat baru
            $user = User::factory()->create([
                'role_id' => $dosenRole->id,
                'username' => $this->faker->unique()->userName, // Atau NIP jika username adalah NIP
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
            ]);
        }

        $namaLengkap = $user ? $user->name : $this->faker->name . ', ' . $this->faker->randomElement(['S.Kom., M.Kom.', 'S.T., M.T.', 'Dr.']);
        $emailInstitusi = $user ? $user->email : $this->faker->unique()->companyEmail;


        return [
            'user_id' => $user ? $user->id : null,
            'nip' => $this->faker->unique()->numerify('19#############'), // Contoh format NIP 18 digit
            'nama_lengkap' => $namaLengkap,
            'email_institusi' => $emailInstitusi,
            'nomor_telepon' => $this->faker->e164PhoneNumber,
            'jabatan_fungsional' => $this->faker->randomElement(['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Profesor']),
            'program_studi_homebase' => $this->faker->randomElement(['Teknik Informatika', 'Sistem Informasi Bisnis']), // Diubah di sini
            'bidang_keahlian_utama' => $this->faker->sentence(3),
            'kuota_bimbingan_aktif' => $this->faker->numberBetween(0, 5),
            'maks_kuota_bimbingan' => $this->faker->randomElement([8, 10, 12]),
            'status_aktif' => $this->faker->boolean(90), // 90% kemungkinan true
        ];
    }
}