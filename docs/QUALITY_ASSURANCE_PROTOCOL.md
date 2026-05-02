# TraKerja Comprehensive Quality Assurance Protocol
*Standard Operating Procedure (SOP) for Technical Validation & Product Excellence*

---

## 1. Executive Summary
Dokumen ini mendefinisikan standar pengetesan untuk seluruh ekosistem **TraKerja**. Setiap rilis fitur wajib melewati fase validasi ini untuk memastikan kualitas premium, akurasi data, dan pengalaman pengguna yang mulus.

---

## 2. Core Feature Testing Matrix

### A. AI Analyzer Engine (High Priority)
*Tujuan: Memastikan AI memberikan feedback akurat dan sistem limitasi bekerja sempurna.*
- [ ] **Usage Limit Logic:**
    - [ ] Verifikasi user Free hanya bisa 1x analisa (Trial).
    - [ ] Verifikasi user Premium bisa 5x analisa/bulan dan reset otomatis.
    - [ ] Pesan error yang tepat saat kuota habis.
- [ ] **API & Connectivity:**
    - [ ] Penanganan timeout jika API Vercel merespon > 150 detik.
    - [ ] Validasi file PDF (Maks 10MB) dan panjang Job Description (50-2500 karakter).
- [ ] **Result Accuracy:**
    - [ ] Verifikasi "Match Score" muncul dengan format persentase yang benar.
    - [ ] Feedback AI harus ter-render dalam format yang mudah dibaca (Justified text).

### B. Analytics & Summary Dashboard
*Tujuan: Memastikan visualisasi data mencerminkan realitas lamaran user.*
- [ ] **Funnel Accuracy:** Verifikasi transisi status dari *Applied* -> *Interview* -> *Accepted* terhitung dengan benar pada grafik funnel.
- [ ] **Time Filters:** Pengujian filter (Weekly, Monthly, All Time) terhadap seluruh widget dashboard.
- [ ] **Advanced Metrics:**
    - [ ] Perhitungan **Daily Streak** (harus bertambah jika ada input lamaran di hari berurutan).
    - [ ] **Platform Effectiveness:** Ranking platform (LinkedIn, Glints, dll) berdasarkan conversion rate.
- [ ] **Visualization:** Rendering Chart.js/ApexCharts pada resolusi layar yang berbeda.

### C. CV Builder & Template System
*Tujuan: Estetika premium dan akurasi data mapping.*
- [ ] **Template Fidelity:**
    - [ ] **Creative:** Grid asimetris tetap presisi.
    - [ ] **Elegant:** Tipografi Serif tetap selaras dan lurus.
    - [ ] **Professional:** Kolom sidebar tidak overflow.
    - [ ] **Minimalist:** Whitespace konsisten di semua section.
- [ ] **Text Alignment:** Verifikasi seluruh bagian *Summary* dan *Description* menggunakan `text-align: justify`.
- [ ] **PDF Export:** Verifikasi @media print menghilangkan elemen navigasi dan mempertahankan font premium.

### D. Job Tracker & CSV Operations
*Tujuan: Integritas data masal.*
- [ ] **CSV Import Engine:**
    - [ ] Validasi pemetaan kolom (Mapping 'Company' -> 'company_name').
    - [ ] Penanganan format tanggal yang beragam (YYYY-MM-DD vs DD/MM/YYYY).
    - [ ] Error reporting: Baris mana yang gagal diimpor dan alasannya.
- [ ] **CSV Export:** Verifikasi file yang diunduh menyertakan Summary laporan yang lengkap di bagian bawah.

### E. Interview & Calendar System
- [ ] **Calendar Rendering:** Verifikasi event interview muncul di tanggal dan jam yang tepat.
- [ ] **Stage Sync:** Perubahan stage di Tracker otomatis mengubah visualisasi di Dashboard Analytics.

### F. Goal Tracking & Cadence
- [ ] **Progress Logic:** Verifikasi bar progres mingguan (Target vs Realisasi).
- [ ] **Consistency Score:** Akurasi perhitungan berdasarkan frekuensi lamaran dalam 90 hari terakhir.

---

## 3. Technical & Performance Standards

### 3.1 UI/UX Precision
- [ ] **Mathematical Alignment:** Jarak (margin/padding) antar section harus konsisten di semua template CV.
- [ ] **Dark Mode/Light Mode:** Konsistensi warna pada elemen dashboard.
- [ ] **Responsive Flow:** Tidak ada elemen yang terpotong pada layar Mobile (iOS/Android).

### 3.2 Performance & Security
- [ ] **API Security:** Memastikan user tidak bisa mengakses hasil AI Analyzer milik user lain (ID validation).
- [ ] **Load Time:** Waktu generasi dashboard (Summary) di bawah 2 detik untuk user dengan > 500 data lamaran.

---

## 4. QA Team Release Checklist
*Setiap personil QA wajib menandatangani checklist ini sebelum kode di-merge ke Main Branch.*

1. [ ] **Fitur Baru:** Apakah sudah sesuai dengan Requirement Document?
2. [ ] **Regression:** Apakah fitur lama (CV Builder/Tracker) masih aman?
3. [ ] **UI Polish:** Apakah desain sudah terlihat "Premium" dan "Mahal"?
4. [ ] **Error Handling:** Apakah semua pesan error sudah user-friendly?

---
*Dokumen ini bersifat dinamis dan akan diperbarui seiring dengan perkembangan fitur TraKerja.*
