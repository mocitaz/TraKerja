if ('serviceWorker' in navigator) {
    const account = @json(auth()->check() ? 'user:'.auth()->id() : 'guest');
    const purgeTraKerjaCaches = async () => {
        navigator.serviceWorker.controller?.postMessage({ type: 'PURGE_TRAKERJA_CACHES' });
        if ('caches' in window) {
            const names = await caches.keys();
            await Promise.all(names.filter(name => name.startsWith('trakerja-')).map(name => caches.delete(name)));
        }
    };
    window.prepareTraKerjaLogout = async () => {
        try { localStorage.removeItem('trakerja-sw-account'); } catch (error) {}
        await purgeTraKerjaCaches();
    };
    const registerTraKerjaWorker = async () => {
        try {
            if (localStorage.getItem('trakerja-sw-account') !== account) {
                await purgeTraKerjaCaches();
                localStorage.setItem('trakerja-sw-account', account);
            }
        } catch (error) {
            await purgeTraKerjaCaches();
        }
        const registration = await navigator.serviceWorker.register('/sw.js');
        await registration.update();
    };
    if (document.readyState === 'complete') {
        registerTraKerjaWorker().catch(console.error);
    } else {
        window.addEventListener('load', () => registerTraKerjaWorker().catch(console.error), { once: true });
    }
    document.addEventListener('submit', event => {
        if (event.target instanceof HTMLFormElement && new URL(event.target.action, location.href).pathname === '/logout') {
            window.prepareTraKerjaLogout().catch(console.error);
        }
    });
}
