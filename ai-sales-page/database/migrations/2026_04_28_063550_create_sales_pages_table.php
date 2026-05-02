<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Input fields
            $table->string('product_name');
            $table->text('description');
            $table->text('features');           // comma-separated
            $table->string('target_audience');
            $table->string('price');
            $table->text('unique_selling_point');

            // AI output
            $table->longText('generated_html');
            $table->string('hero_image_url')->nullable();

            // Design template: 'modern' | 'bold'
            $table->string('template')->default('modern');

            // Regeneration tracking
            // null  = original generation
            // set   = refined version referencing parent_id
            $table->foreignId('parent_id')->nullable()->constrained('sales_pages')->nullOnDelete();
            $table->text('feedback')->nullable();
            $table->unsignedSmallInteger('version')->default(1);

            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_pages');
    }
};
