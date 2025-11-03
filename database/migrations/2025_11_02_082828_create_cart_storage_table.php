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
            Schema::create('cart_storage', function (Blueprint $table) {
                // Kolom ID akan menyimpan ID user (misal: "user_1", "user_2")
                $table->string('id')->primary();
                $table->longText('cart_data');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('cart_storage');
        }
    };
    
