return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('otp', 6);
            $table->enum('type', ['email_verification', 'password_reset'])->default('email_verification');
            $table->integer('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->boolean('used')->default(false);
            $table->timestamps();
            
            $table->index(['email', 'type', 'used']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};