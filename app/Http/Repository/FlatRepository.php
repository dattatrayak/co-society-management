<?php


namespace App\Http\Repository;

use App\Models\Flat;
use DB;
use Illuminate\Support\Facades\Auth;

class FlatRepository
{
    public function insertOrUpdateFlat($dataFlat, $nextData)
    { 
        $societyUserId = Auth::guard('society_user')->user()->id;

        $toatalFlat = $dataFlat->flat_count;
        $floor = $dataFlat->floor;
        $flat_no_start = $dataFlat->flat_no_start;
        $flat_per_floor = $dataFlat->flat_per_floor;
        $society_flat_types_id = (isset($nextData['society_flat_types_id'])) ? $nextData['society_flat_types_id'] : null;
        $maintance_per_month  = (isset($nextData['maintance_per_month'])) ? $nextData['maintance_per_month'] : null; 
        $flatEntry = [];
        for ($i = 0; $i < $floor; $i++) {

            if ($i > 0) {
                $flat_no_start = ($flat_no_start - ($flat_per_floor + 1)) + $dataFlat->flat_no_start;
            }

            for ($floorFlat = 0; $floorFlat < $flat_per_floor; $floorFlat++) {
                $flatDataSingle = array(
                    'society_id' => $societyUserId,
                    'building_id' => $dataFlat->id,
                    'flat_no' => $flat_no_start,
                    //'society_flat_types_id' => $society_flat_types_id,
                    'floor_number' => $i + 1,
                    'maintance_per_month' => $maintance_per_month,
                    'desc' => null,
                );
                if ($society_flat_types_id) {
                    $flatDataSingle['society_flat_types_id']  = $society_flat_types_id;
                }
                // dump($flatDataSingle);
                // die();
                $flatEntry[] = $flatDataSingle;
                $flat_no_start = $flat_no_start + 1;
            }
        }
        $flatChunks = array_chunk($flatEntry, 20);

        foreach ($flatChunks as $chunk) {
            foreach ($chunk as $flatsData) {

                Flat::updateOrCreate(
                    [
                        'society_id' => $flatsData['society_id'],
                        'building_id' => $flatsData['building_id'],
                        'flat_no' => $flatsData['flat_no']
                    ], // Condition to check for existence
                    $flatsData
                );
            }
        }
    }
}
