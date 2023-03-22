/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,twig}","./node_modules/tw-elements/dist/js/**/*.js"],
  plugins: [
    require("tw-elements/dist/plugin"),
    // require('@tailwindcss/forms'),
    // require('@tailwindcss/typography')
  ]
}
