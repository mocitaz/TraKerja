# Job URL fetching and deployment egress

`POST /jobs/scrape-url` now delegates fetching to `JobPageFetcher`. Only HTTP on port 80 and HTTPS on port 443 are accepted, for roots/subdomains in `config/job_scraping.php`. Credentials, IP literals, malformed hostnames and unsupported domains are rejected. Every DNS answer must be public; empty/unresolved answers fail closed. Each connection pins a validated address using cURL, retains TLS hostname verification, disables proxies and connection reuse, and checks each redirect before the next request. At most three redirects are followed.

PHP's cURL extension is required. The fetcher deliberately refuses a stream-handler fallback because it would lose address pinning. Tests fake DNS and HTTP; no production or internal endpoint was contacted during verification.

## Infrastructure requirement

### Authenticated headless rendering and crawler coverage

Discovery (`ScraperSource`), extraction (`ScrapeJobDetailsJob`), and dead-link checks now use validated transports. `ScraperPageFetcher` validates the job URL before sending it to the renderer. Only a configured renderer URL **and** `SCRAPER_SERVICE_TOKEN` enable that call. The renderer receives a bearer token and `{ "url": "..." }`; HTTP redirects and inherited proxies are disabled for the service request. Missing configuration, invalid renderer results and renderer failures fall back only to the pinned `JobPageFetcher`. The old `SCRAPER_PROXIES` rotation is no longer used.

Discovery resolves relative links, compares the parsed hostname against the source's actual target domain/subdomains, and validates DNS before queueing a link. Hostname substrings in the path/query cannot authorize a different origin.

The Node renderer now:

- Binds to `127.0.0.1`, requires a bearer token of at least 32 characters, limits JSON request bodies to 8 KB, and rejects any client `proxy` field.
- Accepts at most two renders concurrently by default (`SCRAPER_MAX_CONCURRENCY`, integer 1–8); excess requests receive 429. This limit is per service process.
- Starts a dedicated loopback egress proxy per render. Every HTTP request and HTTPS CONNECT validates all DNS answers and connects to a validated public address without resolving the hostname again at connection time. Main/iframe navigation is restricted to the supported portal domains; asset requests may use other publicly resolved domains for CDN compatibility.
- Rejects non-HTTP(S) URLs, credentials, IP literals, nonstandard ports, private/reserved addresses and excessive redirects. Proxy access is restricted to HTTP port 80 and HTTPS port 443.
- Forces Chromium through that proxy, removes its implicit loopback bypass, disables QUIC/non-proxied WebRTC UDP and browser DNS resolution, bypasses service workers, and closes extra page targets. The browser sandbox remains enabled.
- Bounds proxy sockets (32), traffic (20 MB, counted conservatively across both sides), socket idle time (15 seconds), connection time (5 seconds), rendering (35 seconds after launch), and returned HTML (2 MB). Browser launch has a separate 10-second deadline. Cleanup closes proxy sockets and the browser, with a kill fallback if browser shutdown stalls.

### Deployment steps

1. Provide the **same new random** `SCRAPER_SERVICE_TOKEN` to Laravel and the Node process through the deployment secret manager; do not commit or log it.
2. Point `SCRAPER_MICROSERVICE_URL` at the loopback service when colocated. For separate hosts, expose the loopback listener only through a private, access-controlled TLS reverse proxy; do not send bearer tokens across plaintext external HTTP.
3. Rebuild Laravel configuration cache and restart queue workers and the Node service. Both services must be deployed together. A missing renderer token disables headless rendering and retains only secure direct fetching.
4. Run Chromium as a dedicated non-root user with its sandbox enabled, and apply container/process memory and CPU limits. Do not restore `--no-sandbox` to work around deployment errors. Confirm that private services and cloud metadata cannot be reached from the workload using an approved isolated network test.
5. Validate actual Chrome rendering of approved portals, rejection of missing/wrong service tokens, busy responses, timeout cleanup, and portal/CDN compatibility in staging. Puppeteer/Express and a browser binary were not installed or launched during this change; browser behavior is covered by stubs, while proxy transport tests use controlled DNS/transports and local test-owned sockets.

Targeted regression checks (no database migrations or real provider requests):

```sh
node --test tests/JavaScript/scraper-egress.test.cjs tests/JavaScript/scraper-service.test.cjs
APP_ENV=testing CACHE_STORE=array DB_CONNECTION=sqlite DB_DATABASE=:memory: LOG_CHANNEL=null php vendor/bin/phpunit --do-not-cache-result tests/Feature/ScraperEgressSecurityTest.php
```

### Workload firewall

The repository does not contain the production network/firewall configuration. The PHP guard is applied here; a host/container/cloud egress policy has **not** been applied by this change. Deployers must enforce the following for the scraping execution environment:

1. Deny destinations in private, loopback, link-local, metadata, carrier-grade NAT, documentation, benchmarking, multicast, reserved and IPv4-mapped IPv6 ranges. Apply the deny to IPv4 and IPv6, including cloud provider metadata endpoints and internal organization networks that use publicly routed address space.
2. Permit scraper outbound HTTP(S) only on TCP 80/443 to the supported job portals. Use a dedicated scraper egress policy/proxy if the infrastructure provides domain-aware filtering. Add this at the workload boundary; do not block unrelated database/cache/payment integrations of a shared application process.
3. Permit DNS only to the deployment's controlled resolver. Preserve the destination IP chosen by the fetcher; do not introduce a transparent resolver/proxy that defeats its pinning. A domain-aware forward proxy requires a separate trusted-proxy design since this implementation deliberately disables proxy inheritance.
4. Verify deny rules from the isolated scraper workload, record accepted public traffic and denied internal destinations, and monitor changes to portal/CDN DNS. Do not test this through production user requests or real metadata credentials.

Reference: [OWASP SSRF Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Server_Side_Request_Forgery_Prevention_Cheat_Sheet.html) describes allowlists, DNS checks, redirect controls and network restrictions. Infrastructure enforcement must be verified by the deployment owner before A06's network-layer recommendation is marked complete.
