'use client'

import { useEffect, useState } from 'react'
import { testimonialsApi } from '@/lib/api'
import { Star, Quote } from 'lucide-react'

interface Testimonial {
  id: number
  customer_name: string
  package_name: string
  rating: number
  comment: string
}

export default function Testimonials() {
  const [testimonials, setTestimonials] = useState<Testimonial[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const fetchTestimonials = async () => {
      try {
        const response = await testimonialsApi.getFeatured()
        if (response.data.success) {
          setTestimonials(response.data.data)
        }
      } catch (error) {
        console.error('Error fetching testimonials:', error)
        // Fallback data
        setTestimonials([
          {
            id: 1,
            customer_name: 'Chris Neville',
            package_name: 'Weekend Bash - 2 Nights / 1 Day',
            rating: 5,
            comment: 'I really enjoyed the planned out Activities and meals for the 2 days of our stay.',
          },
        ])
      } finally {
        setLoading(false)
      }
    }

    fetchTestimonials()
  }, [])

  if (loading) {
    return null
  }

  return (
    <section className="py-20 bg-gradient-to-b from-white to-nomadiq-bone">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 className="text-3xl md:text-4xl font-serif font-bold text-center mb-12 text-nomadiq-black">
          Hear From Our Happy Travelers
        </h2>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {testimonials.map((testimonial) => (
            <div
              key={testimonial.id}
              className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 hover:shadow-xl transition-shadow duration-300 relative"
            >
              <Quote className="absolute top-6 right-6 w-12 h-12 text-nomadiq-copper/20" />
              
              <div className="flex items-center space-x-1 mb-4">
                {[...Array(5)].map((_, i) => (
                  <Star
                    key={i}
                    className={`w-5 h-5 ${
                      i < testimonial.rating
                        ? 'fill-yellow-400 text-yellow-400'
                        : 'text-gray-300'
                    }`}
                  />
                ))}
              </div>

              <p className="text-nomadiq-black/80 mb-6 italic leading-relaxed">
                "{testimonial.comment}"
              </p>

              <div className="border-t border-nomadiq-sand/30 pt-4">
                <p className="font-semibold text-nomadiq-black">{testimonial.customer_name}</p>
                <p className="text-sm text-nomadiq-black/60">{testimonial.package_name}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}

