'use client'

import { useEffect, useState } from 'react'
import Link from 'next/link'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { packagesApi, settingsApi } from '@/lib/api'
import { MapPin, Star, Filter, X } from 'lucide-react'
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

export default function PackagesPage() {
  const [packages, setPackages] = useState<Package[]>([])
  const [filteredPackages, setFilteredPackages] = useState<Package[]>([])
  const [loading, setLoading] = useState(true)
  const [currencyRate, setCurrencyRate] = useState(140)
  const [filters, setFilters] = useState({
    budget: 'all',
    region: 'all',
    interest: 'all',
  })

  useEffect(() => {
    const fetchData = async () => {
      try {
        // Fetch currency rate
        const rate = await fetchCurrencyRate()
        setCurrencyRate(rate)
        setUsdToKshRate(rate)

        // Fetch packages
        const response = await packagesApi.getAll()
        if (response.data.success) {
          const fetchedPackages = response.data.data
          // Add fallback packages if none exist
          if (fetchedPackages.length === 0) {
            fetchedPackages.push(
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
              }
            )
          }
          setPackages(fetchedPackages)
          setFilteredPackages(fetchedPackages)
        }
      } catch (error) {
        console.error('Error fetching packages:', error)
        // Fallback data
        const fallbackPackages = [
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
        ]
        setPackages(fallbackPackages)
        setFilteredPackages(fallbackPackages)
      } finally {
        setLoading(false)
      }
    }

    fetchData()
  }, [])

  useEffect(() => {
    let filtered = [...packages]

    // Budget filter
    if (filters.budget !== 'all') {
      if (filters.budget === 'budget') {
        filtered = filtered.filter((pkg) => pkg.price_usd >= 150 && pkg.price_usd <= 300)
      } else if (filters.budget === 'mid') {
        filtered = filtered.filter((pkg) => pkg.price_usd > 300 && pkg.price_usd <= 500)
      } else if (filters.budget === 'luxury') {
        filtered = filtered.filter((pkg) => pkg.price_usd > 500)
      }
    }

    // Region filter (simplified - would need proper region mapping)
    // Interest filter (simplified - would need proper interest mapping)

    setFilteredPackages(filtered)
  }, [filters, packages])

  const clearFilters = () => {
    setFilters({ budget: 'all', region: 'all', interest: 'all' })
  }

  return (
    <div className="min-h-screen bg-nomadiq-bone">
      <Header />
      <main className="pt-20">
        {/* Hero Section */}
        <section className="bg-gradient-to-br from-nomadiq-copper/10 via-nomadiq-bone to-nomadiq-sky/10 py-16">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 className="text-4xl md:text-5xl font-serif font-bold text-center mb-4 text-nomadiq-black">
              Choose Your Adventure
            </h1>
            <p className="text-center text-nomadiq-black/70 text-lg max-w-2xl mx-auto">
              Tailor your experience by budget, region, and interests.
            </p>
          </div>
        </section>

        {/* Filter Section */}
        <section className="bg-white py-8 border-b border-nomadiq-sand/30">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="bg-nomadiq-sand/20 rounded-2xl p-6">
              <div className="flex items-center justify-between mb-6">
                <div className="flex items-center space-x-2">
                  <Filter className="w-5 h-5 text-nomadiq-copper" />
                  <h2 className="text-xl font-serif font-bold text-nomadiq-black">
                    Filter your perfect experience
                  </h2>
                </div>
                <span className="text-nomadiq-black/70 font-medium">
                  {filteredPackages.length} {filteredPackages.length === 1 ? 'result' : 'results'}
                </span>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {/* Budget Filter */}
                <div>
                  <label className="block text-sm font-semibold text-nomadiq-black mb-3 uppercase tracking-wide">
                    Budget
                  </label>
                  <div className="flex flex-wrap gap-2">
                    {['all', 'budget', 'mid', 'luxury'].map((option) => (
                      <button
                        key={option}
                        onClick={() => setFilters({ ...filters, budget: option })}
                        className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                          filters.budget === option
                            ? 'bg-nomadiq-copper text-white'
                            : 'bg-white text-nomadiq-black hover:bg-nomadiq-sand/30'
                        }`}
                      >
                        {option === 'all'
                          ? 'All'
                          : option === 'budget'
                          ? 'Budget $150-$300'
                          : option === 'mid'
                          ? 'Mid $300-$500'
                          : 'Luxury $500+'}
                      </button>
                    ))}
                  </div>
                </div>

                {/* Region Filter */}
                <div>
                  <label className="block text-sm font-semibold text-nomadiq-black mb-3 uppercase tracking-wide">
                    Region
                  </label>
                  <div className="flex flex-wrap gap-2">
                    {['all', 'central', 'southern', 'coastal'].map((option) => (
                      <button
                        key={option}
                        onClick={() => setFilters({ ...filters, region: option })}
                        className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors capitalize ${
                          filters.region === option
                            ? 'bg-nomadiq-copper text-white'
                            : 'bg-white text-nomadiq-black hover:bg-nomadiq-sand/30'
                        }`}
                      >
                        {option === 'all' ? 'All' : option}
                      </button>
                    ))}
                  </div>
                </div>

                {/* Interest Filter */}
                <div>
                  <label className="block text-sm font-semibold text-nomadiq-black mb-3 uppercase tracking-wide">
                    Interest
                  </label>
                  <div className="flex flex-wrap gap-2">
                    {['all', 'wildlife', 'adventure', 'culture'].map((option) => (
                      <button
                        key={option}
                        onClick={() => setFilters({ ...filters, interest: option })}
                        className={`px-4 py-2 rounded-lg text-sm font-medium transition-colors capitalize ${
                          filters.interest === option
                            ? 'bg-nomadiq-copper text-white'
                            : 'bg-white text-nomadiq-black hover:bg-nomadiq-sand/30'
                        }`}
                      >
                        {option === 'all' ? 'All' : option}
                      </button>
                    ))}
                  </div>
                </div>
              </div>

              {(filters.budget !== 'all' || filters.region !== 'all' || filters.interest !== 'all') && (
                <div className="mt-6 pt-6 border-t border-nomadiq-sand/30">
                  <button
                    onClick={clearFilters}
                    className="text-nomadiq-copper hover:text-nomadiq-copper/80 font-medium text-sm flex items-center space-x-1"
                  >
                    <X className="w-4 h-4" />
                    <span>Clear all filters</span>
                  </button>
                </div>
              )}
            </div>
          </div>
        </section>

        {/* Packages Grid */}
        <section className="py-16 bg-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {loading ? (
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {[1, 2].map((i) => (
                  <div key={i} className="bg-nomadiq-sand/20 rounded-2xl h-96 animate-pulse"></div>
                ))}
              </div>
            ) : filteredPackages.length === 0 ? (
              <div className="text-center py-16">
                <p className="text-nomadiq-black/70 text-lg mb-4">No packages found matching your filters.</p>
                <button
                  onClick={clearFilters}
                  className="text-nomadiq-copper hover:text-nomadiq-copper/80 font-medium"
                >
                  Clear filters
                </button>
              </div>
            ) : (
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {filteredPackages.map((pkg) => (
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
            )}
            
            {/* Make Your Own Package CTA */}
            <div className="mt-12 text-center">
              <div className="bg-gradient-to-r from-nomadiq-copper/10 via-nomadiq-orange/10 to-nomadiq-teal/10 rounded-2xl p-8 border border-nomadiq-sand/30">
                <h3 className="text-2xl md:text-3xl font-serif font-bold text-nomadiq-black mb-3">
                  Can't find what you're looking for?
                </h3>
                <p className="text-nomadiq-black/70 mb-6 max-w-2xl mx-auto">
                  Create your own custom package tailored to your preferences, budget, and interests. Our team will work with you to design the perfect adventure.
                </p>
                <Link
                  href="/proposal"
                  className="group relative inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-nomadiq-copper via-nomadiq-orange to-nomadiq-teal text-white rounded-full font-bold text-lg hover:shadow-xl hover:scale-105 transition-all duration-300 hover:from-nomadiq-teal hover:via-nomadiq-orange hover:to-nomadiq-copper transform hover:-translate-y-1"
                >
                  <span className="font-semibold">Make Your Own Package</span>
                  <span className="flex items-center">
                    <span className="animate-typing-wave inline-block">~</span>
                    <span className="animate-typing-wave-delayed inline-block">~</span>
                    <span className="animate-typing-wave inline-block" style={{ animationDelay: '1s' }}>~</span>
                  </span>
                </Link>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </div>
  )
}

