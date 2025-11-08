'use client'

import { useEffect, useState } from 'react'
import { memoriesApi } from '@/lib/api'
import Link from 'next/link'

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

export default function FloatingMemories() {
  const [memories, setMemories] = useState<FloatingMemory[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    const fetchMemories = async () => {
      try {
        const response = await memoriesApi.getLatest(3)
        if (response.data.success) {
          setMemories(response.data.data)
        }
      } catch (error) {
        console.error('Error fetching memories:', error)
      } finally {
        setLoading(false)
      }
    }

    fetchMemories()
  }, [])

  if (loading) {
    return (
      <section className="py-20 bg-gradient-to-b from-nomadiq-bone to-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-3xl md:text-4xl font-serif font-bold text-center mb-12 text-nomadiq-black">
            Floating Memories
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[1, 2, 3].map((i) => (
              <div key={i} className="bg-nomadiq-sand/20 rounded-2xl h-64 animate-pulse"></div>
            ))}
          </div>
        </div>
      </section>
    )
  }

  if (memories.length === 0) {
    return null
  }

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
    if (!dateString) return ''
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' })
  }


  return (
    <section className="py-20 bg-gradient-to-b from-nomadiq-bone to-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 className="text-3xl md:text-4xl font-serif font-bold text-center mb-12 text-nomadiq-black">
          Floating Memories
        </h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {memories.map((memory) => {
            const title = memory.memory_title || memory.destination?.name || 'Safari Memory'
            const location = memory.destination?.name || memory.package?.title || ''
            const date = formatDate(memory.safari_date)
            
            return (
              <Link
                key={memory.id}
                href={`/memories/${memory.id}`}
                className="group relative rounded-2xl bg-gradient-to-br from-nomadiq-copper/20 to-nomadiq-teal/20 hover:shadow-2xl transition-all duration-300 cursor-pointer block overflow-hidden"
              >
                {(memory.full_image_url || memory.image_url) && (
                  <div className="relative w-full">
                    <img
                      src={getImageUrl(memory.full_image_url || memory.image_url)}
                      alt={memory.caption || title}
                      className="w-full h-auto block"
                      loading="lazy"
                      style={{ display: 'block', maxWidth: '100%' }}
                    />
                  </div>
                )}
                <div className="absolute inset-0 bg-gradient-to-t from-nomadiq-black/80 via-transparent to-transparent z-10 pointer-events-none"></div>
                <div className="absolute bottom-0 left-0 right-0 p-6 z-20 text-white pointer-events-none">
                  <h3 className="text-xl font-serif font-bold mb-2 group-hover:text-nomadiq-copper transition-colors">
                    {title}
                  </h3>
                  <p className="text-sm opacity-90">
                    {location && date ? `${location} · ${date}` : location || date}
                  </p>
                </div>
              </Link>
            )
          })}
        </div>
      </div>
    </section>
  )
}

