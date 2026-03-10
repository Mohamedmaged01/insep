import Alpine from 'alpinejs';

window.Alpine = Alpine;

// CSRF token for AJAX requests
window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// API fetch wrapper
window.apiFetch = async function (endpoint, options = {}) {
    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': window.csrfToken,
        'Accept': 'application/json',
        ...(options.headers || {}),
    };

    const res = await fetch(endpoint, { ...options, headers });
    if (!res.ok) {
        const err = await res.json().catch(() => ({ message: 'خطأ في الاتصال' }));
        throw new Error(err.message || `HTTP ${res.status}`);
    }
    return res.json();
};

// Currency formatting
window.formatPrice = function (priceEgp, currency = 'USD') {
    const rates = { EGP: 1, USD: 0.02, SAR: 0.077 };
    const value = priceEgp * (rates[currency] || 1);
    if (currency === 'EGP') return value.toLocaleString() + ' ج.م';
    if (currency === 'USD') return '$' + value.toLocaleString();
    if (currency === 'SAR') return value.toLocaleString() + ' ر.س';
    return value.toString();
};

// Counter animation
window.animateCounter = function (el, end, duration = 2000) {
    let start = 0;
    const step = end / (duration / 16);
    const timer = setInterval(() => {
        start += step;
        if (start >= end) {
            el.textContent = end.toLocaleString();
            clearInterval(timer);
        } else {
            el.textContent = Math.floor(start).toLocaleString();
        }
    }, 16);
};

Alpine.start();
