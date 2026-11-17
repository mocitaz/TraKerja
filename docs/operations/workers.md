# Queue workers (produksi)

Semua antrean TraKerja dan program Supervisor yang melayaninya. Kalau sebuah antrean tidak punya worker, job-nya diam di tabel
`jobs`: fitur terlihat "macet" dan `jobs:watchdog` mengirim alert CRITICAL setelah 15 menit.

| Program Supervisor | Koneksi | Antrean | Job | Timeout | `retry_after` |
|---|---|---|---|---|---|
| `trakerja-worker` (2 proses) | `database` | `discovery,ingest,ai,default` | Discovery & verifikasi sumber, enrichment AI, notifikasi, email | 900 s (discovery) | 960 s |
| `trakerja-ai-photo` | `ai_photos` | `ai-photo` | `ProcessAiPhoto` | 330 s | 360 s |
| `trakerja-ai-studio` | `ai_studio` | `ai-studio` | `ProcessAiStudio` | 160 s | 240 s |
| `trakerja-exports` | `exports` | `exports` | `BuildDataExport` | 240 s | 300 s |

Aturan: `retry_after` koneksi harus lebih besar dari timeout job terpanjang di koneksi itu (`tests/Unit/QueueTimeoutTest.php`
menjaga koneksi `database`).

## Konfigurasi

`/etc/supervisor/conf.d/trakerja-worker.conf`

```
[program:trakerja-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/test/trakerja/artisan queue:work --queue=discovery,ingest,ai,default --sleep=3 --tries=3 --max-time=3600
directory=/home/test/trakerja
user=test
numprocs=2
autostart=true
autorestart=true
stopwaitsecs=3600
redirect_stderr=true
stdout_logfile=/home/test/trakerja/storage/logs/worker.log
```

`/etc/supervisor/conf.d/trakerja-media-worker.conf`

```
[program:trakerja-ai-photo]
command=php /home/test/trakerja/artisan queue:work ai_photos --queue=ai-photo --timeout=330 --tries=1 --sleep=3 --max-time=3600
directory=/home/test/trakerja
user=test
numprocs=1
autostart=true
autorestart=true
stopwaitsecs=360
redirect_stderr=true
stdout_logfile=/home/test/trakerja/storage/logs/worker-ai-photo.log

[program:trakerja-ai-studio]
command=php /home/test/trakerja/artisan queue:work ai_studio --queue=ai-studio --timeout=160 --tries=1 --sleep=3 --max-time=3600
directory=/home/test/trakerja
user=test
numprocs=1
autostart=true
autorestart=true
stopwaitsecs=200
redirect_stderr=true
stdout_logfile=/home/test/trakerja/storage/logs/worker-ai-studio.log

[program:trakerja-exports]
command=php /home/test/trakerja/artisan queue:work exports --queue=exports --timeout=240 --tries=1 --sleep=3 --max-time=3600
directory=/home/test/trakerja
user=test
numprocs=1
autostart=true
autorestart=true
stopwaitsecs=300
redirect_stderr=true
stdout_logfile=/home/test/trakerja/storage/logs/worker-exports.log
```

## Setelah deploy

Worker memuat kode saat start, jadi semua program harus di-restart:

```bash
sudo supervisorctl restart trakerja-worker:* trakerja-ai-photo trakerja-ai-studio trakerja-exports
```

Isi file konfigurasi hanya blok `[program:…]`; perintah terminal jangan ikut ditempel (Supervisor menolak file seperti itu
dengan `CANT_REREAD`, dan setelah reboot worker tidak akan start).
