return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code', 20)->nullable();
            $table->integer('year');
            $table->integer('semester')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['branch_id', 'code']);
            $table->index(['branch_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};