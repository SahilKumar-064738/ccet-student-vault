return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->enum('upload_type', ['question_paper', 'notes', 'assignment', 'mst', 'other']);
            $table->string('teacher_name')->nullable();
            $table->integer('year');
            $table->integer('semester')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->bigInteger('file_size');
            $table->string('mime_type', 100);
            $table->text('description')->nullable();
            $table->integer('exam_year')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_comment')->nullable();
            $table->boolean('is_public')->default(true);
            $table->integer('downloads_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['branch_id', 'year', 'status']);
            $table->index(['subject_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('upload_type');
            $table->index('created_at');
        });
        
        // Full-text search indexes
        DB::statement('CREATE INDEX uploads_teacher_name_idx ON uploads USING gin(to_tsvector(\'english\', teacher_name))');
        DB::statement('CREATE INDEX uploads_description_idx ON uploads USING gin(to_tsvector(\'english\', description))');
    }

    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};