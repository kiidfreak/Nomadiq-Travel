'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { proposalsApi } from '@/lib/api'

export default function ProposalPage() {
  const router = useRouter()
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    message: '',
    travel_dates: '',
    number_of_people: '',
  })
  const [loading, setLoading] = useState(false)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)

    try {
      const response = await proposalsApi.create({
        ...formData,
        number_of_people: formData.number_of_people ? parseInt(formData.number_of_people) : undefined,
      })

      if (response.data.success) {
        router.push('/proposal/success')
      }
    } catch (error) {
      console.error('Error submitting proposal:', error)
      alert('Error submitting proposal. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="min-h-screen bg-nomadiq-bone">
      <Header />
      <main className="pt-20">
        <section className="py-20 bg-gradient-to-br from-nomadiq-copper/10 via-nomadiq-bone to-nomadiq-sky/10">
          <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="bg-white rounded-2xl p-8 md:p-12 border border-nomadiq-sand/30 shadow-xl">
              <h1 className="text-4xl md:text-5xl font-serif font-bold mb-4 text-nomadiq-black text-center">
                Make a Proposal
              </h1>
              <p className="text-center text-nomadiq-black/70 mb-8 text-lg">
                Tell us about your dream experience and we'll craft something unforgettable for you.
              </p>

              <form onSubmit={handleSubmit} className="space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                      Name *
                    </label>
                    <input
                      type="text"
                      required
                      value={formData.name}
                      onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                      className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                      Email *
                    </label>
                    <input
                      type="email"
                      required
                      value={formData.email}
                      onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                      className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                    />
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                    Phone
                  </label>
                  <input
                    type="tel"
                    value={formData.phone}
                    onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                    className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                  />
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                      Travel Dates
                    </label>
                    <input
                      type="text"
                      placeholder="e.g., Dec 22-24, 2025"
                      value={formData.travel_dates}
                      onChange={(e) => setFormData({ ...formData, travel_dates: e.target.value })}
                      className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                      Number of People
                    </label>
                    <input
                      type="number"
                      min="1"
                      value={formData.number_of_people}
                      onChange={(e) => setFormData({ ...formData, number_of_people: e.target.value })}
                      className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                    />
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                    Tell us about your dream experience *
                  </label>
                  <textarea
                    required
                    rows={6}
                    value={formData.message}
                    onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                    placeholder="Describe what you're looking for, your interests, budget, and any special requests..."
                    className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper resize-none"
                  />
                </div>

                <button
                  type="submit"
                  disabled={loading}
                  className="w-full px-6 py-4 bg-gradient-to-r from-nomadiq-copper to-nomadiq-orange text-white font-semibold rounded-lg hover:shadow-xl hover:scale-105 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {loading ? 'Submitting...' : 'Submit Proposal'}
                </button>
              </form>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </div>
  )
}

