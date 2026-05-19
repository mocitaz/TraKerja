// Listen for requests from popup.js
chrome.runtime.onMessage.addListener((request, sender, sendResponse) => {
    if (request.action === "getJobDetails") {
        
        let jobData = {
            position: "",
            company: "",
            location: ""
        };

        const url = window.location.href;

        // 1. LINKEDIN LOGIC
        if (url.includes("linkedin.com")) {
            try {
                // Judul posisi
                const titleEl = document.querySelector(".job-details-jobs-unified-top-card__job-title") || 
                                document.querySelector(".jobs-unified-top-card__job-title") ||
                                document.querySelector("h1.t-24") ||
                                document.querySelector("h2.t-24") ||
                                document.querySelector(".job-card-list__title") ||
                                document.querySelector("h1");
                if (titleEl) jobData.position = titleEl.innerText.trim();
            } catch(e) {}

            try {
                // Nama Perusahaan
                const companyEl = document.querySelector(".job-details-jobs-unified-top-card__company-name") ||
                                  document.querySelector(".jobs-unified-top-card__company-name") ||
                                  document.querySelector(".app-aware-link") ||
                                  document.querySelector(".job-details-jobs-unified-top-card__primary-description a") ||
                                  document.querySelector(".job-card-container__company-name");
                if (companyEl) jobData.company = companyEl.innerText.trim();
            } catch(e) {}

            try {
                // Lokasi
                const locationEl = document.querySelector(".job-details-jobs-unified-top-card__bullet") ||
                                   document.querySelector(".jobs-unified-top-card__bullet") ||
                                   document.querySelector(".job-details-jobs-unified-top-card__primary-description-container span.tvm__text") ||
                                   document.querySelector(".job-details-jobs-unified-top-card__primary-description span:nth-child(3)") ||
                                   document.querySelector(".job-details-jobs-unified-top-card__primary-description span");
                if (locationEl) jobData.location = locationEl.innerText.trim();
            } catch(e) {}
        } 
        // 2. GLINTS LOGIC
        else if (url.includes("glints.com")) {
            const titleEl = document.querySelector("h1.TopFoldsc__JobOverViewTitle-sc-kklg8i-3") || document.querySelector("h1");
            if (titleEl) jobData.position = titleEl.innerText.trim();

            const companyEl = document.querySelector("div.TopFoldsc__CompanyDescriptionContainer-sc-kklg8i-9 a");
            if (companyEl) jobData.company = companyEl.innerText.trim();

            const locationEl = document.querySelector("div.TopFoldsc__JobOverViewCompanyLocation-sc-kklg8i-5");
            if (locationEl) jobData.location = locationEl.innerText.trim();
        }
        // 3. JOBSTREET LOGIC (Contoh dasar, struktur class JobStreet dinamis)
        else if (url.includes("jobstreet")) {
            const titleEl = document.querySelector("h1");
            if (titleEl) jobData.position = titleEl.innerText.trim();
            
            // Biasa company ada di span setelah h1
            const companyEl = document.querySelector("[data-automation='advertiser-name']");
            if (companyEl) jobData.company = companyEl.innerText.trim();

            const locationEl = document.querySelector("[data-automation='job-location']");
            if (locationEl) jobData.location = locationEl.innerText.trim();
        }
        // 4. KALIBRR
        else if (url.includes("kalibrr")) {
            const titleEl = document.querySelector("h1");
            if (titleEl) jobData.position = titleEl.innerText.trim();
            
            // Nama perusahaan biasanya ada di elemen h2 atau link yang mengarah ke profil perusahaan
            const companyEl = document.querySelector("a[href*='/c/']") || document.querySelector("h2");
            if (companyEl) jobData.company = companyEl.innerText.trim();
        }
        // 5. DEALL JOBS
        else if (url.includes("deall") || url.includes("usedeall")) {
            const titleEl = document.querySelector("h1");
            if (titleEl) jobData.position = titleEl.innerText.trim();
            
            // Deall sering meletakkan nama perusahaan di class tertentu atau h2
            const companyEl = document.querySelector(".company-name") || document.querySelector("h2");
            if (companyEl) jobData.company = companyEl.innerText.trim();
        }
        // 6. TALENTICS
        else if (url.includes("talentics.id")) {
            const titleEl = document.querySelector("h1");
            if (titleEl) jobData.position = titleEl.innerText.trim();
            
            const companyEl = document.querySelector(".company-name") || document.querySelector("h2");
            if (companyEl) jobData.company = companyEl.innerText.trim();
        }

        // Clean up text (buang baris baru dll)
        jobData.position = jobData.position.replace(/(\r\n|\n|\r)/gm, " ");
        jobData.company = jobData.company.replace(/(\r\n|\n|\r)/gm, " ");
        jobData.location = jobData.location.replace(/(\r\n|\n|\r)/gm, " ");

        // Send back to popup
        sendResponse(jobData);
    }
});

