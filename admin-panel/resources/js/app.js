

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

Alpine.data('botSettingsEditor', (keys = []) => ({
    activeTab: localStorage.getItem('settings-active-tab') || 'general',
    activeGroup: localStorage.getItem('settings-active-group') || 'welcome_scope',
    searchQuery: '',
    selectedKeyId: localStorage.getItem('settings-selected-key-id')
        ? parseInt(localStorage.getItem('settings-selected-key-id'), 10)
        : null,
    keys,
    init() {
        if (!this.selectedKeyId || !this.keys.some((key) => key.id === this.selectedKeyId && key.group === this.activeGroup)) {
            this.selectFirstKeyOfGroup(this.activeGroup);
        }
    },
    setActiveTab(tab) {
        this.activeTab = tab;
        localStorage.setItem('settings-active-tab', tab);
    },
    setActiveGroup(group) {
        this.activeGroup = group;
        localStorage.setItem('settings-active-group', group);
        this.selectFirstKeyOfGroup(group);
    },
    selectKey(id) {
        this.selectedKeyId = id;
        localStorage.setItem('settings-selected-key-id', id);
    },
    selectFirstKeyOfGroup(group) {
        const keyRow = this.keys.find((key) => key.group === group);

        if (keyRow) {
            this.selectKey(keyRow.id);
        } else {
            this.selectedKeyId = null;
            localStorage.removeItem('settings-selected-key-id');
        }
    },
    matchesQuery(key, label) {
        const query = this.searchQuery.trim().toLowerCase();

        if (!query) return true;

        return key.includes(query) || label.includes(query);
    },
}));

window.lucide.createIcons();
Alpine.start();
