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
        Schema::table('applications', function (Blueprint $table) {
            // make user_id nullable (may require doctrine/dbal to run)
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // add guest fields
            if (! Schema::hasColumn('applications', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('applications', 'guest_email')) {
                $table->string('guest_email')->nullable()->after('guest_name');
            }
        });

        // ensure foreign key sets null on delete
        // Note: altering foreign key behavior may require manual DB steps depending on driver
        try {
            \DB::statement('ALTER TABLE applications DROP FOREIGN KEY applications_user_id_foreign');
            \DB::statement('ALTER TABLE applications ADD CONSTRAINT applications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
        } catch (\Throwable $e) {
            // ignore if DB driver doesn't support or constraint name differs
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (Schema::hasColumn('applications', 'guest_email')) {
                $table->dropColumn('guest_email');
            }
            if (Schema::hasColumn('applications', 'guest_name')) {
                $table->dropColumn('guest_name');
            }

            // attempt to make user_id not nullable again
            try {
                $table->unsignedBigInteger('user_id')->nullable(false)->change();
                \DB::statement('ALTER TABLE applications DROP FOREIGN KEY applications_user_id_foreign');
                \DB::statement('ALTER TABLE applications ADD CONSTRAINT applications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
            } catch (\Throwable $e) {
                // ignore
            }
        });
    }
};
