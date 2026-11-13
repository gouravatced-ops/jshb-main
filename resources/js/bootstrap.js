import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Echo is managed via CDN / Blade layout with Laravel Reverb configuration
// without requiring Vite or NPM build steps.
