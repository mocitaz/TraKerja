import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./storage/framework/views/*.php",
    "./resources/views/**/*.blade.php",
    "./resources/js/**/*.js"
  ],

  theme: {
    extend: {
      spacing: { "5.5": "1.375rem", "6.5": "1.625rem" },
      boxShadow: {
        "3xs": "0 1px 2px 0 rgb(0 0 0 / 0.025)",
        "2xs": "0 1px 2px 0 rgb(0 0 0 / 0.05)",
        xs: "0 1px 3px 0 rgb(0 0 0 / 0.05)"
      },
      scale: { 97: ".97" },
      backdropBlur: { xs: "2px" },
      fontFamily: {
        sans: ["Inter", "Plus Jakarta Sans", "Figtree", "ui-sans-serif", "system-ui", "-apple-system", "BlinkMacSystemFont", "Segoe UI", "Roboto", "Helvetica Neue", "Arial", "sans-serif"],
        mono: ["JetBrains Mono", "ui-monospace", "SFMono-Regular", "Menlo", "Monaco", "Consolas", "Liberation Mono", "Courier New", "monospace"]
      },
      colors: {
        ui: { strong: "#27272a", muted: "#52525b", disabled: "#52525b" },
        zinc: {"150": "#ececee", "155": "#ebebed", "250": "#dcdce0", "350": "#bbbbc1", "450": "#898992", "455": "#878790", "550": "#62626b", "555": "#606069", "650": "#494951", "655": "#48484f", "750": "#333338", "755": "#323237", "805": "#262629", "850": "#202023", "855": "#1f1f22", "955": "#09090b"},
        amber: {"55": "#fffae7", "150": "#feeda9", "250": "#fddd6c", "350": "#fcc939", "450": "#f8af18", "555": "#e68908", "650": "#c76508", "850": "#853b0f", "955": "#451a03"},
        emerald: {"55": "#e9fdf3", "150": "#bcf7db", "155": "#baf6d9", "205": "#a4f2cf", "250": "#8bedc4", "350": "#51dda8", "450": "#22c68d", "555": "#0aa674", "650": "#058760", "750": "#056c4f", "855": "#065640"},
        rose: {"55": "#fff0f1", "150": "#ffd9dd", "155": "#fed7dc", "250": "#feb9c1", "350": "#fc8b9a", "355": "#fc8898", "650": "#d01842", "750": "#af123b", "955": "#4c0519"},
        slate: {"150": "#eaeff5", "450": "#7c8ca2", "455": "#7a899f", "550": "#56657a", "555": "#546378", "650": "#3d4b5f", "750": "#293548", "850": "#172033"},
        blue: {"55": "#edf5ff", "150": "#cde3fe", "155": "#cce2fe", "650": "#2159e2"},
        red: {"55": "#fef0f0", "150": "#fed6d6", "250": "#fdb8b8", "450": "#f45b5b", "555": "#e53434", "650": "#cb2121", "750": "#a91c1c"},
        orange: {"55": "#fff6eb", "250": "#fec98f", "850": "#8b3112"},
        indigo: {"55": "#edf1ff", "650": "#493fd8"},
        // TraKerja Brand Colors - Purple Theme
        primary: {
          50: "#f5f3ff", // Lightest purple
          100: "#ede9fe", // Very light purple
          200: "#ddd6fe", // Light purple
          300: "#c4b5fd", // Soft purple
          400: "#a78bfa", // Medium light purple
          500: "#a570f0", // Main brand purple (base)
          600: "#a570f0", // Medium purple
          650: "#9333ea", // Strong brand purple (accent)
          700: "#9333ea", // Strong purple
          800: "#7c3aed", // Dark purple
          900: "#6d28d9", // Darkest purple
          750: "#8837ec",
          850: "#7531e3",
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

  plugins: [forms, ({ addUtilities }) => addUtilities({
    ".outline-hidden": { outline: "2px solid transparent", outlineOffset: "2px" }
  })]
};
