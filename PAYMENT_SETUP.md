# YUKK Payment Gateway Integration Setup

## 📋 Overview
Integrasi lengkap YUKK Payment Gateway untuk sistem upgrade Premium di TraKerja.

## ✅ Files Created

### Backend
1. `config/yukk.php` - Configuration file dengan kredensial YUKK
2. `database/migrations/2025_10_22_000009_create_payments_table.php` - Database schema
3. `app/Models/Payment.php` - Payment model dengan helper methods
4. `app/Services/YukkPaymentService.php` - Service untuk YUKK API integration
5. `app/Http/Controllers/PaymentController.php` - Payment controller
6. `routes/web.php` - Payment routes (updated)

### Frontend Views
7. `resources/views/payment/index.blade.php` - Payment method selection page
8. `resources/views/payment/success.blade.php` - Payment success page
9. `resources/views/payment/failed.blade.php` - Payment failed page
10. `resources/views/payment/waiting.blade.php` - Waiting for payment page
11. `resources/views/profile/edit.blade.php` - Added Premium badge & Upgrade button (updated)

## 🔧 Setup Instructions

### 1. Environment Variables
Add these to your `.env` file:

```env
# YUKK Payment Gateway
YUKK_ENVIRONMENT=sandbox
# For production, change to: production

YUKK_CLIENT_ID=bdd79914-832e-3fd5-9bac-11be9d90aa92
YUKK_CLIENT_SECRET=CxZ4PNfYPRDT3efwX2ErbWvnGiqGu28WSSvdHo5r
YUKK_MID="PG Sandbox 49197"

# Payment Configuration
PREMIUM_PRICE=100000
# Price in IDR (default: 100,000)

# Webhook & Callback URLs (auto-generated from APP_URL)
YUKK_NOTIFICATION_URL="${APP_URL}/payment/webhook"
YUKK_CALLBACK_URL="${APP_URL}/payment/callback"
```

### 2. Run Database Migration

```bash
php artisan migrate
```

This will create the `payments` table with columns:
- Order & transaction info
- Payment details (channel, amount, VA number)
- Customer info
- Status & timestamps
- Webhook data storage

### 3. Clear Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### 4. Composer Autoload (if needed)

```bash
composer dump-autoload
```

## 🚀 How It Works

### Payment Flow

1. **User clicks "Upgrade to Premium"** in Profile page
2. **Payment Method Selection** (`/payment`)
   - Shows all available payment channels from YUKK API
   - Grouped by category (Bank Transfer, E-Wallet, QRIS, Credit Card)
3. **Checkout** (`POST /payment/checkout`)
   - Creates payment record in database
   - Calls YUKK API to create transaction
   - Redirects user to YUKK payment page
4. **User completes payment** on YUKK's page
5. **Callback** (`POST /payment/callback`)
   - User is redirected back with status
   - Shows appropriate page (success/failed/waiting)
6. **Webhook** (`POST /payment/webhook`)
   - YUKK sends notification about payment status
   - System updates payment status
   - If SUCCESS: User is upgraded to Premium
7. **Auto-check status** on waiting page (every 10 seconds)

### Payment Status Flow

```
PENDING → WAITING → SUCCESS (User becomes Premium)
                  → FAILED
                  → CANCELED
                  → EXPIRED
```

## 📡 API Endpoints

### User Routes (require auth)
- `GET /payment` - Payment method selection
- `POST /payment/checkout` - Create payment & redirect to YUKK
- `GET /payment/success/{orderId}` - Success page
- `GET /payment/failed/{orderId}` - Failed page
- `GET /payment/waiting/{orderId}` - Waiting page
- `GET /payment/check-status/{orderId}` - Check status (AJAX)

### Webhook Routes (no auth)
- `POST /payment/callback` - Callback from YUKK after payment
- `POST /payment/webhook` - Webhook notification from YUKK

## 🔐 Security

### Webhook Signature Verification
Setiap webhook diverifikasi menggunakan:
```php
hash('sha512', 
    $clientSecret .
    $orderId .
    $paymentChannelCode .
    $amount .
    $status
)
```

### Access Token Caching
Access token dari YUKK API di-cache selama 14 menit untuk efisiensi.

## 💳 Payment Channels Available

