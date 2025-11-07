# Nomadiq Frontend

A modern, next-generation frontend for Nomadiq - a premium coastal experience brand.

## Features

- 🎨 Modern, minimalist design with Nomadiq branding
- 🏝️ Package listings with filtering
- 📱 Fully responsive design
- ⚡ Built with Next.js 14 and React 18
- 🎯 TypeScript for type safety
- 💅 Tailwind CSS for styling
- 🔌 API integration with Laravel backend

## Getting Started

### Prerequisites

- Node.js 18+ and npm/yarn
- Laravel backend running on `http://localhost:8000` (or configure `NEXT_PUBLIC_API_URL`)

### Installation

1. Install dependencies:
```bash
npm install
# or
yarn install
```

2. Create a `.env.local` file:
```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

3. Run the development server:
```bash
npm run dev
# or
yarn dev
```

4. Open [http://localhost:3000](http://localhost:3000) in your browser.

## Project Structure

```
frontend/
├── app/                    # Next.js app directory
│   ├── packages/          # Package listing and detail pages
│   ├── layout.tsx         # Root layout
│   ├── page.tsx           # Homepage
│   └── globals.css        # Global styles
├── components/            # React components
│   ├── Header.tsx         # Navigation header
│   ├── Hero.tsx           # Hero section
│   ├── Packages.tsx       # Package listings
│   ├── Testimonials.tsx   # Testimonials section
│   └── ...
├── lib/                   # Utilities
│   └── api.ts            # API client
└── public/               # Static assets
```

## Nomadiq Brand Colors

- **Nomadiq Black**: `#181818`
- **Nomadiq Sand**: `#E3D5C4`
- **Nomadiq Mist**: `#C7D3CC`
- **Nomadiq Copper**: `#C67B52`
- **Nomadiq Bone**: `#F9F7F3`
- **Nomadiq Sky**: `#B3C9C6`

## Typography

- **Headings**: DM Serif Display
- **Body**: Inter

## Building for Production

```bash
npm run build
npm start
```

## Environment Variables

- `NEXT_PUBLIC_API_URL` - Laravel API base URL (default: `http://localhost:8000/api`)

## License

MIT

