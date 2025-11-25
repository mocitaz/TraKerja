# Cara Menjalankan Archive Command Setelah Deploy

## Manual Execution (Setelah Deploy)

Setelah melakukan deploy, jalankan command berikut di terminal server:

```bash
php artisan jobs:archive-old-declined
```

### Contoh untuk Shared Hosting (cPanel/directadmin):

1. Login ke SSH atau Terminal di cPanel
2. Navigate ke project directory:
   ```bash
   cd /path/to/your/project
   ```
3. Jalankan command:
   ```bash
   php artisan jobs:archive-old-declined
   ```

### Contoh untuk VPS/Server:

```bash
# SSH ke server
ssh user@your-server.com

# Navigate ke project directory
cd /var/www/trakerja

# Jalankan command
php artisan jobs:archive-old-declined
```

## Automatic Execution (Scheduled)

Command ini sudah dijadwalkan untuk berjalan otomatis setiap hari jam 02:00 WIB melalui Laravel Scheduler.

Pastikan cron job sudah dikonfigurasi di server:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

## Hasil Command

Command akan menampilkan:
- Jumlah job yang ditemukan untuk di-archive
- Progress bar
- Jumlah job yang berhasil di-archive

Contoh output:
```
Starting to archive old declined and not processed jobs...
Found 6 job(s) to archive.
 6/6 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%
Successfully archived 6 job(s).
```

## Catatan

- Command ini aman untuk dijalankan berkali-kali (idempotent)
- Hanya akan meng-archive job yang belum ter-archive
- Tidak akan mempengaruhi job yang sudah aktif
- Migration juga sudah otomatis meng-archive data lama saat pertama kali dijalankan


