<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Record;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ClimateDataSeeder extends Seeder
{
    public function run(): void
    {

        $response = Http::timeout(180)->get('https://mohamedsulta.pythonanywhere.com/api/nested');

        if ($response->failed()) {
            throw new \Exception('فشل الاتصال بالـ API: ' . $response->status());
        }

        $data = $response->json();

        $locationCache = [];
        $recordsBatch = [];
        $now = now();

        foreach ($data as $year => $months) {
            foreach ($months as $month => $latitudes) {
                foreach ($latitudes as $lat => $longitudes) {
                    foreach ($longitudes as $lon => $metrics) {
                        
                        $coordKey = "{$lat}_{$lon}";

                        if (!isset($locationCache[$coordKey])) {
                            $location = Location::firstOrCreate([
                                'latitude'  => (float) $lat,
                                'longitude' => (float) $lon,
                            ]);
                            $locationCache[$coordKey] = $location->id;
                        }

                        $recordsBatch[] = [
                            'location_id'       => $locationCache[$coordKey],
                            'year'              => (int) $year,
                            'month'             => (int) $month,
                            't2m'               => $metrics['t2m'] ?? null,
                            'max'               => $metrics['max'] ?? null,
                            'precipitation_mm'  => $metrics['precipitation_mm'] ?? null,
                            'ET_mm'             => $metrics['ET_mm'] ?? null,
                            'RH2M'              => $metrics['RH2M'] ?? null,
                            'ALLSKY_SFC_SW_DWN' => $metrics['ALLSKY_SFC_SW_DWN'] ?? null,
                            'soil_moisture'     => $metrics['soil_moisture'] ?? null,
                            'NDVI'              => $metrics['NDVI'] ?? null,
                            'elevation_m'       => $metrics['elevation_m'] ?? null,
                            'slope_deg'         => $metrics['slope_deg'] ?? null,
                            'clay'              => $metrics['clay'] ?? null,
                            'sand'              => $metrics['sand'] ?? null,
                            'silt'              => $metrics['silt'] ?? null,
                            'ph'                => $metrics['ph'] ?? null,
                            'om'                => $metrics['om'] ?? null,
                            'ksat'              => $metrics['ksat'] ?? null,
                            'created_at'        => $now,
                            'updated_at'        => $now,
                        ];

                        if (count($recordsBatch) >= 500) {
                            Record::insert($recordsBatch);
                            $recordsBatch = [];
                        }
                    }
                }
            }
        }

        if (!empty($recordsBatch)) {
            Record::insert($recordsBatch);
        }
    }
}