<?php

namespace App\Jobs;

use App\Enums\ActivityType;
use App\Enums\CountryCode;
use App\Models\Activity;
use App\Models\Import;
use App\Models\Machine;
use App\Models\StockItem;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ImportCSV implements ShouldQueue
{
    use Queueable;

    public Import $import;

    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    public function handle(): void
    {
        $path = Storage::path($this->import->path);

        if (!is_readable($path)) {
            throw new \Exception('Export is not readable: ' . $path);
        }

        $contents = file_get_contents($path);
        $contents = str_replace("\r", '', $contents);
        $lines = explode("\n", $contents);
        $newEntryCount = 0;
        $rowCount = count($lines) - 1;
        $updatedEntryCount = 0;

        // Get header row
        $header = array_shift($lines);
        $headerCols = explode(',', $header);
        $this->import->update([
            'checksum' => md5($header),
            'row_count' => $rowCount,
        ]);

        // Get CSV column to DB schema mappings
        $mappings = [
            'stock_id' => array_search('Equipment Object GWW Machines[No_]', $headerCols),
            'name' => array_search('Equipment Object GWW Machines[Description]', $headerCols),
            'model' => array_search('Equipment Object GWW Machines[Equipment Model]', $headerCols),
            'serial_no' => array_search('Equipment Object GWW Machines[Serial No_]', $headerCols),
            'frame_no' => array_search('Equipment Object GWW Machines[Frame No_]', $headerCols),
            'license_plate_no' => array_search('Equipment Object GWW Machines[Licence Plate No_]', $headerCols),
            'barcode' => array_search('Equipment Object GWW Machines[Barcode]', $headerCols),
            'modified_at' => array_search('Equipment Object GWW Machines[Modified On]', $headerCols),
        ];

        // Check if header column count matches mappings
        if (count($headerCols) !== count($mappings)) {
            throw new \Exception('Header row does not match mappings.');
        }

        // Get all existing stock IDs (stock_id => modified_at)
        $existingStockIds = StockItem::pluck('modified_at', 'stock_id');

        // Get all existing machines (id => model)
        $existingMachineModels = Machine::pluck('model', 'id');

        for ($i = 0; $i < count($lines); $i++) {
            $line = $lines[$i];
            $cols = explode(',', $line);

            // Get fields
            $barcode = $cols[$mappings['barcode']] ?? '';
            $frameNo = $cols[$mappings['frame_no']] ?? '';
            $licensePlateNo = $cols[$mappings['license_plate_no']] ?? '';
            $model = $cols[$mappings['model']] ?? '';
            $name = $cols[$mappings['name']] ?? '';
            $serialNo = $cols[$mappings['serial_no']] ?? '';
            $stockId = $cols[$mappings['stock_id']] ?? '';
            $modifiedAt = $cols[$mappings['modified_at']] ?? '';

            if (empty($stockId)) {
                // ID is empty, skip
                continue;
            }

            // Search for machine model
            $machineId = $existingMachineModels->search($cols[$mappings['model']]);

            if (!$machineId) {
                // Machine does not exist, create
                $machine = Machine::create([
                    'model' => $model,
                    'name' => $name,
                ]);
                $machineId = $machine->id;

                // Add to existing machine models
                $existingMachineModels[$machineId] = $model;
            }

            // Check if stock item already exists
            if ($existingStockIds->has($stockId)) {
                if (empty($modifiedAt)) {
                    $modifiedAt = Carbon::now()->format('Y-m-d H:i:s');
                } else {
                    $modifiedAt = Carbon::parse($modifiedAt)->format('Y-m-d H:i:s');
                }

                if ($existingStockIds[$stockId] != $modifiedAt) {
                    // Stock item already exists, but modified_at is different, update
                    StockItem::where('stock_id', $stockId)->update([
                        'barcode' => $barcode,
                        'country_code' => CountryCode::NL,
                        'frame_no' => $frameNo,
                        'import_id' => $this->import->id,
                        'license_plate_no' => $licensePlateNo,
                        'machine_id' => $machineId,
                        'modified_at' => $modifiedAt,
                        'serial_no' => $serialNo,
                    ]);

                    // Increment updated entry count
                    $updatedEntryCount++;
                }

                // Skip
                continue;
            }

            // Set empty modified_at to current date
            if (empty($modifiedAt)) {
                $modifiedAt = Carbon::now();
            }

            // Insert stock item
            StockItem::create([
                'barcode' => $barcode,
                'country_code' => CountryCode::NL,
                'frame_no' => $frameNo,
                'import_id' => $this->import->id,
                'license_plate_no' => $licensePlateNo,
                'machine_id' => $machineId,
                'md5' => md5($stockId),
                'modified_at' => $modifiedAt,
                'serial_no' => $serialNo,
                'stock_id' => $stockId,
            ]);

            // Increment new entry count
            $newEntryCount++;
        }

        // Update import
        $this->import->update([
            'new_entry_count' => $newEntryCount,
            'processed_at' => now(),
            'updated_entry_count' => $updatedEntryCount,
        ]);

        // Finally, log import activity
        Activity::log(ActivityType::IMPORT_PROCESSED, [
            'new' => $newEntryCount,
            'updated' => $updatedEntryCount,
        ]);
    }
}
