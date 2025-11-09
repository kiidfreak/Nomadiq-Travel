'use client'

import { useEffect, useState } from 'react'
import Link from 'next/link'
import { packagesApi, settingsApi } from '@/lib/api'
import { MapPin, Star, Calendar } from 'lucide-react'
import { fetchCurrencyRate, usdToKsh, formatKsh, formatUsd, setUsdToKshRate } from '@/lib/currency'

interface Package {
  id: number
  title: string
  theme?: string
  tagline?: string
  description: string
  duration_days: number
  price_usd: number
  max_participants: number
  image_url: string
  highlights?: Array<{ emoji?: string; text: string }>
  destinations?: Array<{ name: string }>
}

export default function Packages() {
  const [packages, setPackages] = useState<Package[]>([])
  const [loading, setLoading] = useState(true)
  const [currencyRate, setCurrencyRate] = useState(140)

  useEffect(() => {
    const fetchData = async () => {
      try {
        // Fetch currency rate
        const rate = await fetchCurrencyRate()
        setCurrencyRate(rate)
        setUsdToKshRate(rate)

        // Fetch packages
        const response = await packagesApi.getFeatured()
        if (response.data.success) {
          setPackages(response.data.data)
        }
      } catch (error) {
        console.error('Error fetching packages:', error)
        // Fallback data with the two Watamu packages
        setPackages([
          {
            id: 1,
            title: 'Weekend Bash - 2 Nights / 1 Day',
            description: 'Join us for a high-vibe coastal weekend at Watamu: villa stay, sunset dhow, beach party, and sand dune adventure. Perfect for fun, friends & memories.',
            duration_days: 2,
            price_usd: 200,
            max_participants: 15,
            image_url: '/images/weekend-bash.jpg',
            destinations: [{ name: 'Watamu' }],
          },
          {
            id: 2,
            title: 'Explorer Weekend - 3 Days / 2 Nights',
            description: 'Dive deeper into the coast with our Explorer Weekend: includes safari or dhow, historic site visits, sand dune rides and canyon sunset. Ideal for adventurers and memory-makers.',
            duration_days: 3,
            price_usd: 300,
            max_participants: 15,
            image_url: '/images/explorer-weekend.jpg',
            destinations: [{ name: 'Watamu' }],
          },
        ])
      } finally {
        setLoading(false)
      }
    }

    fetchData()
  }, [])

  if (loading) {
    return (
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
              Choose Your Adventure
            </h2>
            <p className="text-nomadiq-black/70 text-lg">
              Tailor your experience by budget, region, and interests.
            </p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            {[1, 2].map((i) => (
              <div key={i} className="bg-nomadiq-sand/20 rounded-2xl h-96 animate-pulse"></div>
            ))}
          </div>
        </div>
      </section>
    )
  }

  return (
    <section className="py-20 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
            Choose Your Adventure
          </h2>
          <p className="text-nomadiq-black/70 text-lg">
            Tailor your experience by budget, region, and interests.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          {packages.map((pkg) => (
            <Link
              key={pkg.id}
              href={`/packages/${pkg.id}`}
              className="group relative overflow-hidden rounded-2xl bg-white border border-nomadiq-sand/30 hover:shadow-2xl transition-all duration-300"
            >
              {/* Image */}
              <div className="relative h-64 bg-gradient-to-br from-nomadiq-copper/30 to-nomadiq-teal/30 overflow-hidden">
                {pkg.image_url ? (
                  <img
                    src={pkg.image_url}
                    alt={pkg.title}
                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                    onError={(e) => {
                      // Fallback to gradient if image fails to load
                      e.currentTarget.style.display = 'none'
                    }}
                  />
                ) : null}
                <div className="absolute top-4 right-4 bg-nomadiq-black/80 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                  {pkg.duration_days} {pkg.duration_days === 1 ? 'day' : 'days'}
                </div>
              </div>

              {/* Content */}
              <div className="p-6">
                <h3 className="text-2xl font-serif font-bold mb-3 text-nomadiq-black group-hover:text-nomadiq-copper transition-colors">
                  {pkg.title}
                </h3>

                <div className="flex items-center space-x-4 mb-4 text-sm text-nomadiq-black/70">
                  <div className="flex items-center space-x-1">
                    <Star className="w-4 h-4 fill-yellow-400 text-yellow-400" />
                    <span className="font-semibold">4.9</span>
                  </div>
                  {pkg.destinations && pkg.destinations.length > 0 && (
                    <div className="flex items-center space-x-1">
                      <MapPin className="w-4 h-4" />
                      <span>{pkg.destinations[0].name}</span>
                    </div>
                  )}
                </div>

                {/* Highlights with emojis */}
                {pkg.highlights && pkg.highlights.length > 0 && (
                  <div className="mb-4 space-y-2">
                    {pkg.highlights.slice(0, 3).map((highlight, idx) => (
                      <div key={idx} className="flex items-start space-x-2 text-sm text-nomadiq-black/80">
                        <span className="text-lg">{highlight.emoji || '✨'}</span>
                        <span className="flex-1">{highlight.text}</span>
                      </div>
                    ))}
                    {pkg.highlights.length > 3 && (
                      <div className="text-xs text-nomadiq-black/60 italic">
                        +{pkg.highlights.length - 3} more highlights
                      </div>
                    )}
                  </div>
                )}

                <p className="text-nomadiq-black/70 mb-4 line-clamp-3">
                  {pkg.description}
                </p>

                <div className="flex items-center justify-between pt-4 border-t border-nomadiq-sand/30">
                  <div className="group/price">
                    <div className="text-2xl font-serif font-bold text-nomadiq-copper">
                      {formatKsh(usdToKsh(pkg.price_usd))}
                    </div>
                    <div className="text-xs text-nomadiq-black/50 hidden group-hover/price:block">
                      {formatUsd(pkg.price_usd)}
                    </div>
                    <span className="text-nomadiq-black/60 text-sm block mt-1">per person</span>
                  </div>
                  <span className="text-nomadiq-copper font-semibold group-hover:translate-x-2 transition-transform">
                    View Details →
                  </span>
                </div>
              </div>
            </Link>
          ))}
        </div>

        <div className="text-center mt-12">
          <Link
            href="/packages"
            className="inline-block px-8 py-3 border-2 border-nomadiq-black text-nomadiq-black hover:bg-nomadiq-black hover:text-white transition-all duration-200 font-semibold uppercase tracking-wide"
          >
            View All Experiences
          </Link>
        </div>
      </div>
    </section>
  )
}

