const express = require('express');
const puppeteer = require('puppeteer');

const app = express();
app.use(express.json());

app.post('/scrape', async (req, res) => {
    const { url, proxy } = req.body;
    
    if (!url) {
        return res.status(400).json({ success: false, error: 'URL parameter is required.' });
    }

    console.log(`[Puppeteer Engine] Request received for: ${url}`);
    
    let browser;
    try {
        const args = [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-accelerated-2d-canvas',
            '--no-first-run',
            '--no-zygote',
            '--disable-gpu'
        ];
        
        if (proxy) {
            console.log(`[Puppeteer Engine] Routing through proxy: ${proxy}`);
            args.push(`--proxy-server=${proxy}`);
        }
        
        browser = await puppeteer.launch({
            headless: 'new',
            args: args
        });
        
        const page = await browser.newPage();
        
        // Emulate realistic user agent
        await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
        await page.setViewport({ width: 1280, height: 800 });
        
        // Set extra headers
        await page.setExtraHTTPHeaders({
            'Accept-Language': 'en-US,en;q=0.9,id;q=0.8'
        });

        // Set timeout to 30 seconds
        const response = await page.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });
        
        if (!response) {
            throw new Error('Failed to load page: no response object received.');
        }

        const status = response.status();
        console.log(`[Puppeteer Engine] Page loaded with status: ${status}`);

        if (status >= 400) {
            throw new Error(`Target page returned HTTP error code: ${status}`);
        }

        // Wait a small delay for any client-side layout adjustments
        await new Promise(r => setTimeout(r, 1000));

        const html = await page.content();
        const title = await page.title();

        await browser.close();
        
        console.log(`[Puppeteer Engine] Successfully parsed ${html.length} bytes for: ${url}`);

        res.json({
            success: true,
            title: title,
            html: html
        });
    } catch (err) {
        console.error(`[Puppeteer Engine Error] Failed scraping: ${url} -> ${err.message}`);
        if (browser) {
            try {
                await browser.close();
            } catch (e) {
                // Ignore closing error
            }
        }
        res.status(500).json({ success: false, error: err.message });
    }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, '0.0.0.0', () => {
    console.log(`Headless Render Engine running on port ${PORT}`);
});
