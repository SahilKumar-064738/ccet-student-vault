return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('year')->nullable();
            $table->integer('semester')->nullable();
            $table->enum('role', ['student', 'teacher', 'cr', 'admin'])->default('student');
            $table->string('phone', 15)->nullable();
            $table->string('roll_number', 20)->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->integer('failed_login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
            
            $table->index(['branch_id', 'year']);
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn([
                'branch_id', 'year', 'semester', 'role', 'phone',
                'roll_number', 'is_active', 'last_login_at',
                'failed_login_attempts', 'locked_until'
            ]);
        });
    }
};