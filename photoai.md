# Photo API Documentation

## Overview
API ini digunakan untuk:
* [cite_start]Menghapus background foto [cite: 4]
* [cite_start]Mengganti background pas foto [cite: 5]
* [cite_start]Membuat AI professional headshot [cite: 6]
* [cite_start]Membuat LinkedIn profile photo [cite: 7]
* [cite_start]Membuat pas foto formal [cite: 8]

[cite_start]**Backend Tech Stack:** Flask + LightX AI + rembg [cite: 9]

---

## Global Configuration

* [cite_start]**Base URL:** `http://127.0.0.1:5001/` (Contoh lokal) [cite: 13]
* [cite_start]**Authentication:** Saat ini API tidak menggunakan authentication[cite: 15].
* [cite_start]**Content-Type:** Semua endpoint upload gambar menggunakan `multipart/form-data`[cite: 17, 18].
* [cite_start]**File Limits:** Maksimal 20 MB[cite: 263].
* [cite_start]**Supported Formats:** JPG, JPEG, PNG, WEBP[cite: 264].

---

## Endpoints

### 1. Health Check
[cite_start]Digunakan untuk mengecek status service dan daftar style/background yang tersedia[cite: 20].

* [cite_start]**Endpoint:** `GET /health` [cite: 23]
* **Laravel Example:**
    ```php
    use Illuminate\Support\Facades\Http;
    
    $response = Http::get('[http://127.0.0.1:5001/health](http://127.0.0.1:5001/health)');
    return $response->json();
    ```
* **Success Response (200):**
    ```json
    {
      "status": "ok",
      "rembg_installed": true,
      "lightx_configured": true,
      "imgbb_configured": true,
      "openai_configured": true
    }
    ```

---

### 2. Remove Background API
[cite_start]Menghapus background menggunakan `rembg` secara offline tanpa AI generatif, sehingga wajah tetap asli[cite: 37, 39, 40, 41].

* [cite_start]**Endpoint:** `POST /remove-bg` [cite: 45]
* **Request Parameters (Multipart Form):**
    | Field | Type | Required | Description |
    | :--- | :--- | :--- | :--- |
    | `photo` | file | **YES** | File gambar [cite: 48] |
    | `background` | string | NO | Warna background (Default: `transparan`) [cite: 48, 51, 52] |
    | `size` | string | NO | Ukuran pas foto (Default: `original`) [cite: 48, 65, 66] |

* **Available Options:**
    * [cite_start]**Backgrounds:** `transparan`, `merah`, `biru`, `biru_muda`, `putih`, `abu_abu`, `hitam`, `hijau`, `kuning` [cite: 52, 54, 55, 56, 57, 58, 59, 60, 61, 62]
    * [cite_start]**Sizes:** `original`, `3x4`, `4x6`, `2x3` [cite: 63]

* **Laravel Example:**
    ```php
    use Illuminate\Support\Facades\Http;

    $response = Http::attach(
        'photo',
        fopen(public_path('photo.jpg'), 'r'),
        'photo.jpg'
    )->post('[http://127.0.0.1:5001/remove-bg](http://127.0.0.1:5001/remove-bg)', [
        'background' => 'merah',
        'size' => '3x4'
    ]);

    return $response->json();
    ```

* **cURL Example:**
    ```bash
    curl -X POST [http://127.0.0.1:5001/remove-bg](http://127.0.0.1:5001/remove-bg) \
      -F "photo=@photo.jpg" \
      -F "background=merah" \
      -F "size=3x4"
    ```

* **Responses:**
    * **Success (200):**
        ```json
        {
          "success": true,
          "engine": "rembg (offline)",
          "background": "merah",
          "size": "3x4",
          "format": "jpg",
          "result_url": "[http://127.0.0.1:5001/static/enhanced/abc123.jpg](http://127.0.0.1:5001/static/enhanced/abc123.jpg)"
        }
        ```
    * **Error (400):**
        ```json
        {
          "error": "Field 'photo' wajib."
        }
        ```

---

### 3. Enhance Photo AI
[cite_start]Mengubah style foto menggunakan AI generatif (LightX AI) dengan mempertahankan detail wajah asli[cite: 92, 93, 103].

