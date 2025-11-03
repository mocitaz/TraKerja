# Setup Queue Worker untuk Production

## Cara Menjalankan Queue Worker Otomatis di Production

Queue worker harus berjalan secara otomatis di production agar email blast bisa diproses. Berikut cara setup-nya:

## Metode 1: Menggunakan Supervisor (Recommended)

### Langkah 1: Install Supervisor

```bash
# Ubuntu/Debian
sudo apt-get update
sudo apt-get install supervisor

# CentOS/RHEL
sudo yum install supervisor

# Start supervisor service
sudo systemctl enable supervisor
sudo systemctl start supervisor
```

### Langkah 2: Tentukan Path Absolut Project

Cari tahu path absolut project Anda:

```bash
# Masuk ke folder project
cd domains/trakerja.web.id

# Cek path absolut
pwd

# Output contoh:
# /home/username/domains/trakerja.web.id
# atau
# /var/www/trakerja.web.id
```

**CATATAN:** Path absolut berbeda dengan path relatif. Path absolut harus dimulai dengan `/` (contoh: `/home/username/domains/trakerja.web.id`)

### Langkah 3: Buat Config File

Buat file: `/etc/supervisor/conf.d/trakerja-worker.conf`

```ini
[program:trakerja-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/username/domains/trakerja.web.id/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/home/username/domains/trakerja.web.id/storage/logs/worker.log
stopwaitsecs=3600
```

**PENTING:** 
- Ganti `/home/username/domains/trakerja.web.id` dengan path absolut project Anda (hasil dari `pwd`)
- Jika folder project Anda di `domains/trakerja.web.id`, biasanya path absolutnya seperti:
  - `/home/username/domains/trakerja.web.id`
  - `/var/www/domains/trakerja.web.id`
  - `/home/trakerja/domains/trakerja.web.id`

### Langkah 4: Reload Supervisor

```bash
# Reload config
sudo supervisorctl reread
sudo supervisorctl update

# Start queue worker
sudo supervisorctl start trakerja-worker:*

# Check status
sudo supervisorctl status trakerja-worker:*
```

### Langkah 5: Verifikasi

```bash
# Check if queue worker is running
sudo supervisorctl status

# View logs
tail -f /path/to/TraKerja/storage/logs/worker.log
```

## Metode 2: Menggunakan Script Otomatis

Jika Anda punya akses SSH, jalankan script setup:

```bash
cd /path/to/TraKerja
chmod +x setup-queue-worker.sh
./setup-queue-worker.sh
```

Script akan otomatis:
1. Install supervisor (jika belum ada)
2. Buat config file
3. Setup log directory
4. Start queue worker

## Metode 3: Systemd Service (Alternatif)

Jika tidak menggunakan Supervisor, bisa menggunakan systemd:

Buat file: `/etc/systemd/system/trakerja-worker.service`

```ini
[Unit]
Description=TraKerja Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /path/to/TraKerja/artisan queue:work --sleep=3 --tries=3
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
```

Kemudian:

```bash
# Reload systemd
sudo systemctl daemon-reload

# Enable and start service
sudo systemctl enable trakerja-worker
sudo systemctl start trakerja-worker

# Check status
sudo systemctl status trakerja-worker
```

## Perintah Berguna

### Supervisor Commands

```bash
# Check status
sudo supervisorctl status trakerja-worker:*

# Restart worker
sudo supervisorctl restart trakerja-worker:*

# Stop worker
sudo supervisorctl stop trakerja-worker:*

# View logs
tail -f /path/to/TraKerja/storage/logs/worker.log

# View all supervisor processes
sudo supervisorctl status
```

### Systemd Commands

```bash
# Check status
sudo systemctl status trakerja-worker

# Restart worker
sudo systemctl restart trakerja-worker

# Stop worker
sudo systemctl stop trakerja-worker

# View logs
sudo journalctl -u trakerja-worker -f
```

## Troubleshooting

### Queue Worker Tidak Berjalan

1. **Check supervisor status:**
   ```bash
   sudo supervisorctl status
   ```

2. **Check logs:**
   ```bash
   tail -f /path/to/TraKerja/storage/logs/worker.log
   ```

3. **Check permissions:**
   ```bash
   # Ensure storage directory is writable
   chmod -R 755 /path/to/TraKerja/storage
   chown -R www-data:www-data /path/to/TraKerja/storage
   ```

4. **Restart supervisor:**
   ```bash
   sudo systemctl restart supervisor
   sudo supervisorctl reread
   sudo supervisorctl update
   ```

### Queue Worker Crash

1. **Check error logs:**
   ```bash
   tail -f /path/to/TraKerja/storage/logs/worker.log
   ```

2. **Check Laravel logs:**
   ```bash
   tail -f /path/to/TraKerja/storage/logs/laravel.log
   ```

3. **Restart worker:**
   ```bash
   sudo supervisorctl restart trakerja-worker:*
   ```

## Catatan Penting

1. **Path harus absolut:** Pastikan path di config menggunakan path absolut, bukan relative
2. **User harus benar:** Pastikan user yang digunakan memiliki permission untuk menjalankan PHP dan mengakses storage
3. **Log directory:** Pastikan directory `storage/logs` ada dan writable
4. **Auto-restart:** Supervisor akan otomatis restart queue worker jika crash
5. **Multiple workers:** Bisa run multiple worker dengan `numprocs=2` untuk meningkatkan throughput

## Setelah Setup

Setelah queue worker berjalan:
1. Email blast akan otomatis diproses
2. Tidak perlu lagi menjalankan `php artisan queue:work` manual
3. Queue worker akan restart otomatis jika server reboot
4. Monitor status di halaman admin: `/admin/queue-status`

