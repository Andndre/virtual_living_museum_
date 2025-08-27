import './bootstrap';
import Alpine from 'alpinejs';
import { initBackButton } from './back-button';

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Initialize back buttons
document.addEventListener('DOMContentLoaded', () => {
    // Initialize back buttons with default selector '.back-button' and fallback to home
    initBackButton('.back-button', window.location.origin);
});
