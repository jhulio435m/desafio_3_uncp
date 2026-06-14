

import Alpine from 'alpinejs';
import { Bell, CircleHelp, ClipboardList, Headphones, Moon, Settings, Sun, X, createIcons } from 'lucide';

window.Alpine = Alpine;
window.lucide = {
    createIcons: () => createIcons({
        icons: {
            Bell,
            CircleHelp,
            ClipboardList,
            Headphones,
            Moon,
            Settings,
            Sun,
            X,
        },
    }),
};

Alpine.store('theme', {
    dark: document.documentElement.classList.contains('dark'),
    toggle() {
        this.dark = !this.dark;
        document.documentElement.classList.toggle('dark', this.dark);
        localStorage.setItem('admin-theme', this.dark ? 'dark' : 'light');
        requestAnimationFrame(() => window.lucide?.createIcons());
    },
});

Alpine.data('notificationsPanel', (url) => ({
    open: false,
    loading: true,
    count: 0,
    items: [],
    updatedAt: null,
    intervalId: null,
    init() {
        this.fetchNotifications();
        this.intervalId = setInterval(() => this.fetchNotifications(), 20000);
    },
    async fetchNotifications() {
        try {
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) return;

            const data = await response.json();
            this.count = data.count ?? 0;
            this.items = data.items ?? [];
            this.updatedAt = data.updated_at ?? null;
        } finally {
            this.loading = false;
        }
    },
    close() {
        this.open = false;
    },
}));

window.lucide.createIcons();
Alpine.start();
