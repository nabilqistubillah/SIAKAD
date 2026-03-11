/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Inter"', 'sans-serif'],
      },
      colors: {
        primary: '#4f46e5', // Indigo 600
        secondary: '#0ea5e9', // Sky 500
        dark: '#0f172a', // Slate 900
      }
    },
  },
  plugins: [],
}
