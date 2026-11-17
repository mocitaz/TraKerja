# TraKerja Feed API (read-only)

Untuk klien tepercaya seperti bot Telegram asep. Klien mengambil loker dari TraKerja, jadi tidak perlu scraping sendiri. Semua endpoint hanya membaca data.

## Kunci akses

Buat dan kelola kunci di server:

```bash
php artisan feed:client create asep --per-minute=60   # kunci ditampilkan sekali; simpan di secrets klien
php artisan feed:client list
php artisan feed:client revoke 3                       # nonaktifkan klien id 3
```

Kirim kunci di setiap request sebagai `Authorization: Bearer <key>`, atau `X-Api-Key: <key>`. Server hanya menyimpan hash kunci.

- Tanpa kunci atau kunci salah: `401`.
- Melebihi batas per menit: `429`, dengan header `Retry-After`.
- Setiap respons membawa `X-RateLimit-Limit` dan `X-RateLimit-Remaining`.

## Endpoint

### `GET /api/v1/vacancies`

| Parameter | Arti |
|---|---|
| `updated_since` | ISO 8601. Mengembalikan semua perubahan setelah waktu ini, termasuk loker yang ditutup, digabung, atau dihapus. |
| `cursor` | Nilai `next_cursor` dari halaman sebelumnya. |
| `limit` | 1–100, default 50. |
| `q` | Kata kunci di judul atau nama perusahaan. |
| `region` | Nama kota atau provinsi, misalnya `Jakarta` atau `Jawa Barat`. |
| `remote` | `1` untuk loker remote saja. |
| `status` | `active` (default) atau `all`. |

Urutan: `updated_at` lalu `id`, naik. Respons:

```json
{
  "data": [ { "id": "01m7…", "status": "active", "title": "…", "apply_url": "https://…", "…": "…" } ],
  "has_more": true,
  "next_cursor": "MjAyNi0x…",
  "attribution": "…"
}
```

### `GET /api/v1/vacancies/{id}`

Satu loker berdasarkan `id` publik (ULID).

### `GET /api/v1/sources`

Sumber yang aktif, untuk atribusi.

## Isi satu loker

`id`, `status` (`active` | `suspect` | `closed` | `removed`), `merged_into`, `updated_at`, `url` (halaman TraKerja), `title`, `company` {`name`, `slug`, `domain`, `logo_url`}, `location` {`text`, `city`, `province`, `country`}, `work_mode`, `employment_type`, `seniority`, `is_internship`, `sector`, `skills`, `education_min`, `salary` {`min`, `max`, `currency`, `period`, `label`}, `summary`, `apply_url`, `sources` [{`name`, `url`}], `date_posted`, `valid_through`, `first_seen_at`, `last_verified_at`, `closed_at`.

- `status: removed` berarti loker disembunyikan, misalnya karena dicurigai penipuan. Klien harus menghapusnya.
- Bila `merged_into` terisi, loker ini duplikat dari loker lain. Hapus yang ini dan pakai id di `merged_into`.
- Untuk dua kasus di atas, hanya `id`, `status`, `merged_into`, dan `updated_at` yang dikirim.

## Pola sinkronisasi yang disarankan

1. **Sinkron pertama:** ambil `GET /api/v1/vacancies?limit=100` dan ikuti `next_cursor` sampai `has_more` bernilai `false`.
2. **Simpan waktu:** catat `updated_at` terbesar yang diterima.
3. **Sinkron berkala** (misalnya tiap 15–30 menit): ambil `GET /api/v1/vacancies?updated_since=<waktu tersimpan>&limit=100` dan ikuti cursor.
   - `active`: simpan atau perbarui.
   - `closed` atau `removed`: hapus, atau tandai sudah tutup.
   - `merged_into` terisi: ganti dengan loker tujuan.

## Aturan penggunaan

- Selalu tautkan ke `apply_url` (sumber asli) atau `url` (halaman TraKerja). Pelamar melamar di sumber aslinya.
- Jangan menerbitkan ulang data ini di situs publik lain. Feed hanya untuk klien yang diberi kunci.
- Data tidak berisi data pengguna TraKerja.