### Bank Transfer (Virtual Account)
- VA BCA
- VA Mandiri
- VA BNI
- VA BRI
- VA Permata
- VA Danamon
- VA CIMB
- VA Hana
- VA Maybank
- VA Permata Syariah
- VA BJB
- VA YUDB

### E-Wallet
- OVO
- ShopeePay

### QRIS
- QRIS (all banks)

### Credit Card
- VISA
- Mastercard
- JCB

## 🎨 UI Features

### Payment Selection Page
- Grouped payment channels by category
- Premium benefits showcase
- Clean & modern design
- Responsive layout

### Waiting Page
- Auto-refresh status every 10 seconds
- Copy VA number button (for VA payments)
- VA expiry countdown
- Manual status check button
- Payment instructions

### Success Page
- Premium badge
- Payment details
- Premium features list
- Quick actions (Dashboard, Create CV)

### Failed Page
- Error explanation
- Possible causes
- Retry button
- Support contact

### Profile Page
- **Premium Member**: Gold badge with expiry date
- **Free User**: "Upgrade to Premium" button with star icon

## 📊 Database Schema

### `payments` table
```sql
- id (bigint)
- user_id (bigint, foreign key)
- order_id (string, unique)
- yukk_transaction_code (string, nullable, unique)
- yukk_token (string, nullable)
- amount (integer)
- payment_channel_code (string, nullable)
- payment_channel_name (string, nullable)
- payment_category (string, nullable)
- va_number (string, nullable)
- va_account_id (string, nullable)
- va_expired_at (timestamp, nullable)
- customer_name (string)
- customer_email (string)
- customer_phone (string)
- status (enum: PENDING, WAITING, SUCCESS, FAILED, CANCELED, EXPIRED)
- request_at (timestamp, nullable)
- paid_at (timestamp, nullable)
- expired_at (timestamp, nullable)
- redirect_url (text, nullable)
- callback_url (text, nullable)
- notification_url (text, nullable)
- notes (text, nullable)
- metadata (json, nullable)
- webhook_data (json, nullable)
- created_at, updated_at, deleted_at
```

## 🧪 Testing

### Test Payment (Sandbox)

1. Akses `/payment`
2. Pilih metode pembayaran (VA BCA recommended for testing)
3. Akan mendapat nomor VA
4. **Simulasi pembayaran**:
   - Buka: https://simulator.yukk.co.id/
   - Input VA number yang didapat
   - Simulasikan SUCCESS atau FAILED

### Test Webhook Locally

Gunakan ngrok atau expose.dev untuk webhook testing:
```bash
ngrok http 8000
```

Update `YUKK_NOTIFICATION_URL` dengan ngrok URL:
```
https://xxxx.ngrok.io/payment/webhook
```

## 📝 Logging

Semua payment activities di-log:
- Payment creation
- API calls to YUKK
- Webhook received
- Status updates
- User upgrades

Check logs di:
```bash
tail -f storage/logs/laravel.log | grep YUKK
```

## 🚨 Error Handling

### Common Issues

1. **Access Token Expired**
   - Auto-renewed via cache (14 min)
   - Check `storage/logs/laravel.log`

2. **Webhook Signature Failed**
   - Verify `YUKK_CLIENT_SECRET` correct
   - Check webhook payload in logs

3. **Payment Timeout**
   - Default VA: 24 hours
   - Can be customized via `va.expires_in`

4. **User Not Upgraded After Payment**
   - Check webhook was received
   - Manually check status: `GET /payment/check-status/{orderId}`
   - Verify `webhook_data` in database

## 🔄 Migration from Sandbox to Production

1. Update `.env`:
   ```env
   YUKK_ENVIRONMENT=production
   YUKK_CLIENT_ID=<production_client_id>
   YUKK_CLIENT_SECRET=<production_client_secret>
   YUKK_MID=<production_mid>
   ```

2. Clear config cache:
   ```bash
   php artisan config:clear
   ```

3. Update webhook URL di YUKK dashboard:
   ```
   https://trakerja.web.id/payment/webhook
   ```

4. Test with small amount first!

## 📞 Support

For YUKK API issues:
- Documentation: https://dev.api.yukkpay.com/docs
- Contact YUKK support team

For TraKerja integration issues:
- Check `storage/logs/laravel.log`
- Review payment record in database
- Contact dev team

---

**Created**: {{ now() }}  
**Status**: Ready for Testing 🚀

