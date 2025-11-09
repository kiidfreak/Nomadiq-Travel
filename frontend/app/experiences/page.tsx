'use client'

import { useEffect, useState } from 'react'
import Link from 'next/link'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { microExperiencesApi, settingsApi } from '@/lib/api'
import { Clock, MapPin, ArrowLeft } from 'lucide-react'
import { fetchCurrencyRate, usdToKsh, formatKsh, formatUsd, setUsdToKshRate } from '@/lib/currency'

interface MicroExperience {
  id: number
  title: string
  emoji?: string
  category: string
  description: string
  price_usd?: number
  duration_hours?: number
  location?: string
  image_url?: string
}

export default function ExperiencesPage() {
  const [experiences, setExperiences] = useState<MicroExperience[]>([])
  const [loading, setLoading] = useState(true)
  const [currencyRate, setCurrencyRate] = useState(140)
  const [selectedCategory, setSelectedCategory] = useState<string | null>(null)

  useEffect(() => {
    const fetchData = async () => {
      try {
        // Fetch currency rate
        const rate = await fetchCurrencyRate()
        setCurrencyRate(rate)
        setUsdToKshRate(rate)

        // Fetch all micro experiences
        const response = await microExperiencesApi.getAll()
        if (response.data.success) {
          setExperiences(response.data.data)
        }
      } catch (error) {
        console.error('Error fetching data:', error)
      } finally {
        setLoading(false)
      }
    }

    fetchData()
  }, [])

  // Get unique categories
  const categories = Array.from(new Set(experiences.map(exp => exp.category)))

  // Filter experiences by category
  const filteredExperiences = selectedCategory
    ? experiences.filter(exp => exp.category === selectedCategory)
    : experiences

  if (loading) {
    return (
      <div className="min-h-screen bg-nomadiq-bone">
        <Header />
        <main className="pt-20">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              {[1, 2, 3, 4, 5, 6].map((i) => (
                <div key={i} className="bg-nomadiq-sand/20 rounded-2xl h-96 animate-pulse"></div>
              ))}
            </div>
          </div>
        </main>
        <Footer />
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-nomadiq-bone">
      <Header />
      <main className="pt-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          {/* Header */}
          <div className="mb-12">
            <Link
              href="/"
              className="inline-flex items-center gap-2 text-nomadiq-copper hover:text-nomadiq-teal transition-colors mb-6"
            >
              <ArrowLeft className="w-5 h-5" />
              <span>Back to Home</span>
            </Link>
            <h1 className="text-4xl md:text-5xl font-serif font-bold text-nomadiq-black mb-4">
              All Add-On Experiences
            </h1>
            <p className="text-lg text-nomadiq-black/70">
              Enhance your journey with these special experiences
            </p>
          </div>

          {/* Category Filters */}
          {categories.length > 0 && (
            <div className="mb-8 flex flex-wrap gap-3">
              <button
                onClick={() => setSelectedCategory(null)}
                className={`px-6 py-2 rounded-full font-semibold transition-all duration-300 ${
                  selectedCategory === null
                    ? 'bg-gradient-to-r from-nomadiq-copper to-nomadiq-orange text-white shadow-lg'
                    : 'bg-white text-nomadiq-black border border-nomadiq-sand/30 hover:border-nomadiq-copper'
                }`}
              >
                All
              </button>
              {categories.map((category) => (
                <button
                  key={category}
                  onClick={() => setSelectedCategory(category)}
                  className={`px-6 py-2 rounded-full font-semibold transition-all duration-300 ${
                    selectedCategory === category
                      ? 'bg-gradient-to-r from-nomadiq-copper to-nomadiq-orange text-white shadow-lg'
                      : 'bg-white text-nomadiq-black border border-nomadiq-sand/30 hover:border-nomadiq-copper'
                  }`}
                >
                  {category}
                </button>
              ))}
            </div>
          )}

          {/* Experiences Grid */}
          {filteredExperiences.length > 0 ? (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              {filteredExperiences.map((experience) => (
                <div
                  key={experience.id}
                  className="bg-white rounded-2xl overflow-hidden border border-nomadiq-sand/30 hover:shadow-xl transition-all duration-300 group"
                >
                  {/* Image */}
                  {experience.image_url && (
                    <div className="relative h-48 overflow-hidden">
                      <img
                        src={experience.image_url}
                        alt={experience.title}
                        className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                      />
                      <div className="absolute inset-0 bg-gradient-to-t from-nomadiq-black/60 to-transparent"></div>
                      {experience.emoji && (
                        <div className="absolute top-4 left-4 text-4xl">
                          {experience.emoji}
                        </div>
                      )}
                      <div className="absolute bottom-4 left-4 right-4">
                        <div className="inline-block px-3 py-1 bg-nomadiq-copper/90 text-white rounded-full text-xs font-semibold uppercase tracking-wide">
                          {experience.category}
                        </div>
                      </div>
                    </div>
                  )}

                  {/* Content */}
                  <div className="p-6">
                    <h3 className="text-xl font-serif font-bold text-nomadiq-black mb-3">
                      {experience.title}
                    </h3>
                    <p className="text-nomadiq-black/70 mb-4 line-clamp-3">
                      {experience.description}
                    </p>

                    {/* Details */}
                    <div className="flex flex-wrap items-center gap-4 text-sm text-nomadiq-black/60 mb-4">
                      {experience.location && (
                        <div className="flex items-center gap-1">
                          <MapPin className="w-4 h-4" />
                          <span>{experience.location}</span>
                        </div>
                      )}
                      {experience.duration_hours && (
                        <div className="flex items-center gap-1">
                          <Clock className="w-4 h-4" />
                          <span>{experience.duration_hours} {experience.duration_hours === 1 ? 'hour' : 'hours'}</span>
                        </div>
                      )}
                    </div>

                    {/* Price */}
                    {experience.price_usd && experience.price_usd > 0 && (
                      <div className="pt-4 border-t border-nomadiq-sand/30">
                        <div className="flex items-center justify-between">
                          <span className="text-sm text-nomadiq-black/60">Price</span>
                          <div className="text-right group/price">
                            <div className="font-bold text-nomadiq-copper text-lg">
                              {formatKsh(usdToKsh(experience.price_usd))}
                            </div>
                            <div className="text-xs text-nomadiq-black/50 hidden group-hover/price:block">
                              {formatUsd(experience.price_usd)} USD
                            </div>
                          </div>
                        </div>
                      </div>
                    )}
                    {(!experience.price_usd || experience.price_usd === 0) && (
                      <div className="pt-4 border-t border-nomadiq-sand/30">
                        <span className="text-sm font-semibold text-nomadiq-teal">Free</span>
                      </div>
                    )}
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <div className="text-center py-20">
              <p className="text-xl text-nomadiq-black/60">
                No experiences found in this category.
              </p>
            </div>
          )}
        </div>
      </main>
      <Footer />
    </div>
  )
}

