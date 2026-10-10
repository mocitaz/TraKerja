# Job URL fetching and deployment egress

`POST /jobs/scrape-url` now delegates fetching to `JobPageFetcher`. Only HTTP on port 80 and HTTPS on port 443 are accepted, for roots/subdomains in `config/job_scraping.php`. Credentials, IP literals, malformed hostnames and unsupported domains are rejected. Every DNS answer must be public; empty/unresolved answers fail closed. Each connection pins a validated address using cURL, retains TLS hostname verification, disables proxies and connection reuse, and checks each redirect before the next request. At most three redirects are followed.

PHP's cURL extension is required. The fetcher deliberately refuses a stream-handler fallback because it would lose address pinning. Tests fake DNS and HTTP; no production or internal endpoint was contacted during verification.

## Infrastructure requirement

The repository does not contain the production network/firewall configuration. The PHP guard is applied here; a host/container/cloud egress policy has **not** been applied by this change. Deployers must enforce the following for the scraping execution environment:

1. Deny destinations in private, loopback, link-local, metadata, carrier-grade NAT, documentation, benchmarking, multicast, reserved and IPv4-mapped IPv6 ranges. Apply the deny to IPv4 and IPv6, including cloud provider metadata endpoints and internal organization networks that use publicly routed address space.
2. Permit scraper outbound HTTP(S) only on TCP 80/443 to the supported job portals. Use a dedicated scraper egress policy/proxy if the infrastructure provides domain-aware filtering. Add this at the workload boundary; do not block unrelated database/cache/payment integrations of a shared application process.
3. Permit DNS only to the deployment's controlled resolver. Preserve the destination IP chosen by the fetcher; do not introduce a transparent resolver/proxy that defeats its pinning. A domain-aware forward proxy requires a separate trusted-proxy design since this implementation deliberately disables proxy inheritance.
4. Verify deny rules from the isolated scraper workload, record accepted public traffic and denied internal destinations, and monitor changes to portal/CDN DNS. Do not test this through production user requests or real metadata credentials.

Reference: [OWASP SSRF Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Server_Side_Request_Forgery_Prevention_Cheat_Sheet.html) describes allowlists, DNS checks, redirect controls and network restrictions. Infrastructure enforcement must be verified by the deployment owner before A06's network-layer recommendation is marked complete.
