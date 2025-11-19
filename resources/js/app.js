import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

// Only initialize Alpine if it hasn't been initialized yet
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.plugin(focus);
    Alpine.plugin(collapse);
    Alpine.start();
}
