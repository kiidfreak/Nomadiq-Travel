export default function Stats() {
  const stats = [
    { number: '500+', label: 'Happy travelers' },
    { number: '15', label: 'Expert guides' },
    { number: '4.9/5', label: 'Avg. rating' },
  ]

  return (
    <section id="stats-section" className="py-16 bg-nomadiq-bone">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {stats.map((stat, index) => (
            <div
              key={index}
              className="bg-white/60 backdrop-blur-sm rounded-2xl p-8 text-center border border-nomadiq-sand/30 hover:shadow-lg transition-shadow duration-300"
            >
              <div className="text-4xl md:text-5xl font-serif font-bold text-nomadiq-copper mb-2">
                {stat.number}
              </div>
              <div className="text-nomadiq-black/70 font-medium uppercase tracking-wide text-sm">
                {stat.label}
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}

