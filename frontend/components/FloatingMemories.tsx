'use client'

import { useEffect, useState } from 'react'
import { memoriesApi } from '@/lib/api'
import Link from 'next/link'

interface FloatingMemory {
  id: number
  title: string
  location: string
  date: string
  image_url: string
  slot: string
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
        // Fallback data
        setMemories([
          {
            id: 1,
            title: 'Floating Memories',
            location: 'Samburu National Park',
            date: 'Sep 2025',
            image_url: '/images/memory1.jpg',
            slot: 'morning',
          },
          {
            id: 2,
            title: 'Maasai Mara Adventure',
            location: 'Maasai Mara National Reserve',
            date: 'Aug 2025',
            image_url: '/images/memory2.jpg',
            slot: 'afternoon',
          },
          {
            id: 3,
            title: 'Coastal Escape',
            location: 'Watamu',
            date: 'Oct 2025',
            image_url: '/images/memory3.jpg',
            slot: 'evening',
          },
        ])
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

  return (
    <section className="py-20 bg-gradient-to-b from-nomadiq-bone to-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 className="text-3xl md:text-4xl font-serif font-bold text-center mb-12 text-nomadiq-black">
          Floating Memories
        </h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {memories.map((memory) => (
            <Link
              key={memory.id}
              href={`/memories/${memory.id}`}
              className="group relative overflow-hidden rounded-2xl aspect-[4/3] bg-gradient-to-br from-nomadiq-copper/20 to-nomadiq-teal/20 hover:shadow-2xl transition-all duration-300"
            >
              <div className="absolute inset-0 bg-gradient-to-t from-nomadiq-black/60 via-nomadiq-black/20 to-transparent z-10"></div>
              <div className="absolute bottom-0 left-0 right-0 p-6 z-20 text-white">
                <h3 className="text-xl font-serif font-bold mb-2">{memory.title}</h3>
                <p className="text-sm opacity-90">
                  {memory.location} · {memory.date}
                </p>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  )
}

