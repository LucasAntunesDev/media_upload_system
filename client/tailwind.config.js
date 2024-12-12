/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{html,js,svelte,ts}'],
  theme: {
    // fontSize: {
    //   "h1": "2.25rem",
    //   "h2": "text-3xl",
    //   "h3": "text-2xl",
    //   "h4": "text-xl",
    //   "h5": "text-lg",
    //   "h6": "1rem",
    // },
    extend: {
      colors: {
        primary: '#ff0033',
        'primary-light': '#f87171',

        secondary: '#D90B6D',
        'secondary-light': '#f472b6',

        gray: {
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
          950: '#020617',
        },

        success: '#059669',
        'success-dark': '#065f46',

        danger: '#e11d48',
        'danger-dark': '#881337',

        warning: '#FACC15',
        'warning-dark': '#854D0E',
      },

      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
