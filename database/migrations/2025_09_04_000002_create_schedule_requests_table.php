<?php
// database/migrations/2025_09_04_000002_create_schedule_requests_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('schedule_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->enum('status', ['present','morning_off','afternoon_off','full_day_off'])->default('present');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('schedule_requests');
    }
};
