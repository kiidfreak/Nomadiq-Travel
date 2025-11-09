'use client'

import Link from 'next/link'
import { ArrowDown } from 'lucide-react'

export default function Hero() {
  const scrollToNext = () => {
    const statsSection = document.getElementById('stats-section')
    if (statsSection) {
      statsSection.scrollIntoView({ behavior: 'smooth' })
    }
  }

  return (
    <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
      {/* Background Image - Centered behind text */}
      <div className="absolute inset-0 flex items-center justify-center">
        <img
          src="/images/colorshero.png"
          alt="Nomadiq Coastal Adventure"
          className="w-full h-full object-cover"
          style={{
            objectPosition: 'center 5%',
          }}
          onError={(e) => {
            // Fallback to gradient if image doesn't exist
            e.currentTarget.style.display = 'none'
          }}
        />
        {/* Gradient Overlay for text readability */}
        <div className="absolute inset-0 bg-gradient-to-b from-nomadiq-bone/60 via-nomadiq-bone/40 to-nomadiq-sky/30"></div>
        {/* Additional subtle overlay for better contrast */}
        <div className="absolute inset-0 bg-gradient-to-b from-white/20 via-transparent to-nomadiq-sand/20"></div>
      </div>

      {/* Background decorative elements - subtle brushstroke effect */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute top-20 left-10 w-96 h-96 bg-nomadiq-sky/20 rounded-full blur-3xl opacity-50"></div>
        <div className="absolute bottom-20 right-10 w-[500px] h-[500px] bg-nomadiq-copper/15 rounded-full blur-3xl opacity-50"></div>
        {/* Abstract brushstroke-like shapes */}
        <div className="absolute top-1/4 left-1/4 w-[600px] h-[400px] bg-nomadiq-teal/10 rounded-[60%] blur-2xl rotate-12 opacity-40"></div>
        <div className="absolute bottom-1/4 right-1/4 w-[500px] h-[600px] bg-nomadiq-orange/10 rounded-[50%] blur-2xl -rotate-12 opacity-40"></div>
      </div>

      <div className="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center text-center">
        <h1 className="text-5xl md:text-7xl lg:text-8xl font-serif font-bold mb-6 leading-tight">
          <span className="text-nomadiq-black">Welcome to</span>
          <br />
          <span className="text-nomadiq-copper">Nomadiq</span>
        </h1>
        
        <p className="text-lg md:text-xl lg:text-2xl text-nomadiq-black/80 max-w-3xl mx-auto mb-10 leading-relaxed">
          Experience unforgettable *coastal adventures* with expert guides through Kenya's most spectacular beaches, mangroves, and hidden gems.
        </p>

        <Link
          href="/packages"
          className="inline-block px-8 py-4 bg-gradient-to-r from-nomadiq-copper to-nomadiq-orange text-white font-semibold rounded-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-lg"
        >
          Start Your Adventure
        </Link>
      </div>

      {/* Scroll indicator - positioned at bottom of section */}
      <div className="absolute bottom-8 left-0 right-0 flex justify-center z-20">
        <button
          onClick={scrollToNext}
          className="animate-bounce cursor-pointer hover:opacity-80 transition-opacity"
          aria-label="Scroll to explore"
        >
          <div className="flex flex-col items-center space-y-2 text-nomadiq-black/60">
            <span className="text-sm uppercase tracking-wider">Scroll to explore</span>
            <ArrowDown size={20} />
          </div>
        </button>
      </div>
    </section>
  )
}

