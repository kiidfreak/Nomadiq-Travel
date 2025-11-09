'use client'

import { useEffect, useState } from 'react'
import Link from 'next/link'
import { microExperiencesApi, settingsApi } from '@/lib/api'
import { ChevronLeft, ChevronRight, Clock, MapPin, Plus } from 'lucide-react'
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

export default function MicroExperiences() {
  const [experiences, setExperiences] = useState<MicroExperience[]>([])
  const [loading, setLoading] = useState(true)
  const [currentIndex, setCurrentIndex] = useState(0)
  const [currencyRate, setCurrencyRate] = useState(140)

  useEffect(() => {
    const fetchData = async () => {
      try {
        // Fetch currency rate
        const rate = await fetchCurrencyRate()
        setCurrencyRate(rate)
        setUsdToKshRate(rate)

        // Fetch micro experiences
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

  useEffect(() => {
    if (experiences.length > 0) {
      const interval = setInterval(() => {
        setCurrentIndex((prev) => (prev + 1) % experiences.length)
      }, 5000) // Auto-advance every 5 seconds

      return () => clearInterval(interval)
    }
  }, [experiences.length])

  const goToPrevious = () => {
    setCurrentIndex((prev) => (prev - 1 + experiences.length) % experiences.length)
  }

  const goToNext = () => {
    setCurrentIndex((prev) => (prev + 1) % experiences.length)
  }

  const goToSlide = (index: number) => {
    setCurrentIndex(index)
  }

  if (loading) {
    return (
      <section className="py-20 bg-gradient-to-b from-white to-nomadiq-bone">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4 text-nomadiq-black">
              Add-On Experiences
            </h2>
            <p className="text-nomadiq-black/70 text-lg">
              Enhance your journey with these special experiences
            </p>
          </div>
          <div className="bg-nomadiq-sand/20 rounded-2xl h-96 animate-pulse"></div>
        </div>
      </section>
    )
  }

  if (experiences.length === 0) {
    return null
  }

  const currentExperience = experiences[currentIndex]

  return (
    <section className="py-20 bg-gradient-to-b from-white to-nomadiq-bone">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div className="text-center mb-12">
        <Link
            href="/experiences"
            className="group relative inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-nomadiq-copper via-nomadiq-orange to-nomadiq-teal text-white rounded-full font-bold text-lg hover:shadow-2xl hover:scale-110 transition-all duration-300 hover:from-nomadiq-teal hover:via-nomadiq-orange hover:to-nomadiq-copper transform hover:-translate-y-1"
          >
            <Plus className="w-7 h-7 group-hover:rotate-90 transition-transform duration-300" />
          </Link>
          <br />
          <br />
          <br />
          <br />
          
          <h2 className="text-3xl md:text-4xl font-serif font-bold text-nomadiq-black mb-4">
            Add-On Experiences
          </h2>
          <p className="text-nomadiq-black/70 text-lg mb-6">
            Enhance your journey with these special experiences
          </p>

        </div>

        {/* Slideshow */}
        <div className="relative">
          <div className="relative h-[500px] md:h-[600px] rounded-2xl overflow-hidden bg-gradient-to-br from-nomadiq-copper/20 to-nomadiq-teal/20">
            {/* Background Image */}
            {currentExperience.image_url && (
              <img
                src={currentExperience.image_url}
                alt={currentExperience.title}
                className="absolute inset-0 w-full h-full object-cover opacity-30"
              />
            )}

            {/* Content Overlay */}
            <div className="relative z-10 h-full flex flex-col justify-center items-center text-center p-8 md:p-12">
              <div className="max-w-3xl">
                {/* Emoji */}
                {currentExperience.emoji && (
                  <div className="text-6xl md:text-7xl mb-6">
                    {currentExperience.emoji}
                  </div>
                )}

                {/* Category Badge */}
                <div className="inline-block px-4 py-2 bg-nomadiq-copper/90 text-white rounded-full text-sm font-semibold mb-4 uppercase tracking-wide">
                  {currentExperience.category}
                </div>

                {/* Title */}
                <h3 className="text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-nomadiq-black mb-6">
                  {currentExperience.title}
                </h3>

                {/* Description */}
                <p className="text-lg md:text-xl text-nomadiq-black/80 mb-8 leading-relaxed">
                  {currentExperience.description}
                </p>

                {/* Details */}
                <div className="flex flex-wrap items-center justify-center gap-6 text-nomadiq-black/70">
                  {currentExperience.location && (
                    <div className="flex items-center space-x-2">
                      <MapPin className="w-5 h-5" />
                      <span>{currentExperience.location}</span>
                    </div>
                  )}
                  {currentExperience.duration_hours && (
                    <div className="flex items-center space-x-2">
                      <Clock className="w-5 h-5" />
                      <span>{currentExperience.duration_hours} hours</span>
                    </div>
                  )}
                  {currentExperience.price_usd && currentExperience.price_usd > 0 && (
                    <div className="flex items-center space-x-2 group/price">
                      <span className="font-semibold text-nomadiq-copper text-lg">
                        {formatKsh(usdToKsh(currentExperience.price_usd))}
                      </span>
                      <span className="text-sm text-nomadiq-black/50 hidden group-hover/price:inline">
                        ({formatUsd(currentExperience.price_usd)})
                      </span>
                    </div>
                  )}
                </div>
              </div>
            </div>

            {/* Navigation Arrows */}
            {experiences.length > 1 && (
              <>
                <button
                  onClick={goToPrevious}
                  className="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-white/90 hover:bg-white text-nomadiq-black p-3 rounded-full shadow-lg transition-all duration-200"
                  aria-label="Previous experience"
                >
                  <ChevronLeft className="w-6 h-6" />
                </button>
                <button
                  onClick={goToNext}
                  className="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-white/90 hover:bg-white text-nomadiq-black p-3 rounded-full shadow-lg transition-all duration-200"
                  aria-label="Next experience"
                >
                  <ChevronRight className="w-6 h-6" />
                </button>
              </>
            )}

            {/* Dots Indicator */}
            {experiences.length > 1 && (
              <div className="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex space-x-2">
                {experiences.map((_, index) => (
                  <button
                    key={index}
                    onClick={() => goToSlide(index)}
                    className={`w-3 h-3 rounded-full transition-all duration-200 ${
                      index === currentIndex
                        ? 'bg-nomadiq-copper w-8'
                        : 'bg-white/50 hover:bg-white/75'
                    }`}
                    aria-label={`Go to slide ${index + 1}`}
                  />
                ))}
              </div>
            )}
          </div>
        </div>
      </div>
    </section>
  )
}

