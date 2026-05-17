import './bootstrap';
import { createIcons, icons } from 'lucide';
import { attachNikFormatter, attachPasswordToggle } from './nik-input.js';

document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons });

    document.querySelectorAll('[data-nik-input]').forEach((el) => attachNikFormatter(el));
    document.querySelectorAll('[data-password-toggle]').forEach((el) => attachPasswordToggle(el));
});
