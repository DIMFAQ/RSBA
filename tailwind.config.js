import filament from './vendor/filament/support/tailwind.config.preset';
import forms from '@tailwindcss/forms';

export default {
  presets: [
    filament,
    require('./vendor/tallstackui/tallstackui/tailwind.config.js')
  ],
  content: [

    // filament
    './app/Filament/**/*.php',
    './resources/views/filament/**/*.blade.php',
    './vendor/filament/**/*.blade.php',

    // tallstackui
    './resources/views/**/*.blade.php',
    './vendor/tallstackui/tallstackui/src/**/*.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    forms
  ],
}

