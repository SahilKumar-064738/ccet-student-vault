return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('upload_id')->constrained()->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->enum('action', ['approve', 'reject']);
            $table->text('comment')->nullable();
            $table->timestamps();
            
            $table->index(['upload_id', 'created_at']);
            $table->index('approver_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};