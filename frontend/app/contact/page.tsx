'use client'

import { useState } from 'react'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { inquiriesApi } from '@/lib/api'

export default function ContactPage() {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
  })
  const [loading, setLoading] = useState(false)
  const [success, setSuccess] = useState(false)
  const [error, setError] = useState<string | null>(null)

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setLoading(true)
    setError(null)
    setSuccess(false)

    try {
      await inquiriesApi.create({
        name: formData.name,
        email: formData.email,
        phone: formData.phone || undefined,
        message: `${formData.subject ? `Subject: ${formData.subject}\n\n` : ''}${formData.message}`,
      })
      setSuccess(true)
      setFormData({
        name: '',
        email: '',
        phone: '',
        subject: '',
        message: '',
      })
    } catch (err: any) {
      setError(err.response?.data?.message || 'Failed to send message. Please try again.')
    } finally {
      setLoading(false)
    }
  }

  return (
    <main className="min-h-screen pt-20">
      <Header />
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Header */}
        <div className="text-center mb-16">
          <h1 className="text-5xl md:text-6xl font-serif text-nomadiq-black mb-6">
            Contact Us
          </h1>
          <p className="text-xl text-nomadiq-black/70 max-w-2xl mx-auto">
            We'd love to hear from you. Get in touch and let's plan your next adventure.
          </p>
        </div>

        <div className="grid md:grid-cols-2 gap-12">
          {/* Contact Information */}
          <div>
            <h2 className="text-2xl font-serif text-nomadiq-black mb-6">Get in Touch</h2>
            <div className="space-y-6">
              <div>
                <h3 className="text-lg font-medium text-nomadiq-copper mb-2">Email</h3>
                <a
                  href="mailto:info@nomadiq.com"
                  className="text-nomadiq-black/80 hover:text-nomadiq-copper transition-colors"
                >
                  info@nomadiq.com
                </a>
              </div>
              <div>
                <h3 className="text-lg font-medium text-nomadiq-copper mb-2">Phone</h3>
                <a
                  href="tel:+254700757129"
                  className="text-nomadiq-black/80 hover:text-nomadiq-copper transition-colors"
                >
                  +254 700 757 129
                </a>
              </div>
              <div>
                <h3 className="text-lg font-medium text-nomadiq-copper mb-2">Location</h3>
                <p className="text-nomadiq-black/80">
                  Watamu, Kenya<br />
                  Coastal Region
                </p>
              </div>
            </div>

            <div className="mt-8 p-6 bg-nomadiq-sand/30 rounded-lg">
              <h3 className="text-lg font-medium text-nomadiq-black mb-3">Business Hours</h3>
              <p className="text-nomadiq-black/70 text-sm">
                Monday - Friday: 9:00 AM - 6:00 PM<br />
                Saturday: 10:00 AM - 4:00 PM<br />
                Sunday: Closed
              </p>
            </div>
          </div>

          {/* Contact Form */}
          <div>
            <h2 className="text-2xl font-serif text-nomadiq-black mb-6">Send a Message</h2>
            
            {success && (
              <div className="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p className="text-green-800">
                  Thank you! Your message has been sent. We'll get back to you soon.
                </p>
              </div>
            )}

            {error && (
              <div className="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p className="text-red-800">{error}</p>
              </div>
            )}

            <form onSubmit={handleSubmit} className="space-y-6">
              <div>
                <label htmlFor="name" className="block text-sm font-medium text-nomadiq-black mb-2">
                  Name *
                </label>
                <input
                  type="text"
                  id="name"
                  required
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                />
              </div>

              <div>
                <label htmlFor="email" className="block text-sm font-medium text-nomadiq-black mb-2">
                  Email *
                </label>
                <input
                  type="email"
                  id="email"
                  required
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                  className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                />
              </div>

              <div>
                <label htmlFor="phone" className="block text-sm font-medium text-nomadiq-black mb-2">
                  Phone
                </label>
                <input
                  type="tel"
                  id="phone"
                  value={formData.phone}
                  onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                  className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                />
              </div>

              <div>
                <label htmlFor="subject" className="block text-sm font-medium text-nomadiq-black mb-2">
                  Subject *
                </label>
                <input
                  type="text"
                  id="subject"
                  required
                  value={formData.subject}
                  onChange={(e) => setFormData({ ...formData, subject: e.target.value })}
                  className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                />
              </div>

              <div>
                <label htmlFor="message" className="block text-sm font-medium text-nomadiq-black mb-2">
                  Message *
                </label>
                <textarea
                  id="message"
                  required
                  rows={6}
                  value={formData.message}
                  onChange={(e) => setFormData({ ...formData, message: e.target.value })}
                  className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper resize-none"
                />
              </div>

              <button
                type="submit"
                disabled={loading}
                className="w-full px-6 py-3 bg-nomadiq-copper text-white font-medium rounded-lg hover:bg-nomadiq-copper/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {loading ? 'Sending...' : 'Send Message'}
              </button>
            </form>
          </div>
        </div>
      </div>
      <Footer />
    </main>
  )
}

