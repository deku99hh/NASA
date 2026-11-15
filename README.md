# 🌱 Agro-Pixel (Fern) — Smart Precision Agriculture & Crop Recommendation Platform

**Agro-Pixel (Fern)** is a gamified, pixel-art precision agriculture web application powered by **Laravel**, **Python (Flask)**, and an **XGBoost Machine Learning** model. It analyzes satellite, meteorological, and soil datasets for any geographical coordinate (`Latitude` & `Longitude`) to visualize land metrics and recommend the top three optimal crops tailored to the farmer's strategy.

> **⚠️ Note on Machine Learning Model Repository:**  
> The trained XGBoost model (`crop_model.json`), metadata schema (`meta.json`), training notebooks, and dataset preprocessing pipelines are hosted in a separate dedicated repository:  
> 🔗 **[https://github.com/youssefessamz/Fern](https://github.com/youssefessamz/Fern)**

---

## ✨ Key Features

- **Visual Novel Onboarding ("Fern"):** Interactive pixel-art dialogue engine that collects the farmer's field coordinates (`Latitude` & `Longitude`) and previous crop history (`last_crop`).
- **Geospatial Climate & Soil Ingestion:** Automated database seeding and relational storage (`locations` & `records` tables) handling historical environmental and soil datasets.
- **Interactive Pixel-Art Dashboard:**
  - **Land Metrics & Stats:** Real-time cards displaying Temperature (`T2M` & `Max`), Soil Moisture, Vegetation Index (`NDVI`), and Soil Acidity (`pH`) alongside Organic Matter (`OM`).
  - **Soil Composition Pie/Donut Chart:** Dynamic `conic-gradient` breakdown of **Clay**, **Sand**, and **Silt** ratios, plus Saturated Hydraulic Conductivity (`Ksat`) and terrain `Slope`.
  - **Climate & Energy Bar Chart:** Visualizes Solar Radiation (`ALLSKY_SFC_SW_DWN`), Relative Humidity (`RH2M`), Evapotranspiration (`ET_mm`), and Precipitation (`precipitation_mm`).
- **AI-Powered Crop Strategy Cards:**
  - Connects Laravel to a Flask inference microservice (`POST /predict`) running the **XGBoost Classifier** from the [Model Repository](https://github.com/youssefessamz/Fern).
  - Evaluates **18 environmental & soil features** to predict the top 3 agricultural crops (filtering out non-crop land classes such as forests and wetlands).
  - Presents recommendations across three gamified strategies (**Maximum Profit**, **Soil Health**, and **Perfect Balance**) with interactive character hover tooltips detailing harvest duration, water requirements, optimal temperature ranges, and agronomic reasoning.

---

## 🏗️ System Architecture

```text
┌──────────────────────┐       HTTP POST (JSON)        ┌──────────────────────────┐
│   Laravel Web App    │ ────────────────────────────► │   Flask Inference API    │
│  (Blade + Tailwind)  │   18 Soil & Climate Features  │   (XGBoost + meta.json)  │
└──────────────────────┘ ◄──────────────────────────── └──────────────────────────┘
           │                    Top 3 Crop Predictions               │
           ▼                                                         ▼
┌──────────────────────┐                               ┌──────────────────────────┐
│   SQLite / MySQL DB  │                               │   External Model Repo    │
│ (Users, Locations,   │                               │  youssefessamz/Fern      │
│  Environmental Logs) │                               │  (crop_model.json)       │
└──────────────────────┘                               └──────────────────────────┘
```

---

## 🛠️ Tech Stack

- **Backend & Web Framework:** PHP 8.4+, Laravel 13, Laravel Breeze (Authentication)
- **Frontend & UI:** Blade Templates, Tailwind CSS, Vite, Custom Pixel-Art CSS (`clip-path` cubic corners, `Pixelify Sans` & `Cairo` fonts)
- **AI Microservice:** Python 3.10+, Flask, XGBoost, NumPy
- **Database:** SQLite / MySQL

---

## 📊 Machine Learning Input Features (`18 Features`)

The Laravel `DashboardController` sends the latest land record to the Flask `/predict` endpoint matching the exact feature order defined in `meta.json`:

| Feature | Description | Feature | Description |
| :--- | :--- | :--- | :--- |
| `Year` | Observation year | `clay` | Clay percentage in soil (%) |
| `MONTH` | Observation month | `sand` | Sand percentage in soil (%) |
| `precipitation_mm` | Rainfall amount (mm) | `silt` | Silt percentage in soil (%) |
| `T2M` | Mean temperature at 2m (°C) | `ph` | Soil acidity / alkalinity (`pH`) |
| `T2M_MAX` | Maximum temperature (°C) | `om` | Soil organic matter (%) |
| `RH2M` | Relative humidity at 2m (%) | `ksat` | Saturated hydraulic conductivity (mm/hr) |
| `ALLSKY_SFC_SW_DWN` | All-sky surface shortwave downward irradiance | `ET_mm` | Evapotranspiration (mm) |
| `elevation_m` | Terrain elevation (meters) | `soil_moisture` | Volumetric soil moisture |
| `slope_deg` | Terrain slope (degrees) | `NDVI` | Normalized Difference Vegetation Index |

---

## 🚀 Installation & Setup

### 1. Laravel Web Application Setup
```bash
# Install PHP & Frontend dependencies
composer install
npm install

# Configure environment variables
cp .env.example .env
php artisan key:generate

# Run database migrations and seed climate data
php artisan migrate --seed

# Build frontend assets and start Laravel server
npm run build
php artisan serve
```

### 2. Python AI Microservice Setup
> **Important:** Clone or download `crop_model.json` and `meta.json` from the separate model repository ([https://github.com/youssefessamz/Fern](https://github.com/youssefessamz/Fern)) and place them in the same directory as `app.py`.

```bash
# Install required Python packages
pip install flask xgboost numpy

# Run the Flask inference server on port 5000
python app.py
```

### 3. Asset Verification
Ensure your strategy card artwork images (`ooo.jpg`, `Bmo.jpg`, `hhh.jpg`) are placed inside the `public/` directory of the Laravel project so they render properly on the dashboard.

---

## 🔌 API Endpoint Reference

### `POST http://127.0.0.1:5000/predict`

**Request Payload (JSON):**
```json
{
  "Year": 2026,
  "MONTH": 11,
  "precipitation_mm": 0.03,
  "T2M": 24.5,
  "T2M_MAX": 31.2,
  "RH2M": 51.2,
  "ALLSKY_SFC_SW_DWN": 25.1,
  "elevation_m": 42,
  "slope_deg": 2.4,
  "clay": 25,
  "sand": 45,
  "silt": 30,
  "ph": 6.8,
  "om": 1.2,
  "ksat": 14,
  "ET_mm": 14.1,
  "soil_moisture": 0.184,
  "NDVI": 0.58
}
```

**Response Payload (JSON):**
```json
{
  "status": "success",
  "top_3": [
    {
      "raw_name": "Potatoes",
      "crop_name": "بطاطس (Potatoes)",
      "confidence": 64.2,
      "duration": "90 - 115 يوم",
      "water": "مرتفع",
      "temp": "15°C - 22°C",
      "reason": "عائد مالي مرتفع وتستجيب بقوة لمستوى المادة العضوية في أرضك."
    }
  ]
}
```
