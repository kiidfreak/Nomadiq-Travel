import Link from 'next/link'

export default function Footer() {
  const currentYear = new Date().getFullYear()

  return (
    <footer className="bg-nomadiq-black text-white py-16">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
          {/* Brand */}
          <div>
            <div className="flex items-center space-x-3 mb-4">
              <div className="w-10 h-10 rounded-full bg-gradient-to-br from-nomadiq-copper to-nomadiq-orange flex items-center justify-center">
                <span className="text-white font-serif text-lg font-bold">N</span>
              </div>
              <span className="text-2xl font-serif font-bold">Nomadiq</span>
            </div>
            <p className="text-white/70 mb-4">
              Stepping into Adventure.
            </p>
            <p className="text-white/60 text-sm">
              Premium, ethical experiences across Kenya. We craft unforgettable journeys that respect wildlife, empower communities, and leave you in awe.
            </p>
          </div>

          {/* Explore */}
          <div>
            <h3 className="font-serif font-bold text-lg mb-4 uppercase tracking-wide">Explore</h3>
            <ul className="space-y-2 text-white/70">
              <li><Link href="/packages" className="hover:text-nomadiq-copper transition-colors">Experiences</Link></li>
              <li><Link href="/destinations" className="hover:text-nomadiq-copper transition-colors">Destinations</Link></li>
              <li><Link href="/blog" className="hover:text-nomadiq-copper transition-colors">Blog</Link></li>
              <li><Link href="/conservation" className="hover:text-nomadiq-copper transition-colors">Conservation</Link></li>
            </ul>
          </div>

          {/* Company */}
          <div>
            <h3 className="font-serif font-bold text-lg mb-4 uppercase tracking-wide">Company</h3>
            <ul className="space-y-2 text-white/70">
              <li><Link href="/about" className="hover:text-nomadiq-copper transition-colors">About Us</Link></li>
              <li><Link href="/guides" className="hover:text-nomadiq-copper transition-colors">Our Guides</Link></li>
              <li><Link href="/careers" className="hover:text-nomadiq-copper transition-colors">Careers</Link></li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h3 className="font-serif font-bold text-lg mb-4 uppercase tracking-wide">Contact</h3>
            <ul className="space-y-2 text-white/70">
              <li>info@nomadiq.com</li>
              <li>+254 700 757 129</li>
              <li className="pt-2">Nairobi, Kenya</li>
            </ul>
          </div>
        </div>

        {/* Bottom */}
        <div className="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center">
          <p className="text-white/60 text-sm mb-4 md:mb-0">
            © {currentYear} Nomadiq. All rights reserved.
          </p>
          <div className="flex space-x-6 text-sm text-white/60">
            <Link href="/privacy" className="hover:text-nomadiq-copper transition-colors">Privacy</Link>
            <Link href="/terms" className="hover:text-nomadiq-copper transition-colors">Terms</Link>
            <Link href="/sustainability" className="hover:text-nomadiq-copper transition-colors">Sustainability</Link>
          </div>
        </div>
      </div>
    </footer>
  )
}

