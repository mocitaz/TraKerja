# 📧 Brevo (Sendinblue) SMTP Setup Guide for TraKerja

## Step 1: Create Brevo Account (2 minutes)

1. Go to: https://app.brevo.com/account/register
2. Fill in:
   - Email address
   - Password
   - Company name: "TraKerja"
3. Click "Create my account"
4. Check your email and verify your account

## Step 2: Get SMTP Credentials (2 minutes)

1. Login to Brevo dashboard: https://app.brevo.com
2. Click on your name (top right) → **SMTP & API**
3. Or go directly to: https://app.brevo.com/settings/keys/smtp
4. In the SMTP section, you'll see:
   - **Login**: Your email address (e.g., your-email@gmail.com)
   - **SMTP Key**: Click "Generate a new SMTP key" if you don't have one
5. **IMPORTANT**: Copy and save your SMTP key securely (you can't view it again!)

Example SMTP key format: `xsmtpsib-a1b2c3d4e5f6g7h8-9i0j1k2l3m4n5o6p`

## Step 3: Configure Laravel .env File

Open your `.env` file and update these settings:

```env
# Mail Configuration - Brevo SMTP
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=xsmtpsib-your-smtp-key-here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@trakerja.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Replace:**

- `your-email@gmail.com` → Your Brevo login email
- `xsmtpsib-your-smtp-key-here` → Your actual SMTP key from Step 2
- `noreply@trakerja.com` → Your sender email (can be any email, Brevo will handle it)

## Step 4: Verify Sender Email (Required for Production)

1. In Brevo dashboard, go to: **Senders** → **Add a sender**
2. Add your sender email (e.g., `noreply@trakerja.com`)
3. Brevo will send a verification email
4. Click the verification link in the email
5. Once verified, you can send emails from that address

**Note**: For testing, you can skip this and use any email, but for production you should verify your sender domain.

## Step 5: Test Email Sending

Run these commands in your terminal:

```bash
# Clear config cache
php artisan config:clear

# Test email via Tinker
php artisan tinker
```

In Tinker, run:

```php
Mail::raw('This is a test email from TraKerja using Brevo SMTP!', function($message) {
    $message->to('bimadharmawan6@gmail.com')
            ->subject('Test Email - TraKerja');
});
```

Press `Ctrl+C` to exit Tinker.

Check your email inbox (and spam folder) for the test email.

## Step 6: Monitor Email Activity

1. Go to Brevo Dashboard: https://app.brevo.com
2. Navigate to: **Statistics** → **Email**
3. You can see:
   - Emails sent
   - Delivery rate
   - Open rate
   - Click rate
   - Bounces and spam reports

## Troubleshooting

### Problem: "Authentication failed"

**Solution**:

- Double-check your SMTP username and password
- Make sure you copied the full SMTP key (starts with `xsmtpsib-`)
- Run `php artisan config:clear`

### Problem: Email not received

**Solution**:

- Check spam folder
- Verify sender email in Brevo dashboard
- Check Brevo dashboard for bounce/error messages
- Make sure you're within the 300 emails/day limit

### Problem: "Connection refused"

**Solution**:

- Check your server firewall allows outbound port 587
- Try port 465 with `MAIL_ENCRYPTION=ssl`
- Make sure your hosting provider doesn't block SMTP

## Production Checklist

- [ ] Brevo account created and verified
- [ ] SMTP credentials configured in `.env`
- [ ] Sender email verified in Brevo
- [ ] Test email sent successfully
- [ ] Welcome email template tested
- [ ] Interview reminder email tested
- [ ] Goal achievement email tested
- [ ] `.env` file added to `.gitignore` (security)
- [ ] Production `.env` updated on server
- [ ] `php artisan config:cache` run on production server

## Daily Limits (Free Tier)

- **300 emails per day** (9,000 per month)
- Unlimited contacts
- Email templates included
- Basic analytics included

**Need more?** Upgrade to:

- Starter: €25/month → 20,000 emails/month
- Business: €65/month → 100,000 emails/month

## Additional Features (Optional)

### 1. Use Brevo API (Alternative to SMTP)

```env
MAIL_MAILER=brevo
BREVO_API_KEY=your-api-key
```

### 2. Email Templates in Brevo

- Create beautiful email templates in Brevo dashboard
- Use template IDs in your Laravel code
- Better tracking and analytics

### 3. Add Custom Domain

- Point your domain's DNS to Brevo
- Send emails from `@yourdomain.com`
- Better deliverability and trust

## Support & Resources

- Brevo Documentation: https://developers.brevo.com/
- Brevo Support: https://help.brevo.com/
- Laravel Mail Docs: https://laravel.com/docs/mail

---

**Setup completed? Test your emails and you're ready for production! 🚀**
