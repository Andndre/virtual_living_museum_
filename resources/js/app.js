import './bootstrap';
import { initBackButton } from './back-button';
import './tiptap-editor';

// Alpine is provided and started by @livewireScripts — don't import it here.
// We only expose PanoramaTiptapEditor (set in tiptap-editor.js) on window.

document.addEventListener('DOMContentLoaded', () => {
    initBackButton('.back-button', window.location.origin);
});
