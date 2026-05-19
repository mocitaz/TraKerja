const API_BASE = 'http://localhost:8000/api'; // Ganti ke URL production nanti

document.addEventListener('DOMContentLoaded', async () => {
    const loginView = document.getElementById('login-view');
    const saveView = document.getElementById('save-view');
    const logoutBtn = document.getElementById('logout-btn');
    const loginForm = document.getElementById('login-form');
    const jobContainer = document.getElementById('job-details-container');
    const noJobState = document.getElementById('no-job-detected');
    const btnSave = document.getElementById('btn-save');
    const provinceSelect = document.getElementById('job-province');
    const citySelect = document.getElementById('job-city');

    // 0. Init Dropdowns
    if (typeof INDONESIA_LOCATIONS !== 'undefined') {
        Object.keys(INDONESIA_LOCATIONS).sort().forEach(prov => {
            const opt = document.createElement('option');
            opt.value = prov;
            opt.textContent = prov;
            provinceSelect.appendChild(opt);
        });

        provinceSelect.addEventListener('change', (e) => {
            const prov = e.target.value;
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            if (prov && INDONESIA_LOCATIONS[prov]) {
                INDONESIA_LOCATIONS[prov].forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city;
                    opt.textContent = city;
                    citySelect.appendChild(opt);
                });
            }
        });
    }

    // 1. Cek Token
    const data = await chrome.storage.local.get(['token', 'user']);
    if (data.token) {
        showSaveView(data.user);
    } else {
        showLoginView();
    }

    // 2. Handle Login
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const errorDiv = document.getElementById('login-error');
        const btn = document.getElementById('btn-login');

        btn.textContent = 'Memproses...';
        errorDiv.classList.add('hidden');

        try {
            const res = await fetch(`${API_BASE}/extension/login`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ email, password })
            });
            const result = await res.json();

            if (res.ok) {
                await chrome.storage.local.set({ token: result.token, user: result.user });
                showSaveView(result.user);
            } else {
                errorDiv.textContent = result.message || 'Login gagal';
                errorDiv.classList.remove('hidden');
            }
        } catch (err) {
            errorDiv.textContent = 'Gagal koneksi ke server.';
            errorDiv.classList.remove('hidden');
        } finally {
            btn.textContent = 'Login';
        }
    });

    // 3. Handle Logout
    logoutBtn.addEventListener('click', async () => {
        await chrome.storage.local.remove(['token', 'user']);
        showLoginView();
    });

    // 4. Handle Save Job
    btnSave.addEventListener('click', async () => {
        const tokenData = await chrome.storage.local.get('token');
        const msgDiv = document.getElementById('save-message');
        
        btnSave.textContent = 'Menyimpan...';
        btnSave.disabled = true;

        // Ambil nama platform yg sesuai
        let platformName = 'Other';
        const link = window.currentJobLink || '';
        if (link.includes('linkedin')) platformName = 'LinkedIn';
        else if (link.includes('jobstreet')) platformName = 'JobStreet';
        else if (link.includes('glints')) platformName = 'Glints';
        else if (link.includes('kalibrr')) platformName = 'Kalibrr';
        else if (link.includes('deall')) platformName = 'Dealls';
        else if (link.includes('talentics')) platformName = 'Talentics';

        const prov = document.getElementById('job-province').value;
        const city = document.getElementById('job-city').value;
        
        if (!prov || !city) {
            alert("Mohon pilih Provinsi dan Kota terlebih dahulu.");
            btnSave.textContent = 'Simpan ke Kanban';
            btnSave.disabled = false;
            return;
        }

        let cleanLink = window.currentJobLink || '';
        
        // Clean up LinkedIn URLs to avoid "Data too long" errors in DB
        try {
            if (cleanLink.includes('linkedin.com')) {
                const urlObj = new URL(cleanLink);
                const jobId = urlObj.searchParams.get('currentJobId') || urlObj.searchParams.get('jobId');
                if (jobId) {
                    cleanLink = `https://www.linkedin.com/jobs/view/${jobId}`;
                } else {
                    cleanLink = cleanLink.split('?')[0];
                }
            } else if (cleanLink.length > 250) {
                cleanLink = cleanLink.split('?')[0]; // General fallback for other platforms
            }
        } catch(e) {}

        const payload = {
            position: document.getElementById('job-position').value,
            company_name: document.getElementById('job-company').value,
            location: `${city}, ${prov}`,
            career_level: document.getElementById('job-level').value,
            platform: platformName, 
            platform_link: cleanLink
        };

        try {
            const res = await fetch(`${API_BASE}/extension/jobs`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${tokenData.token}`
                },
                body: JSON.stringify(payload)
            });
            
            const result = await res.json();
            msgDiv.classList.remove('hidden', 'msg-error', 'msg-success');

            if (res.ok) {
                msgDiv.textContent = 'Berhasil disimpan!';
                msgDiv.classList.add('msg-success');
                setTimeout(() => window.close(), 1500); // Tutup popup otomatis
            } else {
                msgDiv.textContent = result.message || 'Gagal menyimpan.';
                msgDiv.classList.add('msg-error');
            }
        } catch (err) {
            msgDiv.classList.remove('hidden');
            msgDiv.textContent = 'Koneksi error.';
            msgDiv.classList.add('msg-error');
        } finally {
            btnSave.innerHTML = '<i class="ph ph-floppy-disk"></i> Simpan ke Kanban';
            btnSave.disabled = false;
        }
    });

    // --- Helper Functions ---
    function showLoginView() {
        loginView.classList.remove('hidden');
        saveView.classList.add('hidden');
        logoutBtn.classList.add('hidden');
    }

    function showSaveView(user) {
        loginView.classList.add('hidden');
        saveView.classList.remove('hidden');
        logoutBtn.classList.remove('hidden');
        document.getElementById('user-greeting').textContent = `Halo, ${user.name.split(' ')[0]}!`;
        detectJobInActiveTab();
    }

    function detectJobInActiveTab() {
        chrome.tabs.query({active: true, currentWindow: true}, (tabs) => {
            const tab = tabs[0];
            // Cek apakah URL valid
            if (tab.url.includes('linkedin.com') || tab.url.includes('glints.com') || tab.url.includes('jobstreet') || 
                tab.url.includes('kalibrr') || tab.url.includes('deall') || tab.url.includes('talentics')) {
                
                // Minta data secara langsung dari halaman aktif (dijamin jalan walau belum di-refresh)
                chrome.scripting.executeScript({
                    target: { tabId: tab.id },
                    function: scrapeJobData
                }, (results) => {
                    noJobState.classList.add('hidden');
                    jobContainer.classList.remove('hidden');
                    window.currentJobLink = tab.url;

                    if (!chrome.runtime.lastError && results && results[0] && results[0].result) {
                        const response = results[0].result;
                        
                        document.getElementById('job-position').value = response.position || '';
                        document.getElementById('job-company').value = response.company || '';
                        
                        if (response.location) {
                            let detected = response.location.toLowerCase();
                            document.getElementById('detected-location').textContent = `Terdeteksi: ${response.location}`;
                            document.getElementById('detected-location-container').style.display = 'flex';
                            
                            // Translate English phrases to Indonesian for matching
                            detected = detected.replace('central jakarta', 'jakarta pusat')
                                             .replace('south jakarta', 'jakarta selatan')
                                             .replace('north jakarta', 'jakarta utara')
                                             .replace('east jakarta', 'jakarta timur')
                                             .replace('west jakarta', 'jakarta barat')
                                             .replace('south tangerang', 'tangerang selatan')
                                             .replace('jakarta metropolitan area', 'jakarta pusat')
                                             .replace('greater jakarta', 'jakarta pusat');
                                             
                            if (detected === 'jakarta' || detected === 'dki jakarta') {
                                detected = 'dki jakarta';
                            }
                            if (detected === 'jogja' || detected === 'yogyakarta') {
                                detected = 'di yogyakarta';
                            }

                            // Coba auto-select Provinsi & Kota
                            let foundProv = null;
                            let foundCity = null;

                            if (typeof INDONESIA_LOCATIONS !== 'undefined') {
                                for (const [prov, cities] of Object.entries(INDONESIA_LOCATIONS)) {
                                    for (const city of cities) {
                                        const cleanCity = city.toLowerCase().replace(/(kota|kabupaten)\s/g, '').trim();
                                        if (detected.includes(cleanCity)) {
                                            foundProv = prov;
                                            foundCity = city;
                                            break;
                                        }
                                    }
                                    if (foundProv) break;
                                }

                                // Fallback pencarian manual nama provinsi (misal: "Jawa Barat")
                                if (!foundProv) {
                                    for (const prov of Object.keys(INDONESIA_LOCATIONS)) {
                                        if (detected.includes(prov.toLowerCase())) {
                                            foundProv = prov;
                                            break;
                                        }
                                    }
                                }

                                if (foundProv) {
                                    provinceSelect.value = foundProv;
                                    // Trigger event change manual
                                    provinceSelect.dispatchEvent(new Event('change'));
                                    
                                    if (foundCity) {
                                        setTimeout(() => { citySelect.value = foundCity; }, 50);
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                noJobState.classList.remove('hidden');
                jobContainer.classList.add('hidden');
            }
        });
    }
});

// Fungsi untuk disuntikkan langsung ke halaman (Scripting API)
function scrapeJobData() {
    let jobData = { position: "", company: "", location: "" };
    const url = window.location.href;

    if (url.includes("linkedin.com")) {
        try {
            // 1. Cari elemen Active Card (Panel Kiri)
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
                const titleEl = activeCard.querySelector('.job-card-list__title') || activeCard.querySelector('strong');
                if (titleEl) jobData.position = titleEl.innerText.trim();

                const compEl = activeCard.querySelector('.job-card-container__company-name') || activeCard.querySelector('.artdeco-entity-lockup__subtitle');
                if (compEl) jobData.company = compEl.innerText.trim();

                const locEl = activeCard.querySelector('.job-card-container__metadata-item') || activeCard.querySelector('.job-card-container__metadata-wrapper');
                if (locEl) jobData.location = locEl.innerText.trim();
            }

            // 2. Cari elemen di Right Pane (Panel Kanan) atau Body
            const rightPane = document.querySelector('.jobs-search__job-details--wrapper') ||
                              document.querySelector('.jobs-search__right-rail') ||
                              document.querySelector('.jobs-search__job-details--container') || 
                              document.querySelector('.job-view-layout') || 
                              document.querySelector('.jobs-details__main-content') || 
                              document.querySelector('.scaffold-layout__detail') ||
                              document.querySelector('.jobs-search-two-pane__details') ||
                              document.querySelector('main') || document.body;

            if (rightPane) {
                if (!jobData.position) {
                    const titleEl = rightPane.querySelector(".job-details-jobs-unified-top-card__job-title") || 
                                    rightPane.querySelector(".jobs-unified-top-card__job-title") ||
                                    rightPane.querySelector("h2.t-24") ||
                                    rightPane.querySelector("h1.t-24") ||
                                    rightPane.querySelector("h2.artdeco-entity-lockup__title");
                    if (titleEl) {
                        jobData.position = titleEl.innerText.trim();
                    } else {
                        // Gabungkan pencarian Link JobId dan Heading
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
                        
                        if (valid.length > 0) jobData.position = valid[0].innerText.trim();
                    }
                }

                if (!jobData.company) {
                    const compEl = rightPane.querySelector(".job-details-jobs-unified-top-card__company-name") ||
                                   rightPane.querySelector(".app-aware-link") ||
                                   rightPane.querySelector("a[href*='/company/']");
                    if (compEl) jobData.company = compEl.innerText.trim();
                }

                if (!jobData.location) {
                    const locEl = rightPane.querySelector(".job-details-jobs-unified-top-card__bullet") ||
                                  rightPane.querySelector(".job-details-jobs-unified-top-card__primary-description span:nth-child(3)") ||
                                  rightPane.querySelector("span.tvm__text");
                    if (locEl) jobData.location = locEl.innerText.trim();
                }
            }
        } catch(e) {}    } 
    else if (url.includes("glints.com")) {
        try {
            const titleEl = document.querySelector("h1[class*='JobOverViewTitle']") || document.querySelector("h1");
            if (titleEl) jobData.position = titleEl.innerText.trim();
            const companyEl = document.querySelector("a[href*='/companies/']") || document.querySelector("div[class*='CompanyDescriptionContainer'] a");
            if (companyEl) jobData.company = companyEl.innerText.trim();
            const locationEl = document.querySelector("div[class*='JobOverViewCompanyLocation']") || document.querySelector("div[class*='CompactOpportunityCardsc__OpportunityInfo']");
            if (locationEl) jobData.location = locationEl.innerText.trim();
        } catch(e) {}
    }
    else if (url.includes("jobstreet")) {
        try {
            const titleEl = document.querySelector("[data-automation='job-detail-title']") || 
                            document.querySelector("[data-automation='job-title']") || 
                            document.querySelector("h1.job-title") ||
                            document.querySelector("h1");
            if (titleEl) {
                let txt = titleEl.innerText.trim();
                if (txt.toLowerCase().includes("lowongan kerja di indonesia")) {
                    // Fallback jika masih menangkap H1 SEO
                    const titleFallback = document.querySelectorAll("h1")[1];
                    if (titleFallback) txt = titleFallback.innerText.trim();
                }
                jobData.position = txt;
            }
            const companyEl = document.querySelector("[data-automation='advertiser-name']") || document.querySelector("[data-automation='job-company']");
            if (companyEl) jobData.company = companyEl.innerText.trim();
            const locationEl = document.querySelector("[data-automation='job-detail-location']") || document.querySelector("[data-automation='job-location']");
            if (locationEl) jobData.location = locationEl.innerText.trim();
        } catch(e) {}
    }
    else if (url.includes("deall") || url.includes("usedeall")) {
        try {
            const titleEl = document.querySelector("h1") || document.querySelector("h2.text-2xl") || document.querySelector(".job-title");
            if (titleEl) jobData.position = titleEl.innerText.trim();

            const companyEl = document.querySelector("a[href*='/company/']") || 
                              document.querySelector("a[href*='/employer/']") ||
                              document.querySelector("p[class*='company']");
                              
            if (companyEl) {
                jobData.company = companyEl.innerText.trim();
            } else if (titleEl) {
                // Fallback: Cari elemen di sekitar Judul (biasanya perusahaan ada di parent container yang sama)
                const container = titleEl.parentElement.parentElement;
                if (container) {
                    const allTexts = Array.from(container.querySelectorAll('p, span, h2, h3, a')).map(e => e.innerText.trim());
                    const validTexts = allTexts.filter(t => t.length > 2 && t !== jobData.position && !t.toLowerCase().includes("deall") && !t.toLowerCase().includes("apply") && !t.toLowerCase().includes("save"));
                    if (validTexts.length > 0) {
                        jobData.company = validTexts[0];
                    }
                }
            }
        } catch(e) {}
    }
    else if (url.includes("kalibrr") || url.includes("talentics.id")) {
        try {
            const titleEl = document.querySelector("h1") || 
                            document.querySelector(".job-title") || 
                            document.querySelector("h2[class*='title']") || 
                            document.querySelector("h2.text-2xl") || 
                            document.querySelector("h2.text-3xl") ||
                            document.querySelector(".text-title-1") ||
                            document.querySelector("h2.font-bold");
            
            if (titleEl) {
                jobData.position = titleEl.innerText.trim();
            }

            const companyEl = document.querySelector(".company-name") || 
                              document.querySelector("a[href*='/company/']") || 
                              document.querySelector("a[href*='/employer/']") ||
                              document.querySelector("a[href*='/c/']") ||
                              document.querySelector("p[class*='company']");
                              
            if (companyEl) {
                jobData.company = companyEl.innerText.trim();
            } else {
                const logos = document.querySelectorAll("img[alt*='logo' i], img[alt*='company' i]");
                for(let logo of logos) {
                    let alt = logo.getAttribute('alt');
                    if (alt && alt.toLowerCase() !== 'logo' && alt.length > 3) {
                        jobData.company = alt.replace(/logo/i, '').trim();
                        break;
                    }
                }
            }
        } catch(e) {}
    }

    jobData.position = jobData.position.replace(/(\r\n|\n|\r)/gm, " ");
    jobData.company = jobData.company.replace(/(\r\n|\n|\r)/gm, " ");
    if (jobData.location) jobData.location = jobData.location.replace(/(\r\n|\n|\r)/gm, " ");

    // Ultimate Fallback: Parsing document.title jika posisi atau perusahaan masih kosong
    if (!jobData.position || !jobData.company) {
        let titleText = document.title;
        
        // Batal gunakan judul tab jika ini adalah halaman hasil pencarian/koleksi LinkedIn
        if (titleText.includes("Easy Apply") || titleText.includes("jobs in") || titleText.match(/^\(\d+\)/) || titleText.includes("Search |")) {
            titleText = "";
        }

        if (titleText) {
            // Bersihkan nama platform dan teks SEO dari judul tab
            titleText = titleText.replace(/\|?\s*talentics\b/i, '')
                                 .replace(/\|?\s*dealls?\b/i, '')
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
                                 
            // Pecah judul tab berdasarkan pemisah umum ( at , |, - )
            let parts = titleText.split(/\s+at\s+|\s+\|\s+|\s+\-\s+/i).map(s => s.trim()).filter(s => s.length > 2 && s.toLowerCase() !== 'talentics');
            
            if (parts.length >= 2) {
                if (!jobData.position) jobData.position = parts[0];
                if (!jobData.company) jobData.company = parts[1];
            } else if (parts.length === 1) {
                if (!jobData.position) jobData.position = parts[0];
            }
        }
    }

    // Ultimate Company Fallback via URL Slug atau Subdomain
    const platformNames = ['talentics', 'dealls', 'deall', 'usedeall', 'kalibrr', 'glints', 'linkedin', 'jobstreet'];
    if (!jobData.company || platformNames.some(p => jobData.company.toLowerCase().includes(p))) {
        let slug = null;
        
        // 1. Cek path URL (misal: /company/arya-noble/) - HINDARI KATA "jobs" atau "collections"
        const pathMatch = url.match(/\/(?:company|c|employer|companies|vacancies|job-board)\/([a-z0-9\-]+)/i);
        if (pathMatch && pathMatch[1] && pathMatch[1] !== 'collections' && pathMatch[1] !== 'view' && pathMatch[1] !== 'search') {
            slug = pathMatch[1];
        } else {
            // 2. Cek subdomain (misal: arya-noble.talentics.id)
            const subMatch = url.match(/https?:\/\/([a-z0-9\-]+)\.(?:talentics|deall|kalibrr|glints)\.[a-z]+/i);
            if (subMatch && subMatch[1] && subMatch[1] !== 'www' && subMatch[1] !== 'jobs' && subMatch[1] !== 'careers') {
                slug = subMatch[1];
            }
        }

        if (slug) {
            jobData.company = slug.split('-').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
        } else {
            // Jika gagal total, hapus teks kotor "talentics-" agar kosong dan bisa diisi manual
            jobData.company = jobData.company.replace(/talentics\-?/ig, '').trim();
        }
    }

    // Universal Location Fallback
    if (!jobData.location) {
        const allTexts = Array.from(document.querySelectorAll('span, p, div, li, h3, h4'))
                              .map(e => e.innerText ? e.innerText.trim() : '');
        for(let txt of allTexts) {
            // Jika teks pendek dan mengandung nama wilayah populer
            if (txt.length > 3 && txt.length < 50 && !txt.includes("Follow") && !txt.includes("Lowongan")) {
                if (txt.includes("Indonesia") || txt.includes("Jakarta") || txt.includes("Bandung") || 
                    txt.includes("Surabaya") || txt.includes("Bali") || txt.includes("Yogyakarta") || 
                    txt.includes("Tangerang") || txt.includes("Bekasi") || txt.includes("Depok") || 
                    txt.includes("Bogor") || txt.includes("Medan") || txt.includes("Semarang") ||
                    txt.includes("Makassar") || txt.includes("Batam") || txt.includes("Palembang")) {
                    jobData.location = txt;
                    break;
                }
            }
        }
    }

    return jobData;
}
