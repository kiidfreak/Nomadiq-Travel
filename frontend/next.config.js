/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  images: {
    domains: [
      'localhost',
      'nomadiq.com',
      'www.nomadiq.com',
      'api.nomadiq.com',
      'nevcompany2.test',
      '*.railway.app', // Railway backend URLs
      '*.vercel.app', // Vercel preview URLs
    ],
    remotePatterns: [
      {
        protocol: 'https',
        hostname: '**.railway.app',
        pathname: '/storage/**',
      },
      {
        protocol: 'https',
        hostname: 'api.nomadiq.com',
        pathname: '/storage/**',
      },
      {
        protocol: 'https',
        hostname: 'nomadiq.com',
        pathname: '/storage/**',
      },
      {
        protocol: 'http',
        hostname: 'localhost',
        pathname: '/storage/**',
      },
      {
        protocol: 'https',
        hostname: 'nevcompany2.test',
        pathname: '/storage/**',
      },
    ],
    unoptimized: process.env.NODE_ENV === 'development', // Enable optimization in production
  },
  env: {
    NEXT_PUBLIC_API_URL: process.env.NEXT_PUBLIC_API_URL || 'https://nevcompany2.test/api',
  },
}

module.exports = nextConfig

