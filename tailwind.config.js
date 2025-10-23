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
          500: "#8b5cf6", // Main brand purple (base)
          600: "#7c3aed", // Medium purple
          700: "#6d28d9", // Strong purple
          800: "#5b21b6", // Dark purple
          900: "#4c1d95", // Darkest purple
          950: "#2e1065" // Ultra dark purple
        },
        secondary: {
          50: "#faf5ff", // Very light lavender
          100: "#f3e8ff", // Light lavender
          200: "#e9d5ff", // Soft lavender
          300: "#d8b4fe", // Medium lavender
          400: "#c084fc", // Light violet
          500: "#a855f7", // Medium violet
          600: "#9333ea", // Strong violet
          700: "#7e22ce", // Deep violet
          800: "#6b21a8", // Dark violet
          900: "#581c87" // Darkest violet
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
