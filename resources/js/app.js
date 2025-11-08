import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.plugin(focus);
Alpine.plugin(collapse);
Alpine.start();
