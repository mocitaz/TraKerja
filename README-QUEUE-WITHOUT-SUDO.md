# Setup Queue Worker Tanpa Sudo (Shared Hosting)

Jika Anda tidak punya akses `sudo` di server (shared hosting atau limited access), berikut cara alternatif untuk menjalankan queue worker.

## Metode 1: Background Process dengan Script (Recommended)

### Setup

1. **Upload script ke server:**
   - `start-queue-worker.sh`
   - `stop-queue-worker.sh`
   - `check-queue-worker.sh`

2. **Buat script executable:**
   ```bash
   chmod +x start-queue-worker.sh
   chmod +x stop-queue-worker.sh
   chmod +x check-queue-worker.sh
   ```

3. **Start queue worker:**
   ```bash
   ./start-queue-worker.sh
   ```

### Commands

```bash
# Start queue worker
./start-queue-worker.sh

# Stop queue worker
./stop-queue-worker.sh

# Check status
./check-queue-worker.sh

# View logs
tail -f storage/logs/worker.log
```

### Catatan Penting

⚠️ **Queue worker akan berhenti jika:**
- SSH session disconnect
- Server reboot
- Process crash

**Solusi:** Setelah reconnect SSH, jalankan lagi `./start-queue-worker.sh`

## Metode 2: Screen Session (More Persistent)

### Setup

1. **Install screen (jika belum ada):**
   ```bash
   # Check if screen is available
   which screen
   ```

2. **Start screen session:**
   ```bash
   screen -S queue-worker
   ```

3. **Di dalam screen, jalankan queue worker:**
   ```bash
   php artisan queue:work --sleep=3 --tries=3
   ```

4. **Detach dari screen:**
   - Tekan `Ctrl+A` lalu `D`

5. **Reattach ke screen:**
   ```bash
   screen -r queue-worker
   ```

### Commands

```bash
# List screen sessions
screen -ls

# Attach to screen
screen -r queue-worker

# Kill screen session
screen -X -S queue-worker quit
```

## Metode 3: Cron Job untuk Auto-Start (Jika Process Mati)

Buat cron job untuk check dan restart queue worker:

```bash
# Edit crontab
crontab -e

# Add this line (check every 5 minutes)
*/5 * * * * cd /home/u672201335/domains/trakerja.web.id && pgrep -f "artisan queue:work" > /dev/null || nohup php artisan queue:work --sleep=3 --tries=3 > storage/logs/worker.log 2>&1 &
```

Ini akan otomatis start queue worker jika tidak berjalan.

## Metode 4: Systemd User Service (Jika Available)

Jika server support systemd user services:

1. **Create service file:**
   ```bash
   mkdir -p ~/.config/systemd/user
   nano ~/.config/systemd/user/trakerja-worker.service
   ```

2. **Paste ini:**
   ```ini
   [Unit]
   Description=TraKerja Queue Worker
   After=network.target

   [Service]
   Type=simple
   WorkingDirectory=%h/domains/trakerja.web.id
   ExecStart=/usr/bin/php artisan queue:work --sleep=3 --tries=3
   Restart=always
   RestartSec=10

   [Install]
   WantedBy=default.target
   ```

3. **Enable and start:**
   ```bash
   systemctl --user enable trakerja-worker.service
   systemctl --user start trakerja-worker.service
   systemctl --user status trakerja-worker.service
   ```

## Troubleshooting

### Queue Worker Tidak Berjalan

1. **Check if process is running:**
   ```bash
   ps aux | grep "artisan queue:work"
   ```

2. **Check logs:**
   ```bash
   tail -f storage/logs/worker.log
   ```

3. **Check Laravel logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Queue Worker Berhenti Setelah SSH Disconnect

**Solusi:** Gunakan screen atau nohup:
```bash
nohup php artisan queue:work --sleep=3 --tries=3 > storage/logs/worker.log 2>&1 &
```

### Permission Denied

Pastikan storage directory writable:
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## Rekomendasi

Untuk production tanpa sudo access:
1. **Gunakan Metode 1 (Script)** untuk kemudahan
2. **Setup Cron Job (Metode 3)** untuk auto-restart
3. **Monitor via halaman admin:** `/admin/queue-status`

## Catatan

- Queue worker akan berhenti jika SSH disconnect (kecuali pakai screen/nohup)
- Setup cron job untuk auto-restart jika process mati
- Monitor status di halaman admin queue status
- Jika perlu permanent solution, minta admin server untuk setup supervisor

