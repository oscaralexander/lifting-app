<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Attachments are never inspected according to TCVT.
     */
    public function up(): void
    {
        DB::table('inspections')
            ->whereIn('inspection_object_id', DB::table('inspection_objects')->where('type', 'attachment')->select('id'))
            ->update(['type' => 'periodical']);
    }

    public function down(): void
    {
        //
    }
};
