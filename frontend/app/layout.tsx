import type { Metadata } from 'next'
import './globals.css'

export const metadata: Metadata = {
  title: 'Nomadiq - Live. Connect. Belong.',
  description: 'Curated coastal experiences in Watamu and beyond. Premium lifestyle travel that connects you with authentic local culture.',
  keywords: 'Watamu, travel, experiences, coastal, Kenya, lifestyle, curated travel',
  icons: {
    icon: [
      { url: '/favicon.svg', type: 'image/svg+xml' },
    ],
    shortcut: '/favicon.svg',
    apple: '/favicon.svg',
  },
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="en">
      <body>{children}</body>
    </html>
  )
}

