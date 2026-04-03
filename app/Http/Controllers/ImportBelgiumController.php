<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\StockItem;
use App\Enums\CountryCode;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ImportBelgiumController extends Controller
{
    protected Collection $machines;

    public function __invoke()
    {
        $num_created = 0;
        $num_updated = 0;

        // Only fetch inventory that has been modified since the last import
        $lastModifiedAt = StockItem::where('country_code', CountryCode::BE)->max('modified_at');

        $client = new \GuzzleHttp\Client();
        $params = [
            'api_key' => 'd8e01451159750ad8cb5f76dd10d3e4b797d3e2d6a92cfa81baba532fc444b11',
            'action' => 'get_inventory',
        ];

        if ($lastModifiedAt) {
            $params['modified_after'] = $lastModifiedAt;
        }
        
        $response = $client->post('https://portalalex.jangkardang.synology.me/api/', ['json' => $params]);
        $body = $response->getBody()->getContents();
        $inventory = json_decode($body, true)['data']['inventory'] ?? null;

        if ($inventory) {
            // Fetch all Machines
            $this->machines = Machine::all();

            foreach ($inventory as $item) {
                /**
                 * Item object schema:
                 * 
                 * @machine_type string
                 * @machine_brand string
                 * @machine_model string
                 * @id int
                 * @modified_at string
                 * @serial_number string
                 */
                $stockId = 'BE' . $item['id'];
                $stockItem = StockItem::where('stock_id', $stockId)->first();

                if (!$stockItem) {
                    $stockItem = new StockItem();

                    // Set defaults
                    $stockItem->barcode = '';
                    $stockItem->frame_no = '';
                    $stockItem->license_plate_no = '';

                    $num_created++;
                } else {
                    $num_updated++;
                }
                
                $stockItem->country_code = CountryCode::BE;
                $stockItem->machine_id = $this->getMachineId($item['machine_brand'], $item['machine_model']);
                $stockItem->md5 = md5($stockId);
                $stockItem->modified_at = now();
                $stockItem->stock_id = $stockId;
                $stockItem->serial_no = $item['serial_number'];
                $stockItem->save();
            }
        }

        return sprintf('Created %d and updated %d stock items.', $num_created, $num_updated);
    }

    protected function getMachineId($brand, $model): int
    {
        // Sanitize model and brand
        $model = trim(str_ireplace($brand, '', $model));

        // Check if machine already exists
        $machine = $this->machines->first(function ($machine) use ($brand, $model) {
            return $machine->model === $model && Str::contains($machine->name, $brand, true);
        });

        if (!$machine) {
            // Machine does not exist, create
            $machine = Machine::create([
                'model' => $model,
                'name' => $brand,
            ]);
        }

        $this->machines->push($machine);
        return $machine->id;
    }
}
