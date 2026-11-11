<?php

use App\Enums\InspectionObject\Attachment\Type as AttachmentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspection_objects', function (Blueprint $table) {
            $table->enum('attachment_type', array_column(AttachmentType::cases(), 'value'))->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('inspection_objects', function (Blueprint $table) {
            $table->enum('attachment_type', ['fork_carriage', 'material_bucket', 'aerial_work_platform_bucket', 'other'])->nullable()->change();
        });
    }
};
