# 📊 QUICK REFERENCE: Feature Limits

## 🎯 MONETIZATION MODE AKTIF

### FREE TIER LIMITS:
| Fitur | Limit | Reset |
|-------|-------|-------|
| Job Tracker | 50 total | Tidak ada reset |
| CV Templates | 1 (Minimal) | - |
| CV Generation | 3/bulan | Monthly |
| CV Export | 5/bulan | Monthly |
| AI Analyzer | 1/bulan | Monthly |
| Summary Page | Timeline only | - |
| Goals | Tidak ada | - |
| Email Notif | Tidak ada | - |

### PREMIUM LIMITS:
| Fitur | Limit |
|-------|-------|
| Job Tracker | ✅ Unlimited |
| CV Templates | ✅ All 5 templates |
| CV Generation | ✅ Unlimited |
| CV Export | ✅ Unlimited |
| AI Analyzer | ⚠️ 5/bulan (reset monthly) |
| Summary Page | ✅ Full analytics |
| Goals | ✅ Unlimited |
| Email Notif | ✅ Yes |

## 💰 PRICING
- Default: **Rp 199.000** (lifetime)
- Phase 1 users: **50% discount** (Rp 99.500)

## 🔑 KEY METHODS

```php
// Job Applications
$user->canCreateJobApplication()        // true/false
$user->getJobApplicationsCount()        // current count
$user->getRemainingJobApplications()    // remaining or 'unlimited'

// CV Generation  
$user->incrementCvGenerationCount()     // true if allowed
$user->getRemainingCvGenerations()      // remaining or 'unlimited'

// AI Analyzer
$user->canAccessAiAnalyzerWithLimit()   // true/false
$user->incrementAiAnalyzerCount()       // true if allowed
$user->getRemainingAiAnalyzer()         // remaining or 'unlimited'

// CV Export (existing)
$user->incrementExportCount()           // true if allowed
$user->getRemainingExports()            // remaining or 'unlimited'

// Check premium status
$user->isPremium()                      // true/false
```

## ⚡ ERROR MESSAGES

```
❌ Job: "Limit 50 applications reached. Upgrade to Premium!"
❌ CV Gen: "Limit 3/month reached. Upgrade to Premium!"
❌ AI Free: "Limit 1/month reached. Upgrade for 5x/month!"
❌ AI Premium: "Limit 5/month reached. Resets next month."
```

## 🎛️ ADMIN CONTROL
- Path: `/admin/monetization`
- Toggle FREE ↔ PREMIUM mode
- Set premium price
- View user stats & revenue

## ⚠️ IMPORTANT NOTES
1. **AI Analyzer**: Premium = 5x/month (NOT unlimited!)
2. **Job Limit**: 50 total (NO monthly reset)
3. **Auto Reset**: CV gen, exports, AI reset monthly
4. **Free Mode**: All limits disabled when FREE mode active
