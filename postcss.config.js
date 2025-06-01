// NEW way - alternative syntax
import tailwindcss from '@tailwindcss/postcss'; // Import it
import autoprefixer from 'autoprefixer';

export default {
  plugins: [
    tailwindcss,    // Use the imported variable
    autoprefixer,
  ],
};