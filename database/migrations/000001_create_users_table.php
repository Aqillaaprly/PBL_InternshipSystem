    <?php
    // File: 2024_01_01_000001_create_users_table.php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('users', function (Blueprint $table) {
                $table->id('user_id');
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->string('password');
                $table->enum('role', ['student', 'supervisor', 'company', 'admin']);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('users');
        }
    };