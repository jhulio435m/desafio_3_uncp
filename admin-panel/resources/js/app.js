

import Alpine from 'alpinejs';
import { CircleHelp, ClipboardList, Headphones, Settings, createIcons } from 'lucide';

window.Alpine = Alpine;
window.lucide = {
    createIcons: () => createIcons({
        icons: {
            CircleHelp,
            ClipboardList,
            Headphones,
            Settings,
        },
    }),
};

document.addEventListener('DOMContentLoaded', () => {
    window.lucide.createIcons();
});

Alpine.start();
