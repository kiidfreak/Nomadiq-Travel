'use client'

import { useEffect, useState } from 'react'
import { useParams, useRouter } from 'next/navigation'
import Link from 'next/link'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { packagesApi, bookingsApi } from '@/lib/api'
import { MapPin, Star, Calendar, Users, ArrowLeft, Check, X, Info, AlertCircle, Map } from 'lucide-react'

interface Package {
  id: number
  title: string
  description: string
  duration_days: number
  price_usd: number
  max_participants: number
  image_url: string
  destinations?: Array<{ name: string }>
  itineraries?: Array<{
    id: number
    day_number: number
    title: string
    description: string
  }>
}

export default function PackageDetailPage() {
  const params = useParams()
  const router = useRouter()
  const [packageData, setPackageData] = useState<Package | null>(null)
  const [loading, setLoading] = useState(true)
  const [bookingData, setBookingData] = useState({
    start_date: '',
    number_of_people: 1,
    special_requests: '',
    customer: {
      name: '',
      email: '',
      phone: '',
      country: '',
    },
  })
  const [isBooking, setIsBooking] = useState(false)

  useEffect(() => {
    const fetchPackage = async () => {
      try {
        const response = await packagesApi.getById(params.id as string)
        if (response.data.success) {
          setPackageData(response.data.data)
        }
      } catch (error) {
        console.error('Error fetching package:', error)
        // Fallback data
        if (params.id === '1') {
          setPackageData({
            id: 1,
            title: 'Weekend Bash - 2 Nights / 1 Day',
            description: 'Join us for a high-vibe coastal weekend at Watamu: villa stay, sunset dhow, beach party, and sand dune adventure. Perfect for fun, friends & memories. Includes accommodation in beachfront villa for 2 nights with half-meal plan, sunset dhow ride, beach party at Papa Remo Beach, and sand dunes excursion to Mambrui Sand Dunes.',
            duration_days: 2,
            price_usd: 200,
            max_participants: 15,
            image_url: '/images/weekend-bash.jpg',
            destinations: [{ name: 'Watamu' }],
            itineraries: [
              {
                id: 1,
                day_number: 1,
                title: 'Arrival & Sunset Dhow',
                description: 'Check into beachfront villa, enjoy half-meal plan, and experience a magical sunset dhow ride.',
              },
              {
                id: 2,
                day_number: 2,
                title: 'Beach Party & Sand Dunes',
                description: 'Beach party at Papa Remo Beach followed by sand dunes excursion to Mambrui.',
              },
            ],
          })
        } else if (params.id === '2') {
          setPackageData({
            id: 2,
            title: 'Explorer Weekend - 3 Days / 2 Nights',
            description: 'Dive deeper into the coast with our Explorer Weekend: includes safari or dhow, historic site visits, sand dune rides and canyon sunset. Ideal for adventurers and memory-makers.',
            duration_days: 3,
            price_usd: 300,
            max_participants: 15,
            image_url: '/images/explorer-weekend.jpg',
            destinations: [{ name: 'Watamu' }],
            itineraries: [
              {
                id: 1,
                day_number: 1,
                title: 'Safari Blu or Dhow Ride',
                description: 'Marine park exploration or dhow ride experience.',
              },
              {
                id: 2,
                day_number: 2,
                title: 'Cultural Tour',
                description: 'Visit Gedi Ruins, Malindi National Museum, and Vasco Da Gama with choice of transport.',
              },
              {
                id: 3,
                day_number: 3,
                title: 'Sand Dunes & Hells Kitchen',
                description: 'Sand dunes adventure and visit to Hells Kitchen (Marafa Canyon).',
              },
            ],
          })
        }
      } finally {
        setLoading(false)
      }
    }

    if (params.id) {
      fetchPackage()
    }
  }, [params.id])

  const handleBooking = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsBooking(true)
    try {
      const response = await bookingsApi.create({
        package_id: packageData?.id!,
        start_date: bookingData.start_date,
        number_of_people: bookingData.number_of_people,
        special_requests: bookingData.special_requests || undefined,
        customer: {
          name: bookingData.customer.name,
          email: bookingData.customer.email,
          phone: bookingData.customer.phone,
          country: bookingData.customer.country,
        },
      })

      if (response.data.success) {
        router.push(`/bookings/${response.data.data.id}`)
      }
    } catch (error: any) {
      console.error('Error creating booking:', error)
      let errorMessage = 'Error creating booking. Please try again.'
      
      if (error.response?.data?.errors) {
        // Handle validation errors
        const errors = error.response.data.errors
        const errorList = Object.entries(errors)
          .map(([field, messages]: [string, any]) => `${field}: ${Array.isArray(messages) ? messages.join(', ') : messages}`)
          .join('\n')
        errorMessage = `Validation failed:\n${errorList}`
      } else if (error.response?.data?.message) {
        errorMessage = error.response.data.message
      } else if (error.message) {
        errorMessage = error.message
      }
      
      alert(errorMessage)
    } finally {
      setIsBooking(false)
    }
  }

  if (loading) {
    return (
      <div className="min-h-screen bg-nomadiq-bone">
        <Header />
        <main className="pt-20">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div className="bg-nomadiq-sand/20 rounded-2xl h-96 animate-pulse"></div>
          </div>
        </main>
        <Footer />
      </div>
    )
  }

  if (!packageData) {
    return (
      <div className="min-h-screen bg-nomadiq-bone">
        <Header />
        <main className="pt-20">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h1 className="text-3xl font-serif font-bold mb-4 text-nomadiq-black">Package not found</h1>
            <Link href="/packages" className="text-nomadiq-copper hover:text-nomadiq-copper/80 font-medium">
              ← Back to Packages
            </Link>
          </div>
        </main>
        <Footer />
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-nomadiq-bone">
      <Header />
      <main className="pt-20">
        {/* Hero Image */}
        <section className="relative h-[60vh] bg-gradient-to-br from-nomadiq-copper/30 to-nomadiq-teal/30 overflow-hidden">
          <div className="absolute inset-0 bg-gradient-to-t from-nomadiq-black/60 via-nomadiq-black/20 to-transparent z-10"></div>
          <div className="absolute bottom-0 left-0 right-0 p-8 z-20 text-white">
            <Link
              href="/packages"
              className="inline-flex items-center space-x-2 mb-4 text-white/90 hover:text-white transition-colors"
            >
              <ArrowLeft className="w-5 h-5" />
              <span>Back to Packages</span>
            </Link>
            <h1 className="text-4xl md:text-5xl font-serif font-bold mb-4">{packageData.title}</h1>
          </div>
        </section>

        {/* Content */}
        <section className="py-16 bg-white">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
              {/* Main Content */}
              <div className="lg:col-span-2">
                <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
                  <h2 className="text-2xl font-serif font-bold mb-4 text-nomadiq-black">About This Experience</h2>
                  <p className="text-nomadiq-black/80 leading-relaxed mb-6">{packageData.description}</p>

                  {/* Key Details */}
                  <div className="grid grid-cols-2 gap-6 pt-6 border-t border-nomadiq-sand/30">
                    <div className="flex items-center space-x-3">
                      <Calendar className="w-5 h-5 text-nomadiq-copper" />
                      <div>
                        <div className="text-sm text-nomadiq-black/60">Duration</div>
                        <div className="font-semibold text-nomadiq-black">
                          {packageData.duration_days} {packageData.duration_days === 1 ? 'Day' : 'Days'}
                        </div>
                      </div>
                    </div>
                    <div className="flex items-center space-x-3">
                      <Users className="w-5 h-5 text-nomadiq-copper" />
                      <div>
                        <div className="text-sm text-nomadiq-black/60">Max People</div>
                        <div className="font-semibold text-nomadiq-black">
                          {packageData.max_participants} {packageData.max_participants === 1 ? 'Person' : 'People'}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Itinerary */}
                {packageData.itineraries && packageData.itineraries.length > 0 && (
                  <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
                    <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">Itinerary</h2>
                    <div className="space-y-6">
                      {packageData.itineraries.map((itinerary) => (
                        <div key={itinerary.id} className="flex space-x-4">
                          <div className="flex-shrink-0">
                            <div className="w-12 h-12 rounded-full bg-nomadiq-copper text-white flex items-center justify-center font-bold">
                              {itinerary.day_number}
                            </div>
                          </div>
                          <div className="flex-1">
                            <h3 className="font-semibold text-nomadiq-black mb-2">{itinerary.title}</h3>
                            <p className="text-nomadiq-black/70">{itinerary.description}</p>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>
                )}

                {/* What's Included */}
                <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
                  <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">What's Included</h2>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div className="flex items-start space-x-3">
                      <Check className="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">Accommodation for {packageData.duration_days} {packageData.duration_days === 1 ? 'night' : 'nights'}</span>
                    </div>
                    <div className="flex items-start space-x-3">
                      <Check className="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">Half-meal plan (breakfast & dinner)</span>
                    </div>
                    <div className="flex items-start space-x-3">
                      <Check className="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">All activities mentioned in itinerary</span>
                    </div>
                    <div className="flex items-start space-x-3">
                      <Check className="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">Expert local guides</span>
                    </div>
                    <div className="flex items-start space-x-3">
                      <Check className="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">Transportation for activities</span>
                    </div>
                    <div className="flex items-start space-x-3">
                      <Check className="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">24/7 support during your stay</span>
                    </div>
                  </div>
                </div>

                {/* What's Not Included */}
                <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
                  <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">What's Not Included</h2>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div className="flex items-start space-x-3">
                      <X className="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">International flights</span>
                    </div>
                    <div className="flex items-start space-x-3">
                      <X className="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">Travel insurance</span>
                    </div>
                    <div className="flex items-start space-x-3">
                      <X className="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">Lunch (unless specified)</span>
                    </div>
                    <div className="flex items-start space-x-3">
                      <X className="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">Personal expenses & tips</span>
                    </div>
                    <div className="flex items-start space-x-3">
                      <X className="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">Alcoholic beverages</span>
                    </div>
                    <div className="flex items-start space-x-3">
                      <X className="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" />
                      <span className="text-nomadiq-black/80">Optional activities not in itinerary</span>
                    </div>
                  </div>
                </div>

                {/* Highlights */}
                <div className="bg-gradient-to-br from-nomadiq-copper/10 to-nomadiq-teal/10 rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
                  <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">Experience Highlights</h2>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="flex items-start space-x-3">
                      <Star className="w-5 h-5 text-nomadiq-copper mt-0.5 flex-shrink-0 fill-nomadiq-copper" />
                      <div>
                        <h3 className="font-semibold text-nomadiq-black mb-1">Beachfront Accommodation</h3>
                        <p className="text-nomadiq-black/70 text-sm">Stay in a beautiful beachfront villa with stunning ocean views</p>
                      </div>
                    </div>
                    <div className="flex items-start space-x-3">
                      <Star className="w-5 h-5 text-nomadiq-copper mt-0.5 flex-shrink-0 fill-nomadiq-copper" />
                      <div>
                        <h3 className="font-semibold text-nomadiq-black mb-1">Sunset Dhow Experience</h3>
                        <p className="text-nomadiq-black/70 text-sm">Magical sunset dhow ride along the pristine coastline</p>
                      </div>
                    </div>
                    <div className="flex items-start space-x-3">
                      <Star className="w-5 h-5 text-nomadiq-copper mt-0.5 flex-shrink-0 fill-nomadiq-copper" />
                      <div>
                        <h3 className="font-semibold text-nomadiq-black mb-1">Beach Party</h3>
                        <p className="text-nomadiq-black/70 text-sm">Vibrant beach party at Papa Remo Beach</p>
                      </div>
                    </div>
                    <div className="flex items-start space-x-3">
                      <Star className="w-5 h-5 text-nomadiq-copper mt-0.5 flex-shrink-0 fill-nomadiq-copper" />
                      <div>
                        <h3 className="font-semibold text-nomadiq-black mb-1">Sand Dunes Adventure</h3>
                        <p className="text-nomadiq-black/70 text-sm">Exciting sand dunes excursion to Mambrui</p>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Important Information */}
                <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
                  <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black flex items-center gap-2">
                    <Info className="w-6 h-6 text-nomadiq-copper" />
                    Important Information
                  </h2>
                  <div className="space-y-4">
                    <div className="flex items-start space-x-3">
                      <AlertCircle className="w-5 h-5 text-nomadiq-copper mt-0.5 flex-shrink-0" />
                      <div>
                        <h3 className="font-semibold text-nomadiq-black mb-1">Cancellation Policy</h3>
                        <p className="text-nomadiq-black/70 text-sm">Free cancellation up to 7 days before departure. 50% refund for cancellations 3-7 days before. No refund for cancellations less than 3 days before.</p>
                      </div>
                    </div>
                    <div className="flex items-start space-x-3">
                      <AlertCircle className="w-5 h-5 text-nomadiq-copper mt-0.5 flex-shrink-0" />
                      <div>
                        <h3 className="font-semibold text-nomadiq-black mb-1">What to Bring</h3>
                        <p className="text-nomadiq-black/70 text-sm">Sunscreen, hat, comfortable walking shoes, swimwear, camera, and a sense of adventure!</p>
                      </div>
                    </div>
                    <div className="flex items-start space-x-3">
                      <AlertCircle className="w-5 h-5 text-nomadiq-copper mt-0.5 flex-shrink-0" />
                      <div>
                        <h3 className="font-semibold text-nomadiq-black mb-1">Health & Safety</h3>
                        <p className="text-nomadiq-black/70 text-sm">All activities are conducted with safety as our top priority. Our guides are trained in first aid and emergency procedures.</p>
                      </div>
                    </div>
                    <div className="flex items-start space-x-3">
                      <AlertCircle className="w-5 h-5 text-nomadiq-copper mt-0.5 flex-shrink-0" />
                      <div>
                        <h3 className="font-semibold text-nomadiq-black mb-1">Group Size</h3>
                        <p className="text-nomadiq-black/70 text-sm">Maximum {packageData.max_participants} participants per group to ensure personalized attention and quality experience.</p>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Location Map */}
                {packageData.destinations && packageData.destinations.length > 0 && (
                  <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
                    <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black flex items-center gap-2">
                      <Map className="w-6 h-6 text-nomadiq-copper" />
                      Location
                    </h2>
                    <div className="bg-nomadiq-sand/20 rounded-lg p-6 text-center">
                      <MapPin className="w-12 h-12 text-nomadiq-copper mx-auto mb-4" />
                      <h3 className="text-xl font-semibold text-nomadiq-black mb-2">{packageData.destinations[0].name}</h3>
                      <p className="text-nomadiq-black/70 mb-4">Kenya's stunning coastal region</p>
                      <a
                        href={`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(packageData.destinations[0].name + ', Kenya')}`}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="inline-flex items-center gap-2 px-6 py-3 bg-nomadiq-copper text-white rounded-lg hover:bg-nomadiq-copper/90 transition-colors"
                      >
                        <Map className="w-4 h-4" />
                        View on Google Maps
                      </a>
                    </div>
                  </div>
                )}

                {/* FAQs */}
                <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30">
                  <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">Frequently Asked Questions</h2>
                  <div className="space-y-6">
                    <div>
                      <h3 className="font-semibold text-nomadiq-black mb-2">What is the best time to visit?</h3>
                      <p className="text-nomadiq-black/70 text-sm">The coastal region is beautiful year-round, but the best weather is typically from June to October and December to March.</p>
                    </div>
                    <div>
                      <h3 className="font-semibold text-nomadiq-black mb-2">Is this suitable for families?</h3>
                      <p className="text-nomadiq-black/70 text-sm">Yes! This experience is perfect for families. Children of all ages are welcome, though some activities may have age restrictions.</p>
                    </div>
                    <div>
                      <h3 className="font-semibold text-nomadiq-black mb-2">What if I have dietary restrictions?</h3>
                      <p className="text-nomadiq-black/70 text-sm">Please let us know about any dietary restrictions when booking. We'll do our best to accommodate your needs.</p>
                    </div>
                    <div>
                      <h3 className="font-semibold text-nomadiq-black mb-2">Can I customize this package?</h3>
                      <p className="text-nomadiq-black/70 text-sm">Absolutely! Contact us to discuss customizing this experience to better suit your preferences and interests.</p>
                    </div>
                  </div>
                </div>
              </div>

              {/* Booking Sidebar */}
              <div className="lg:col-span-1">
                <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 sticky top-24">
                  <div className="mb-6">
                    <div className="flex items-center space-x-2 mb-4">
                      <Star className="w-5 h-5 fill-yellow-400 text-yellow-400" />
                      <span className="font-semibold text-nomadiq-black">4.9</span>
                      <span className="text-nomadiq-black/60 text-sm">(500+ reviews)</span>
                    </div>
                    {packageData.destinations && packageData.destinations.length > 0 && (
                      <div className="flex items-center space-x-2 text-nomadiq-black/70 mb-4">
                        <MapPin className="w-4 h-4" />
                        <span>{packageData.destinations[0].name}</span>
                      </div>
                    )}
                    <div className="text-4xl font-serif font-bold text-nomadiq-copper mb-2">
                      ${packageData.price_usd}
                    </div>
                    <div className="text-sm text-nomadiq-black/60">per person</div>
                  </div>

                  <form onSubmit={handleBooking} className="space-y-4">
                    <div className="border-b border-nomadiq-sand/30 pb-4 mb-4">
                      <h3 className="text-lg font-semibold text-nomadiq-black mb-4">Your Information</h3>
                      <div className="space-y-4">
                        <div>
                          <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                            Full Name *
                          </label>
                          <input
                            type="text"
                            required
                            value={bookingData.customer.name}
                            onChange={(e) =>
                              setBookingData({
                                ...bookingData,
                                customer: { ...bookingData.customer, name: e.target.value },
                              })
                            }
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
                            value={bookingData.customer.email}
                            onChange={(e) =>
                              setBookingData({
                                ...bookingData,
                                customer: { ...bookingData.customer, email: e.target.value },
                              })
                            }
                            className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                          />
                        </div>
                        <div>
                          <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                            Phone *
                          </label>
                          <input
                            type="tel"
                            required
                            value={bookingData.customer.phone}
                            onChange={(e) =>
                              setBookingData({
                                ...bookingData,
                                customer: { ...bookingData.customer, phone: e.target.value },
                              })
                            }
                            className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                          />
                        </div>
                        <div>
                          <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                            Country *
                          </label>
                          <input
                            type="text"
                            required
                            value={bookingData.customer.country}
                            onChange={(e) =>
                              setBookingData({
                                ...bookingData,
                                customer: { ...bookingData.customer, country: e.target.value },
                              })
                            }
                            className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                          />
                        </div>
                      </div>
                    </div>

                    <div className="border-b border-nomadiq-sand/30 pb-4 mb-4">
                      <h3 className="text-lg font-semibold text-nomadiq-black mb-4">Booking Details</h3>
                      <div className="space-y-4">
                        <div>
                          <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                            Start Date *
                          </label>
                          <input
                            type="date"
                            required
                            min={new Date().toISOString().split('T')[0]}
                            value={bookingData.start_date}
                            onChange={(e) => setBookingData({ ...bookingData, start_date: e.target.value })}
                            className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                          />
                        </div>

                        <div>
                          <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                            Number of People *
                          </label>
                          <input
                            type="number"
                            min="1"
                            max={packageData?.max_participants || 50}
                            required
                            value={bookingData.number_of_people || ''}
                            onChange={(e) => {
                              const value = e.target.value === '' ? 1 : parseInt(e.target.value, 10)
                              if (!isNaN(value) && value >= 1) {
                                setBookingData({ ...bookingData, number_of_people: value })
                              }
                            }}
                            className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                          />
                        </div>
                      </div>
                    </div>

                    <div>
                      <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                        Special Requests (Optional)
                      </label>
                      <textarea
                        value={bookingData.special_requests}
                        onChange={(e) => setBookingData({ ...bookingData, special_requests: e.target.value })}
                        rows={3}
                        className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                      />
                    </div>

                    <button
                      type="submit"
                      disabled={isBooking}
                      className="w-full px-6 py-4 bg-gradient-to-r from-nomadiq-copper to-nomadiq-orange text-white font-semibold rounded-lg hover:shadow-xl hover:scale-105 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed relative overflow-hidden"
                    >
                      {isBooking ? (
                        <span className="flex items-center justify-center gap-2">
                          <svg className="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                          <span>Creating your booking...</span>
                        </span>
                      ) : (
                        'Book Now'
                      )}
                      {isBooking && (
                        <div className="absolute inset-0 overflow-hidden pointer-events-none">
                          <div className="absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-t from-white/30 via-white/20 to-transparent animate-wave opacity-60"></div>
                          <div className="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-white/20 via-white/10 to-transparent animate-wave-delayed opacity-40"></div>
                          <div className="absolute bottom-0 left-0 right-0 h-6 bg-gradient-to-t from-white/15 to-transparent animate-wave opacity-30" style={{ animationDelay: '1.6s' }}></div>
                        </div>
                      )}
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
      <Footer />
    </div>
  )
}

