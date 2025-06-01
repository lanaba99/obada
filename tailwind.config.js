/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.jsx", // Ensure jsx is included
    "./resources/**/*.vue", // If you ever use Vue
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}