* [cite_start]**Endpoint:** `POST /enhance-photo-ai` [cite: 105]
* **Request Parameters (Multipart Form):**
    | Field | Type | Required | Description |
    | :--- | :--- | :--- | :--- |
    | `photo` | file | **YES** | File gambar [cite: 107] |
    | `style` | string | NO | Style pakaian/AI (Default: `auto`) [cite: 107, 110] |
    | `background` | string | NO | Background AI [cite: 107] |
    | `mode` | string | NO | Mode pemrosesan (Default: `portrait`) [cite: 107, 147, 148] |

* **Available Styles:**
    | Key | Description |
    | :--- | :--- |
    | `auto` | Auto detect [cite: 110, 112] |
    | `rapi_formal` | Jas & Dasi [cite: 114] |
    | `klasik_pria` | Navy Suit [cite: 114] |
    | `professional_wanita` | Blazer [cite: 114] |
    | `casual_modern` | Smart Casual [cite: 114] |
    | `natural` | Natural Friendly [cite: 114] |
    | `linkedin_pria` | LinkedIn Male [cite: 114] |
    | `linkedin_wanita` | LinkedIn Female [cite: 114] |
    | `personal_branding` | Creative Professional [cite: 114] |
    | `pasfoto_formal` | Pas Foto Formal [cite: 114] |
    | `pasfoto_wisuda` | Wisuda [cite: 114] |

* **Available Backgrounds:**
    * [cite_start]**Pas Foto:** `merah`, `biru`, `biru_muda`, `putih`, `abu_abu` [cite: 116, 118, 119, 120, 121, 122]
    * [cite_start]**Studio:** `studio_plain`, `studio_dark`, `studio_gradient` [cite: 123, 125, 126, 127]
    * [cite_start]**Office:** `modern_office`, `meeting_room`, `co_working` [cite: 129, 131, 132, 133]
    * [cite_start]**Outdoor:** `city_rooftop`, `outdoor_park`, `library`, `urban_street` [cite: 134, 136, 137, 138, 139]

* **Available Modes:**
    * [cite_start]`portrait`: Wajah lebih terjaga (Sangat direkomendasikan)[cite: 143, 144].
    * [cite_start]`headshot`: Transformasi pakaian dan gaya lebih agresif[cite: 145, 146].

* **cURL Example:**
    ```bash
    curl -X POST [http://127.0.0.1:5001/enhance-photo-ai](http://127.0.0.1:5001/enhance-photo-ai) \
      -F "photo=@photo.jpg" \
      -F "style=linkedin_pria" \
      -F "background=modern_office" \
      -F "mode=portrait"
    ```

* **Success Response (200):**
    ```json
    {
      "success": true,
      "photo_analysis": {
        "engine": "rembg + LightX AI (portrait)",
        "gender": "pria",
        "recommended_style": "rapi_formal"
      },
      "style_used": "linkedin_pria",
      "background_used": "modern_office",
      "mode": "portrait",
      "prompt_used": "preserve the exact same face...",
      "result_url": "[http://127.0.0.1:5001/static/enhanced/hasil.jpg](http://127.0.0.1:5001/static/enhanced/hasil.jpg)"
    }
    ```

---

## Laravel Integration Example

### Controller (`PhotoController.php`)
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PhotoController extends Controller
{
    public function enhance(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:20480' // Max 20MB
        ]);

        // Mengirim data ke Flask API dengan timeout 5 menit (300 detik) karena proses AI membutuhkan waktu
        $response = Http::timeout(300)
            ->attach(
                'photo',
                fopen($request->file('photo')->getRealPath(), 'r'),
                $request->file('photo')->getClientOriginalName()
            )->post('[http://127.0.0.1:5001/enhance-photo-ai](http://127.0.0.1:5001/enhance-photo-ai)', [
                'style' => $request->input('style', 'linkedin_pria'),
                'background' => $request->input('background', 'modern_office'),
                'mode' => 'portrait'
            ]);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => $response->json()
            ], 500);
        }

        return response()->json($response->json());
    }
}

## Environment Variables (.env pada Flask)

```env
PORT=5001
OPENAI_API_KEY=your_openai_key
LIGHTX_API_KEY=your_lightx_key
IMGBB_API_KEY=your_imgbb_key