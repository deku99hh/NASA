<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $location = $user->location()->with('records')->first();

        if (!$location) {
            return redirect()->route('chat-fern-1.fern1');
        }

        $records = $location->records;
        $latest = $records->last();
        $suggestions = null;
        $apiError = null;

        // قوالب البطاقات الثلاث لربطها برد الـ AI
        $cardTemplates = [
            [
                'id'        => 'max_profit',
                'title'     => 'الربح القصوى',
                'theme'     => 'theme-profit',
                'art_img'   => 'ooo.jpg',
                'badge_bg'  => '#cff982',
                'char_img'  => 'https://placehold.co/64x64/fff3b0/1a1a1a?text=Ice+King',
                'char_name' => 'ملك الجليد',
                'desc_prefix' => 'استراتيجية التركيز على المال',
            ],
            [
                'id'        => 'soil_health',
                'title'     => 'تحسين التربة',
                'theme'     => 'theme-soil',
                'art_img'   => 'Bmo.jpg',
                'badge_bg'  => '#fff3b0',
                'char_img'  => 'https://placehold.co/64x64/cff982/1a1a1a?text=Fern',
                'char_name' => 'فيرن',
                'desc_prefix' => 'استراتيجية التركيز على صحة الأرض',
            ],
            [
                'id'        => 'perfect_balance',
                'title'     => 'التوازن المثالي',
                'theme'     => 'theme-balance',
                'art_img'   => 'hhh.jpg',
                'badge_bg'  => '#e0fbfc',
                'char_img'  => 'https://placehold.co/64x64/e0fbfc/1a1a1a?text=Huntress',
                'char_name' => 'الصيادة',
                'desc_prefix' => 'استراتيجية التوازن بين المال والأرض',
            ],
        ];

        if ($latest) {
            try {
                $response = Http::timeout(15)->post('http://127.0.0.1:5000/predict', [
                    'Year'              => $latest->year,
                    'MONTH'             => $latest->month,
                    'precipitation_mm'  => $latest->precipitation_mm,
                    'T2M'               => $latest->t2m,
                    'T2M_MAX'           => $latest->max,
                    'RH2M'              => $latest->RH2M,
                    'ALLSKY_SFC_SW_DWN' => $latest->ALLSKY_SFC_SW_DWN,
                    'elevation_m'       => $latest->elevation_m,
                    'slope_deg'         => $latest->slope_deg,
                    'clay'              => $latest->clay,
                    'sand'              => $latest->sand,
                    'silt'              => $latest->silt,
                    'ph'                => $latest->ph,
                    'om'                => $latest->om,
                    'ksat'              => $latest->ksat,
                    'ET_mm'             => $latest->ET_mm,
                    'soil_moisture'     => $latest->soil_moisture,
                    'NDVI'              => $latest->NDVI,
                ]);

                if ($response->successful() && isset($response->json()['top_3'])) {
                    $top3 = $response->json()['top_3'];
                    $suggestions = [];

                    foreach ($top3 as $index => $item) {
                        $tpl = $cardTemplates[$index] ?? $cardTemplates[0];
                        $suggestions[] = [
                            'id'         => $tpl['id'],
                            'title'      => $tpl['title'],
                            'crop_name'  => $item['crop_name'],
                            'theme'      => $tpl['theme'],
                            'art_img'    => asset($tpl['art_img']),
                            'short_desc' => $tpl['desc_prefix'] . ' (' . $item['crop_name'] . ')',
                            'badge'      => 'ملاءمة ' . $item['confidence'] . '%',
                            'badge_bg'   => $tpl['badge_bg'],
                            'char_img'   => $tpl['char_img'],
                            'char_name'  => $tpl['char_name'],
                            'duration'   => $item['duration'],
                            'water'      => $item['water'],
                            'temp'       => $item['temp'],
                            'reason'     => $item['reason'],
                        ];
                    }
                } else {
                    $apiError = 'خطأ من سيرفر الموديل: ' . $response->status();
                }
            } catch (\Exception $e) {
                $apiError = 'سيرفر Python غير متصل حالياً (يتم عرض بيانات تجريبية)';
            }
        }

        return view('dashboard', [
            'location'    => $location,
            'records'     => $records,
            'suggestions' => $suggestions,
            'apiError'    => $apiError,
        ]);
    }
}