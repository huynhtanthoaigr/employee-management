<?php
// database/migrations/2025_09_04_000001_create_shifts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Sáng / Chiều
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });

        // Seed cơ bản
        DB::table('shifts')->insert([
            ['name' => 'Sáng', 'start_time' => '08:00:00', 'end_time' => '16:00:00', 'created_at'=>now(),'updated_at'=>now()],
            ['name' => 'Chiều', 'start_time' => '14:00:00', 'end_time' => '22:00:00', 'created_at'=>now(),'updated_at'=>now()],
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('shifts');
    }
};
