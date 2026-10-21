# Landing and authentication image compression

Optimized the ten raster assets referenced by the current landing/auth views on 21 October 2026. Combined file size decreased from 3,411,844 to 1,025,684 bytes (69.9%). These are asset bytes, not a measured page-load time or network total.

| Asset under public/images | Before (bytes) | After (bytes) |
| --- | ---: | ---: |
| icon.png | 948496 | 122782 |
| landing/hero-portrait-white-v4.webp | 756012 | 88630 |
| landing/footer-mountains-plum-v1.webp | 829500 | 296696 |
| landing/preparation-portrait.webp | 127656 | 113128 |
| landing/indonesian-portraits.webp | 146068 | 123976 |
| landing/erlan-founder.webp | 20010 | 2700 |
| landing/review-al-ferro.webp | 242608 | 70036 |
| landing/review-bintang.webp | 161568 | 61124 |
| landing/review-aretha.webp | 43776 | 25634 |
| auth/iphone-landing-cutout.webp | 136150 | 120978 |

WebP assets were encoded using cwebp quality 84, method 6 and lossless alpha. The decorative footer uses quality 80 at 1600×533; it is displayed with a low-opacity mask. The founder portrait is 192×192 for its 52px display. Other WebP dimensions are unchanged. The shared PNG icon is a lossless RGBA PNG at 512×512, sufficient for its declared PWA size and the 200px landing wordmark. A palette conversion was evaluated but rejected to retain smooth gradients. Original assets remain recoverable through Git history.

Verification: decoded every output, checked alpha-channel equality for unchanged-size transparent images, and inspected before/after comparisons of every asset. No layout or authentication behavior changes. LandingPageTest and AuthPresentationTest completed with 785 assertions and no failures; their existing setup emits `/dev/null/.env` warnings. Local comparison artifact: `output/image-compression/all-comparison.jpg`. Unused legacy images were left alone.
