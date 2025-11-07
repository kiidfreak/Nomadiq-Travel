/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './pages/**/*.{js,ts,jsx,tsx,mdx}',
    './components/**/*.{js,ts,jsx,tsx,mdx}',
    './app/**/*.{js,ts,jsx,tsx,mdx}',
  ],
  theme: {
    extend: {
      colors: {
        // Nomadiq Neutrals Palette
        'nomadiq-black': '#181818',
        'nomadiq-sand': '#E3D5C4',
        'nomadiq-mist': '#C7D3CC',
        'nomadiq-copper': '#C67B52',
        'nomadiq-bone': '#F9F7F3',
        'nomadiq-sky': '#B3C9C6',
        // Accent colors
        'nomadiq-orange': '#FF8360',
        'nomadiq-teal': '#3BAEA0',
      },
      fontFamily: {
        'serif': ['DM Serif Display', 'serif'],
        'sans': ['Inter', 'system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}

