'use client'

import { useState } from 'react'
import Link from 'next/link'
import { Menu, X } from 'lucide-react'

export default function Header() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false)

  const navItems = [
    { name: 'Home', href: '/' },
    { name: 'About Us', href: '/about' },
    { name: 'Experiences', href: '/packages' },
    { name: 'Blog', href: '/blog' },
    { name: 'Contact', href: '/contact' },
  ]

  return (
    <header className="fixed top-0 left-0 right-0 z-50 bg-nomadiq-bone/95 backdrop-blur-sm border-b border-nomadiq-sand/20">
      <nav className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          {/* Logo */}
          <Link href="/" className="flex items-center space-x-3 group">
            <div className="w-12 h-12 rounded-full bg-gradient-to-br from-nomadiq-copper to-nomadiq-orange flex items-center justify-center">
              <span className="text-white font-serif text-xl font-bold">N</span>
            </div>
            <span className="text-2xl font-serif text-nomadiq-black font-bold tracking-tight">
              Nomadiq
            </span>
          </Link>

          {/* Desktop Navigation */}
          <div className="hidden md:flex items-center space-x-8">
            {navItems.map((item) => (
              <Link
                key={item.name}
                href={item.href}
                className="text-nomadiq-black hover:text-nomadiq-copper transition-colors duration-200 font-medium text-sm uppercase tracking-wide"
              >
                {item.name}
              </Link>
            ))}
            <Link
              href="/proposal"
              className="px-6 py-2.5 border-2 border-nomadiq-black text-nomadiq-black hover:bg-nomadiq-black hover:text-white transition-all duration-200 font-medium text-sm uppercase tracking-wide"
            >
              Make a Proposal
            </Link>
          </div>

          {/* Mobile menu button */}
          <button
            className="md:hidden p-2 text-nomadiq-black"
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
          >
            {mobileMenuOpen ? <X size={24} /> : <Menu size={24} />}
          </button>
        </div>

        {/* Mobile Navigation */}
        {mobileMenuOpen && (
          <div className="md:hidden py-4 space-y-4 border-t border-nomadiq-sand/20">
            {navItems.map((item) => (
              <Link
                key={item.name}
                href={item.href}
                className="block py-2 text-nomadiq-black hover:text-nomadiq-copper transition-colors font-medium"
                onClick={() => setMobileMenuOpen(false)}
              >
                {item.name}
              </Link>
            ))}
            <Link
              href="/proposal"
              className="block mt-4 px-6 py-2.5 border-2 border-nomadiq-black text-nomadiq-black hover:bg-nomadiq-black hover:text-white transition-all text-center font-medium"
              onClick={() => setMobileMenuOpen(false)}
            >
              Make a Proposal
            </Link>
          </div>
        )}
      </nav>
    </header>
  )
}

