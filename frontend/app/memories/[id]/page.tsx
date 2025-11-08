'use client'

import { useEffect, useState } from 'react'
import { useParams, useRouter } from 'next/navigation'
import { memoriesApi } from '@/lib/api'
import Link from 'next/link'
import { ArrowLeft, Calendar, MapPin, Package, Heart } from 'lucide-react'

interface FloatingMemory {
  id: number
  image_url: string
  full_image_url?: string
  caption?: string
  safari_date?: string
  destination?: {
    id: number
    name: string
  }
  package?: {
    id: number
    title: string
  }
  slot?: number
  memory_title?: string
  memory_age?: string
}

export default function MemoryDetailPage() {
  const params = useParams()
  const router = useRouter()
  const [memory, setMemory] = useState<FloatingMemory | null>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)

  useEffect(() => {
    const fetchMemory = async () => {
      try {
        setLoading(true)
        const response = await memoriesApi.getById(params.id as string)
        if (response.data.success) {
          setMemory(response.data.data)
        } else {
          setError('Memory not found')
        }
      } catch (err: any) {
        console.error('Error fetching memory:', err)
        setError(err.response?.status === 404 ? 'Memory not found' : 'Failed to load memory')
      } finally {
        setLoading(false)
      }
    }

    if (params.id) {
      fetchMemory()
    }
  }, [params.id])

  const getImageUrl = (imageUrl: string) => {
    if (!imageUrl) return '/images/logo.jpg'
    
    // If it's already a full URL, use it
    if (imageUrl.startsWith('http://') || imageUrl.startsWith('https://')) {
      // Replace localhost with nevcompany2.test for storage URLs
      if (imageUrl.includes('localhost') && imageUrl.includes('/storage/')) {
        return imageUrl.replace('http://localhost', 'https://nevcompany2.test')
      }
      return imageUrl
    }
    
    // If it's a relative path starting with /storage, prepend the API URL domain
    if (imageUrl.startsWith('/storage/') || imageUrl.startsWith('storage/')) {
      const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'https://nevcompany2.test/api'
      const baseUrl = apiUrl.replace('/api', '')
      return `${baseUrl}/${imageUrl.startsWith('/') ? imageUrl.substring(1) : imageUrl}`
    }
    
    return imageUrl
  }

  const formatDate = (dateString?: string) => {
    if (!dateString) return 'Date unknown'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { 
      weekday: 'long',
      year: 'numeric', 
      month: 'long', 
      day: 'numeric' 
    })
  }

  if (loading) {
    return (
      <div className="min-h-screen bg-gradient-to-b from-nomadiq-bone to-white flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-nomadiq-copper mx-auto mb-4"></div>
          <p className="text-nomadiq-black text-lg">Loading beautiful memory...</p>
        </div>
      </div>
    )
  }

  if (error || !memory) {
    return (
      <div className="min-h-screen bg-gradient-to-b from-nomadiq-bone to-white flex items-center justify-center">
        <div className="text-center max-w-md mx-auto px-4">
          <Heart className="w-16 h-16 text-nomadiq-copper mx-auto mb-4" />
          <h1 className="text-3xl font-serif font-bold text-nomadiq-black mb-4">
            Memory Not Found
          </h1>
          <p className="text-nomadiq-black/70 mb-8">{error || 'This memory could not be found.'}</p>
          <Link
            href="/"
            className="inline-flex items-center gap-2 px-6 py-3 bg-nomadiq-copper text-white rounded-lg hover:bg-nomadiq-copper/90 transition-colors"
          >
            <ArrowLeft className="w-4 h-4" />
            Back to Home
          </Link>
        </div>
      </div>
    )
  }

  const title = memory.memory_title || memory.destination?.name || 'Safari Memory'

  return (
    <div className="min-h-screen bg-gradient-to-b from-nomadiq-bone to-white">
      {/* Hero Image Section */}
      <div className="relative w-full bg-nomadiq-bone">
        {(memory.full_image_url || memory.image_url) && (
          <div className="relative w-full flex items-center justify-center">
            <img
              src={getImageUrl(memory.full_image_url || memory.image_url)}
              alt={memory.caption || title}
              className="w-full h-auto block max-h-[90vh]"
              loading="eager"
              style={{ display: 'block', maxWidth: '100%' }}
            />
          </div>
        )}
        <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-nomadiq-black/80 to-transparent pointer-events-none z-10"></div>
        
        {/* Back Button */}
        <div className="absolute top-4 left-4 z-20">
          <Link
            href="/"
            className="inline-flex items-center gap-2 px-4 py-2 bg-white/90 backdrop-blur-sm text-nomadiq-black rounded-lg hover:bg-white transition-colors"
          >
            <ArrowLeft className="w-4 h-4" />
            Back
          </Link>
        </div>

        {/* Title Overlay */}
        <div className="absolute bottom-0 left-0 right-0 p-8 md:p-12 z-20">
          <h1 className="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-4">
            {title}
          </h1>
          <div className="flex flex-wrap items-center gap-4 text-white/90">
            {memory.destination && (
              <div className="flex items-center gap-2">
                <MapPin className="w-5 h-5" />
                <span className="text-lg">{memory.destination.name}</span>
              </div>
            )}
            {memory.safari_date && (
              <div className="flex items-center gap-2">
                <Calendar className="w-5 h-5" />
                <span className="text-lg">{formatDate(memory.safari_date)}</span>
              </div>
            )}
            {memory.memory_age && (
              <div className="flex items-center gap-2">
                <Heart className="w-5 h-5" />
                <span className="text-lg">{memory.memory_age}</span>
              </div>
            )}
          </div>
        </div>
      </div>

      {/* Content Section */}
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        {memory.caption && (
          <div className="bg-white rounded-2xl p-8 md:p-12 shadow-lg mb-8">
            <p className="text-xl md:text-2xl text-nomadiq-black leading-relaxed font-serif">
              {memory.caption}
            </p>
          </div>
        )}

        {/* Details Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
          {memory.destination && (
            <div className="bg-white rounded-xl p-6 shadow-md">
              <div className="flex items-center gap-3 mb-2">
                <MapPin className="w-6 h-6 text-nomadiq-copper" />
                <h3 className="text-lg font-semibold text-nomadiq-black">Destination</h3>
              </div>
              <p className="text-nomadiq-black/70">{memory.destination.name}</p>
            </div>
          )}

          {memory.package && (
            <div className="bg-white rounded-xl p-6 shadow-md">
              <div className="flex items-center gap-3 mb-2">
                <Package className="w-6 h-6 text-nomadiq-teal" />
                <h3 className="text-lg font-semibold text-nomadiq-black">Package</h3>
              </div>
              <p className="text-nomadiq-black/70">{memory.package.title}</p>
              {memory.package.id && (
                <Link
                  href={`/packages/${memory.package.id}`}
                  className="mt-3 inline-block text-nomadiq-copper hover:underline text-sm"
                >
                  View Package →
                </Link>
              )}
            </div>
          )}

          {memory.safari_date && (
            <div className="bg-white rounded-xl p-6 shadow-md">
              <div className="flex items-center gap-3 mb-2">
                <Calendar className="w-6 h-6 text-nomadiq-copper" />
                <h3 className="text-lg font-semibold text-nomadiq-black">Safari Date</h3>
              </div>
              <p className="text-nomadiq-black/70">{formatDate(memory.safari_date)}</p>
            </div>
          )}

          {memory.memory_age && (
            <div className="bg-white rounded-xl p-6 shadow-md">
              <div className="flex items-center gap-3 mb-2">
                <Heart className="w-6 h-6 text-nomadiq-teal" />
                <h3 className="text-lg font-semibold text-nomadiq-black">Memory Age</h3>
              </div>
              <p className="text-nomadiq-black/70">{memory.memory_age}</p>
            </div>
          )}
        </div>

        {/* Call to Action */}
        <div className="bg-gradient-to-r from-nomadiq-copper to-nomadiq-teal rounded-2xl p-8 md:p-12 text-center text-white">
          <h2 className="text-3xl md:text-4xl font-serif font-bold mb-4">
            Create Your Own Memory
          </h2>
          <p className="text-lg mb-6 opacity-90">
            Ready to embark on your own unforgettable adventure?
          </p>
          <Link
            href="/packages"
            className="inline-block px-8 py-4 bg-white text-nomadiq-copper rounded-lg font-semibold hover:bg-nomadiq-bone transition-colors"
          >
            Explore Packages
          </Link>
        </div>
      </div>
    </div>
  )
}

