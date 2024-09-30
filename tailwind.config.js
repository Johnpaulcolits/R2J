/** @type {import('tailwindcss').Config} */
module.exports = {
  
  content: [
    "./view/**/*.{html,js,php}",  // Include all HTML, JS, and PHP files in the 'view' directory and its subdirectories
    "./controller/**/*.{php}",     // Include all PHP files in the 'controller' directory
    "./admin/**/*.{html,js,php}",  // Include all relevant files in the 'admin' directory
    "./src/**/*.{html,js,php}", 
    "./view/*.{html,js}"],
  theme: {
    extend: { 
      boxShadow: {
        'custom': '0px 4px 4px 0px #00000040',
      }
     },
    // screens: {
    //   'phone': '375px',
    //   // => @media (min-width: 375px) { ... }
    //   'tablet': '640px',
    //   // => @media (min-width: 640px) { ... }

    //   'laptop': '1024px',
    //   // => @media (min-width: 1024px) { ... }

    //   'desktop': '1280px',
    //   // => @media (min-width: 1280px) { ... }
    // },
  },
  plugins: [],
}

