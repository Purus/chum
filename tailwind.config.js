/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,twig}"],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'), 
    require('daisyui'),
  ],
  daisyui: {
    themes: ["light", "dark"],
  },
}
