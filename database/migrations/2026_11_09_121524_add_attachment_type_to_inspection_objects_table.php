<?php

use App\Enums\InspectionObject\Attachment\Type as AttachmentType;
use App\Enums\InspectionObject\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspection_objects', function (Blueprint $table) {
            $table->enum('type', array_column(Type::cases(), 'value'))->nullable()->change();
            $table->enum('attachment_type', array_column(AttachmentType::cases(), 'value'))->nullable()->after('type');
            $table->string('attachment_type_other')->nullable()->after('attachment_type');
        });
    }

    public function down(): void
    {
        Schema::table('inspection_objects', function (Blueprint $table) {
            $table->dropColumn(['attachment_type', 'attachment_type_other']);
            $table->enum('type', ['crane', 'operator_lift'])->nullable()->change();
        });
    }
};