// --- AUTO NOTIFICATION LOGIC ---
let lastNotifiedJob = ""; // Simpan nama pekerjaan terakhir agar tidak spam notif di loker yang sama

function checkAndNotify() {
    if (document.getElementById('trakerja-auto-notify')) return;

    const url = window.location.href;
    let currentJob = "";

    try {
        if (url.includes("linkedin.com")) {
            const urlObj = new URL(url);
            const jobId = urlObj.searchParams.get('currentJobId') || urlObj.searchParams.get('jobId');
            
            let activeCard = null;
            if (jobId) {
                activeCard = document.querySelector(`[data-job-id="${jobId}"]`) ||
                             document.querySelector(`[data-entity-urn*="${jobId}"]`) ||
                             document.querySelector(`[data-occludable-job-id="${jobId}"]`) ||
                             document.querySelector('.job-card-container--active');
            }

            if (activeCard) {
                const cardTitle = activeCard.querySelector('.job-card-list__title') || activeCard.querySelector('strong');
                if (cardTitle) currentJob = cardTitle.innerText.trim();
            }

            if (!currentJob) {
                const rightPane = document.querySelector('.jobs-search__job-details--wrapper') ||
                                  document.querySelector('.jobs-search__right-rail') ||
                                  document.querySelector('.jobs-search__job-details--container') || 
                                  document.querySelector('.job-view-layout') || 
                                  document.querySelector('.jobs-details__main-content') || 
                                  document.querySelector('.scaffold-layout__detail') ||
                                  document.querySelector('.jobs-search-two-pane__details') ||
                                  document.querySelector('main') || document.body;
                                  
                if (rightPane) {
                    const titleEl = rightPane.querySelector(".job-details-jobs-unified-top-card__job-title") || 
                                    rightPane.querySelector(".jobs-unified-top-card__job-title") ||
                                    rightPane.querySelector("h2.t-24") ||
                                    rightPane.querySelector("h1.t-24") ||
                                    rightPane.querySelector("h2.artdeco-entity-lockup__title");
                    if (titleEl) {
                        currentJob = titleEl.innerText.trim();
                    } else {
                        let candidates = [];
                        
                        if (jobId) {
                            let links = Array.from(rightPane.querySelectorAll(`a[href*="${jobId}"]`)).filter(l => l.innerText.trim().length > 5);
                            candidates.push(...links);
                        }
                        
                        let headings = Array.from(rightPane.querySelectorAll('h1, h2, .t-24')).filter(l => l.innerText.trim().length > 5);
                        candidates.push(...headings);
                        
                        candidates.sort((a, b) => b.innerText.trim().length - a.innerText.trim().length);
                        
                        const blacklist = ["easy apply", "save", "share", "more", "see all", "explore", "sign in", "apply", "notification", "messaging", "profile", "linkedin", "jobs", "hiring", "collections", "results"];
                        const valid = candidates.filter(l => !blacklist.some(b => l.innerText.toLowerCase().includes(b)));
                        
                        if (valid.length > 0) currentJob = valid[0].innerText.trim();
                    }
                }
            }
        } else if (url.includes("glints.com")) {
            const titleEl = document.querySelector("h1.TopFoldsc__JobOverViewTitle-sc-kklg8i-3") || document.querySelector("h1");
            if (titleEl) currentJob = titleEl.innerText.trim();
        } else if (url.includes("jobstreet")) {
            const titleEl = document.querySelector("[data-automation='job-detail-title']") || 
                            document.querySelector("[data-automation='job-title']") || 
                            document.querySelector("h1.job-title") ||
                            document.querySelector("h1");
            if (titleEl) {
                let txt = titleEl.innerText.trim();
                if (txt.toLowerCase().includes("lowongan kerja di indonesia")) {
                    const titleFallback = document.querySelectorAll("h1")[1];
                    if (titleFallback) txt = titleFallback.innerText.trim();
                }
                currentJob = txt;
            }
        } else if (url.includes("kalibrr") || url.includes("deall") || url.includes("usedeall") || url.includes("talentics.id")) {
            const titleEl = document.querySelector("h1") || 
                            document.querySelector(".job-title") || 
                            document.querySelector("h2[class*='title']") || 
                            document.querySelector("h2.text-2xl") || 
                            document.querySelector("h2.text-3xl") ||
                            document.querySelector(".text-title-1") ||
                            document.querySelector("h2.font-bold");
            if (titleEl) {
                currentJob = titleEl.innerText.trim();
            }
        }

        // Ultimate Fallback untuk Notifikasi (sama dengan popup.js)
        if (!currentJob) {
            let titleText = document.title;
            titleText = titleText.replace(/\|?\s*talentics\b/i, '')
                                 .replace(/\|?\s*deall\b/i, '')
                                 .replace(/\|?\s*kalibrr\b/i, '')
                                 .replace(/\|?\s*glints\b/i, '')
                                 .replace(/\|?\s*linkedin\b/i, '')
                                 .replace(/\|?\s*jobstreet\b/i, '')
                                 .replace(/lowongan kerja di\s*/i, '')
                                 .replace(/lowongan kerja\s*/i, '')
                                 .replace(/loker\s*/i, '')
                                 .replace(/hiring\s*/i, '')
                                 .replace(/job vacancy\s*/i, '')
                                 .trim();
            let parts = titleText.split(/\s+at\s+|\s+\|\s+|\s+\-\s+/i).map(s => s.trim()).filter(s => s.length > 2 && s.toLowerCase() !== 'talentics');
            if (parts.length > 0) {
                currentJob = parts[0];
            }
        }
    } catch (e) {}

    // Jika menemukan judul pekerjaan yang valid, dan berbeda dari yang sebelumnya
    if (currentJob && currentJob.length > 2 && currentJob !== lastNotifiedJob) {
        lastNotifiedJob = currentJob;
        showNotification();
    }
}

