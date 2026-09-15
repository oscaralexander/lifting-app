<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->json('photos')->nullable()->after('images');
        });

        DB::table('inspections')
            ->whereNotNull('images')
            ->orderBy('id')
            ->each(function (object $inspection) {
                $photos = collect(json_decode($inspection->images, true) ?: [])
                    ->filter(fn ($image) => is_string($image) && $image !== '')
                    ->map(fn (string $image) => [
                        'image' => str_starts_with($image, 'http') ? $image : Storage::disk('public')->url($image),
                        'comment' => null,
                    ])
                    ->values();

                DB::table('inspections')
                    ->where('id', $inspection->id)
                    ->update(['photos' => $photos->isEmpty() ? null : $photos->toJson(JSON_UNESCAPED_UNICODE)]);
            });
    }

    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropColumn('photos');
        });
    }
};
