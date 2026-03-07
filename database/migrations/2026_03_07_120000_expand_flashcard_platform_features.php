<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('password');
            $table->unsignedInteger('experience_points')->default(0)->after('role');
            $table->unsignedInteger('daily_streak')->default(0)->after('experience_points');
            $table->timestamp('last_studied_at')->nullable()->after('daily_streak');
        });

        Schema::table('decks', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('visibility')->default('private')->after('description');
            $table->string('category')->nullable()->after('visibility');
            $table->json('tags')->nullable()->after('category');
            $table->foreignId('source_deck_id')->nullable()->after('tags')->constrained('decks')->nullOnDelete();
        });

        Schema::table('flashcards', function (Blueprint $table) {
            $table->string('image_url')->nullable()->after('back_content');
            $table->string('audio_url')->nullable()->after('image_url');
            $table->string('hint')->nullable()->after('audio_url');
        });

        Schema::table('study_progress', function (Blueprint $table) {
            $table->timestamp('next_review_at')->nullable()->after('last_reviewed_at');
            $table->unsignedInteger('review_count')->default(0)->after('next_review_at');
            $table->unsignedInteger('correct_streak')->default(0)->after('review_count');
        });

        Schema::create('deck_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deck_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['deck_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deck_reviews');

        Schema::table('study_progress', function (Blueprint $table) {
            $table->dropColumn(['next_review_at', 'review_count', 'correct_streak']);
        });

        Schema::table('flashcards', function (Blueprint $table) {
            $table->dropColumn(['image_url', 'audio_url', 'hint']);
        });

        Schema::table('decks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('source_deck_id');
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['visibility', 'category', 'tags']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'experience_points', 'daily_streak', 'last_studied_at']);
        });
    }
};
