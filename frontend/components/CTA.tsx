import Link from 'next/link'

export default function CTA() {
  return (
    <section className="py-20 bg-gradient-to-br from-nomadiq-copper via-nomadiq-orange to-nomadiq-teal text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 className="text-4xl md:text-5xl font-serif font-bold mb-6">
          Ready when the wild calls.
        </h2>
        <p className="text-xl mb-8 text-white/90 max-w-2xl mx-auto">
          Hand-picked experiences, expert guides, and cinematic landscapes. Let's craft your custom route.
        </p>

        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 max-w-4xl mx-auto">
          <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
            <div className="text-3xl font-serif font-bold mb-2">4.9/5</div>
            <div className="text-sm text-white/80">from 500+ travelers</div>
          </div>
          <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
            <div className="text-3xl font-serif font-bold mb-2">Local</div>
            <div className="text-sm text-white/80">Expert guides</div>
          </div>
          <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
            <div className="text-3xl font-serif font-bold mb-2">10%</div>
            <div className="text-sm text-white/80">to conservation</div>
          </div>
          <div className="bg-white/10 backdrop-blur-sm rounded-lg p-6">
            <div className="text-3xl font-serif font-bold mb-2">2025</div>
            <div className="text-sm text-white/80">Limited slots</div>
          </div>
        </div>

        <Link
          href="/proposal"
          className="inline-block px-8 py-4 bg-white text-nomadiq-copper font-semibold rounded-lg hover:shadow-xl hover:scale-105 transition-all duration-300 text-lg"
        >
          Make a Proposal
        </Link>
      </div>
    </section>
  )
}

