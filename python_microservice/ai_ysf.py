from flask import Flask, request, jsonify
import xgboost as xgb
import numpy as np
import json

app = Flask(__name__)


model = xgb.XGBClassifier()
model.load_model('crop_model.json')

with open('meta.json', 'r', encoding='utf-8') as f:
    meta = json.load(f)

FEATURE_NAMES = meta['features']
CLASS_NAMES   = meta['classes']


EXCLUDED_CLASSES = {
    'Deciduous Forest', 'Mixed Forest', 'Woody Wetlands', 
    'Grassland/Pasture', 'Fallow/Idle Cropland', 'Other Crops', 
    'Other Hay/Non Alfalfa', 'Sod/Grass Seed'
}


CROP_INFO = {
    'Alfalfa': {'ar': 'برسيم حجازي (Alfalfa)', 'duration': '60 - 90 يوم', 'water': 'مرتفع', 'temp': '18°C - 28°C', 'reason': 'يثبت النيتروجين في التربة ويعيد خصوبتها بشكل ممتاز.'},
    'Barley': {'ar': 'شعير (Barley)', 'duration': '90 - 110 يوم', 'water': 'منخفض', 'temp': '12°C - 22°C', 'reason': 'يتحمل ملوحة التربة والجفاف ويحافظ على تماسك الأرض.'},
    'Canola': {'ar': 'كانولا (Canola)', 'duration': '100 - 120 يوم', 'water': 'متوسط', 'temp': '15°C - 25°C', 'reason': 'محصول زيتي ذو عائد اقتصادي مرتفع ويكسر دورة آفات الحبوب.'},
    'Carrots': {'ar': 'جزر (Carrots)', 'duration': '70 - 80 يوم', 'water': 'متوسط', 'temp': '16°C - 24°C', 'reason': 'محصول جذري يفكك التربة ويستفيد من قوام الرمل والطمي لديك.'},
    'Corn': {'ar': 'ذرة (Corn)', 'duration': '90 - 110 يوم', 'water': 'مرتفع', 'temp': '22°C - 32°C', 'reason': 'إنتاجية عالية وعائد تجاري سريع مع الإشعاع الشمسي المتوفر.'},
    'Garlic': {'ar': 'ثوم (Garlic)', 'duration': '120 - 150 يوم', 'water': 'متوسط', 'temp': '13°C - 24°C', 'reason': 'يطهر التربة طبيعياً من الفطريات ويحقق ربحية ممتازة.'},
    'Mint': {'ar': 'نعناع (Mint)', 'duration': '60 - 75 يوم', 'water': 'مرتفع', 'temp': '18°C - 28°C', 'reason': 'محصول عطري مستدام ينمو بكفاءة عالية في رطوبة تربتك الحالية.'},
    'Oats': {'ar': 'شوفان (Oats)', 'duration': '85 - 100 يوم', 'water': 'منخفض - متوسط', 'temp': '14°C - 22°C', 'reason': 'يحسن بنية التربة العضوية ويقاوم الأعشاب الضارة.'},
    'Onions': {'ar': 'بصل (Onions)', 'duration': '100 - 130 يوم', 'water': 'متوسط', 'temp': '15°C - 25°C', 'reason': 'مناسب لدرجة حموضة التربة (pH) لديك وله طلب تصديري قوي.'},
    'Peas': {'ar': 'بازلاء (Peas)', 'duration': '60 - 75 يوم', 'water': 'متوسط', 'temp': '13°C - 20°C', 'reason': 'بقوليات تغني التربة بالنيتروجين وتجهزها للموسم التالي.'},
    'Potatoes': {'ar': 'بطاطس (Potatoes)', 'duration': '90 - 115 يوم', 'water': 'مرتفع', 'temp': '15°C - 22°C', 'reason': 'عائد مالي مرتفع وتستجيب بقوة لمستوى المادة العضوية في أرضك.'},
    'Pumpkins': {'ar': 'قرع عسلي (Pumpkins)', 'duration': '85 - 100 يوم', 'water': 'متوسط', 'temp': '20°C - 30°C', 'reason': 'يغطي سطح التربة ويقلل التبخر ويحقق إنتاجية ضخمة.'},
    'Rye': {'ar': 'شيلم / جاودار (Rye)', 'duration': '90 - 110 يوم', 'water': 'منخفض', 'temp': '12°C - 20°C', 'reason': 'ممتاز كمحصول تغطية لحماية التربة من الانجراف.'},
    'Sorghum': {'ar': 'ذرة رفيعة (Sorghum)', 'duration': '95 - 115 يوم', 'water': 'منخفض', 'temp': '25°C - 35°C', 'reason': 'يتحمل درجات الحرارة العالية ويحتاج كميات مياه أقل.'},
    'Spring Wheat': {'ar': 'قمح ربيعي (Spring Wheat)', 'duration': '100 - 120 يوم', 'water': 'متوسط', 'temp': '15°C - 24°C', 'reason': 'محصول استراتيجي مستقر يتوافق مع معدلات التبخر والحرارة لديك.'},
    'Winter Wheat': {'ar': 'قمح شتوي (Winter Wheat)', 'duration': '130 - 150 يوم', 'water': 'متوسط', 'temp': '12°C - 22°C', 'reason': 'يعطي أعلى إنتاجية للحبوب في الظروف المناخية المسجلة لموقعك.'},
    'Triticale': {'ar': 'تريتيكال (Triticale)', 'duration': '110 - 130 يوم', 'water': 'منخفض - متوسط', 'temp': '14°C - 23°C', 'reason': 'يجمع بين صلابة الشيلم وإنتاجية القمح في تربتك.'}
}

@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.get_json()
        if not data:
            return jsonify({'status': 'error', 'message': 'No JSON data provided'}), 400


        features = []
        for col in FEATURE_NAMES:
            if col == 'Year':
                val = data.get('Year') or data.get('year', 2026)
            elif col == 'MONTH':
                val = data.get('MONTH') or data.get('month', 11)
            elif col == 'T2M':
                val = data.get('T2M') or data.get('t2m', 0)
            elif col == 'T2M_MAX':
                val = data.get('T2M_MAX') or data.get('max', 0)
            else:
                val = data.get(col, 0)
            features.append(float(val))

        input_array = np.array([features])
        probs = model.predict_proba(input_array)[0]
        sorted_indices = np.argsort(probs)[::-1]

        recommendations = []
        for idx in sorted_indices:
            raw_name = CLASS_NAMES[idx]
            if raw_name in EXCLUDED_CLASSES:
                continue

            info = CROP_INFO.get(raw_name, {
                'ar': raw_name,
                'duration': '75 - 95 يوم',
                'water': 'متوسط',
                'temp': '18°C - 28°C',
                'reason': 'يتوافق بدرجة عالية مع خصائص التربة والمناخ في إحداثيات أرضك.'
            })

            recommendations.append({
                'raw_name': raw_name,
                'crop_name': info['ar'],
                'confidence': round(float(probs[idx]) * 100, 1),
                'duration': info['duration'],
                'water': info['water'],
                'temp': info['temp'],
                'reason': info['reason'],
            })

            if len(recommendations) == 3:
                break

        return jsonify({
            'status': 'success',
            'top_3': recommendations
        })

    except Exception as e:
        return jsonify({'status': 'error', 'message': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)