function showNotification() {
    const div = document.createElement('div');
    div.id = 'trakerja-auto-notify';
    div.innerHTML = `
        <div style="display:flex; align-items:center; gap:14px; margin: 0; padding: 0;">
            <img src="${chrome.runtime.getURL('icons/icon.png')}" style="width:28px; height:28px; border-radius:8px; border: 1px solid #f1f5f9; box-shadow: 0 1px 2px rgba(0,0,0,0.05); flex-shrink: 0;">
            <div style="display: flex; flex-direction: column; gap: 2px;">
                <p style="margin:0; font-weight:800; font-size:14px; color:#0f172a; font-family:-apple-system, system-ui, sans-serif; letter-spacing: -0.01em;">Loker Terdeteksi!</p>
                <p style="margin:0; font-size:11px; color:#64748b; font-family:-apple-system, system-ui, sans-serif; font-weight: 500;">Klik icon TraKerja untuk menyimpan.</p>
            </div>
            <button id="trakerja-close-notify" style="background:none; border:none; cursor:pointer; font-size:18px; color:#94a3b8; margin-left:12px; padding: 4px; display:flex; align-items:center; justify-content:center; border-radius: 6px; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'; this.style.color='#ef4444';" onmouseout="this.style.background='none'; this.style.color='#94a3b8';">&times;</button>
        </div>
    `;
    div.style.cssText = `
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #ffffff;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1);
        padding: 16px 20px;
        border-radius: 16px;
        z-index: 2147483647;
        transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        opacity: 0;
        transform: translateY(20px) scale(0.95);
        pointer-events: auto;
    `;
    document.body.appendChild(div);

    // Animate in
    setTimeout(() => {
        div.style.opacity = '1';
        div.style.transform = 'translateY(0) scale(1)';
    }, 100);

    // Close action
    const closeBtn = document.getElementById('trakerja-close-notify');
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            div.style.opacity = '0';
            div.style.transform = 'translateY(20px) scale(0.95)';
            setTimeout(() => div.remove(), 400);
        });
    }

    // Auto close after 8 seconds
    setTimeout(() => {
        if(document.body.contains(div)) {
            div.style.opacity = '0';
            div.style.transform = 'translateY(20px) scale(0.95)';
            setTimeout(() => div.remove(), 400);
        }
    }, 8000);
}

// Run loop to handle SPA navigations (like LinkedIn)
setInterval(checkAndNotify, 2500);
