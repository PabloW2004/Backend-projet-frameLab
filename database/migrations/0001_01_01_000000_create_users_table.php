<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('first_name');
            $table->string('email')->unique();
            $table->string('password');

            $table->enum('role', ['user', 'admin'])->default('user');

            $table->string('token_validation')->nullable();
            $table->timestamp('date_validation')->nullable();

            $table->rememberToken();
            $table->timestamps('created_at');
        });


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamps('created_at');
        });


        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
            $table->timestamps('created_at');
        });


        Schema::create('challenges', function (Blueprint $table) {
            $table->id();

            $table->string('theme');
            $table->text('description');
            $table->string('categorie');
            $table->string('img_primary');
            $table->timestamps('created_at');

            $table->timestamp('starting_date');
            $table->timestamp('finishing_date');

            $table->boolean('actif')->default(true);
        });


        Schema::create('participations', function (Blueprint $table) {
            $table->id();

            $table->string('img_url');
            $table->timestamp('date_submission')->useCurrent();

            $table->enum(
                'moderation_issue',
                ['SUSPICIOUS', 'MODERATED']
            )->nullable()->default(NULL);

            $table->boolean('masked')->default(false);

            $table->foreignId('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('id_challenge')
                ->references('id')
                ->on('challenges')
                ->onDelete('cascade');

            $table->unique(['id_user', 'id_challenge']);
            $table->timestamps('created_at');
        });


        Schema::create('votes', function (Blueprint $table) {
            $table->foreignId('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('id_participation')
                ->references('id')
                ->on('participations')
                ->onDelete('cascade');

            $table->unsignedTinyInteger('creative_note');
            $table->unsignedTinyInteger('technical_note');
            $table->unsignedTinyInteger('theme_note');

            $table->timestamps('created_at');

            $table->primary(['id_user', 'id_participation']);
        });


        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->unique();
            $table->timestamps('created_at');
        });


        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->text('content');

            $table->timestamp('date_creation')->useCurrent();
            $table->timestamp('date_modification')->nullable();

            $table->enum(
                'moderation_issue',
                ['SUSPICIOUS', 'MODERATED']
            )->nullable();

            $table->foreignId('id_participation')
                ->references('id')
                ->on('participations')
                ->onDelete('cascade');

            $table->foreignId('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->timestamps('created_at');
        });


        Schema::create('tag_participation', function (Blueprint $table) {
            $table->foreignId('id_participation')
                ->references('id')
                ->on('participations')
                ->onDelete('cascade');

            $table->foreignId('id_tag')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade');
            
            $table->timestamps('created_at');

            $table->primary(['id_participation', 'id_tag']);
        });


        Schema::create('signalements', function (Blueprint $table) {
            $table->id();

            $table->text('motif');
            $table->timestamp('date_signalement')->useCurrent();

            $table->foreignId('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('id_participation')
                ->nullable()
                ->references('id')
                ->on('participations')
                ->onDelete('cascade');

            $table->foreignId('id_commentaire')
                ->nullable()
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');

            $table->unique(['id_user', 'id_participation']);
            $table->unique(['id_user', 'id_commentaire']);

            $table->timestamps('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signalements');
        Schema::dropIfExists('etiqueter');
        Schema::dropIfExists('votes');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('participations');
        Schema::dropIfExists('challenges');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
