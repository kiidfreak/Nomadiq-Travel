'use client'

import { useEffect, useState, useRef } from 'react'
import Link from 'next/link'
import { packagesApi, settingsApi } from '@/lib/api'
import { MapPin, Star, Calendar } from 'lucide-react'
import { fetchCurrencyRate, usdToKsh, formatKsh, formatUsd, setUsdToKshRate } from '@/lib/currency'
import { gsap } from 'gsap'

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
  const typewriterRef = useRef<HTMLSpanElement>(null)
  const cursorRef = useRef<HTMLSpanElement>(null)
  
  // Get current quarter and year dynamically
  const getCurrentQuarter = () => {
    const now = new Date()
    const year = now.getFullYear()
    const month = now.getMonth() + 1 // 0-indexed, so add 1
    
    // Determine current quarter
    let quarter: string
    if (month >= 1 && month <= 3) {
      quarter = 'first quarter'
    } else if (month >= 4 && month <= 6) {
      quarter = 'second quarter'
    } else if (month >= 7 && month <= 9) {
      quarter = 'third quarter'
    } else {
      quarter = 'last quarter'
    }
    
    return { quarter, year }
  }
  
  const { quarter, year } = getCurrentQuarter()
  const quarterText = `this ${quarter} of ${year}`

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
  
  // GSAP Typewriter effect
  useEffect(() => {
    if (typewriterRef.current && cursorRef.current && !loading) {
      const element = typewriterRef.current
      const cursor = cursorRef.current
      const text = quarterText
      
      // Clear the element and hide cursor initially
      element.textContent = ''
      gsap.set(cursor, { opacity: 0 })
      
      // Create timeline with typewriter effect
      const chars = text.split('')
      const tl = gsap.timeline({ repeat: 0, delay: 0.5 })
      
      chars.forEach((char) => {
        tl.to({}, {
          duration: 0.08,
          onComplete: () => {
            if (element) {
              element.textContent += char
            }
          }
        })
      })
      
      // Show cursor after typing is complete
      tl.to(cursor, {
        opacity: 1,
        duration: 0.3,
        onComplete: () => {
          // Optional: Remove cursor after a delay or keep it blinking
        }
      })
      
      // Cleanup function
      return () => {
        tl.kill()
      }
    }
  }, [loading, quarterText])

  if (loading) {
    return (
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-3xl md:text-4xl font-serif font-bold mb-2 text-nomadiq-black">
              Choose Your Adventure
            </h2>
            <div className="mb-4 min-h-[28px]">
              <p className="text-nomadiq-black/70 text-lg">
                <span className="text-nomadiq-copper font-semibold italic">
                  {quarterText}
                </span>
              </p>
            </div>
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
          <h2 className="text-3xl md:text-4xl font-serif font-bold mb-2 text-nomadiq-black">
            Choose Your Adventure
          </h2>
          <div className="mb-4 min-h-[28px]">
            <p className="text-nomadiq-black/70 text-lg">
              <span
                ref={typewriterRef}
                className="text-nomadiq-copper font-semibold italic"
              ></span>
              <span 
                ref={cursorRef}
                className="inline-block w-0.5 h-5 bg-nomadiq-copper ml-1 animate-pulse"
              ></span>
            </p>
          </div>
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
          <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
            <Link
              href="/packages"
              className="inline-block px-8 py-3 border-2 border-nomadiq-black text-nomadiq-black hover:bg-nomadiq-black hover:text-white transition-all duration-200 font-semibold uppercase tracking-wide"
            >
              View All Packages
            </Link>
            <span className="text-nomadiq-black/60 font-medium hidden sm:inline">or</span>
            <Link
              href="/proposal"
              className="group relative inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-nomadiq-copper via-nomadiq-orange to-nomadiq-teal text-white rounded-full font-bold hover:shadow-xl hover:scale-105 transition-all duration-300 hover:from-nomadiq-teal hover:via-nomadiq-orange hover:to-nomadiq-copper transform hover:-translate-y-1"
            >
              <span className="font-semibold">Make Your Own</span>
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
  )
}

