import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php"
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ["Figtree", ...defaultTheme.fontFamily.sans]
      },
      colors: {
        // TraKerja Brand Colors - Purple Theme
        primary: {
          50: "#f5f3ff", // Lightest purple
          100: "#ede9fe", // Very light purple
          200: "#ddd6fe", // Light purple
          300: "#c4b5fd", // Soft purple
          400: "#a78bfa", // Medium light purple
          500: "#a570f0", // Main brand purple (base)
          600: "#a570f0", // Medium purple
          700: "#9333ea", // Strong purple
          800: "#7c3aed", // Dark purple
          900: "#6d28d9", // Darkest purple
          950: "#4c1d95" // Ultra dark purple
        },
        secondary: {
          50: "#f0f4ff", // Very light blue-purple
          100: "#e0e9ff", // Light blue-purple
          200: "#c1d3ff", // Soft blue-purple
          300: "#a2bdff", // Medium blue-purple
          400: "#83a7ff", // Light blue-purple
          500: "#4e71c5", // Medium blue-purple
          600: "#4e71c5", // Strong blue-purple
          700: "#4563b1", // Deep blue-purple
          800: "#3c559d", // Dark blue-purple
          900: "#334789" // Darkest blue-purple
        },
        accent: {
          50: "#fdf4ff", // Very light pink-purple
          100: "#fae8ff", // Light pink-purple
          200: "#f5d0fe", // Soft pink-purple
          300: "#f0abfc", // Medium pink-purple
          400: "#e879f9", // Bright pink-purple
          500: "#d946ef", // Vibrant fuchsia
          600: "#c026d3", // Strong fuchsia
          700: "#a21caf", // Deep fuchsia
          800: "#86198f", // Dark fuchsia
          900: "#701a75" // Darkest fuchsia
        }
      }
    }
  },

  plugins: [forms]
};
