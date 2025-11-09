'use client'

import { useEffect, useState } from 'react'
import { useParams, useRouter } from 'next/navigation'
import Link from 'next/link'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { bookingsApi } from '@/lib/api'
import { CheckCircle, Calendar, Users, MapPin, ArrowLeft, Mail, Phone } from 'lucide-react'
import { fetchCurrencyRate, usdToKsh, formatKsh, formatUsd, setUsdToKshRate } from '@/lib/currency'

interface Booking {
  id: number
  booking_reference: string
  package_id: number
  start_date: string
  number_of_people: number
  total_amount: number | string
  status: string
  special_requests?: string
  selected_micro_experiences?: Array<{
    id: number
    title: string
    price_usd?: number
  }>
  addons_total?: number | string
  balance?: number | string
  total_paid?: number | string
  package?: {
    id: number
    title: string
    price_usd: number
  }
  customer?: {
    id: number
    name: string
    email: string
    phone: string
    country: string
  }
}

export default function BookingConfirmationPage() {
  const params = useParams()
  const router = useRouter()
  const [booking, setBooking] = useState<Booking | null>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)
  const [currencyRate, setCurrencyRate] = useState(140)

  useEffect(() => {
    const fetchData = async () => {
      try {
        // Fetch currency rate
        const rate = await fetchCurrencyRate()
        setCurrencyRate(rate)
        setUsdToKshRate(rate)

        // Fetch booking
        const response = await bookingsApi.getById(params.id as string)
        if (response.data.success) {
          // Handle nested response structure from backend
          const bookingData = response.data.data.booking || response.data.data
          setBooking(bookingData)
        } else {
          setError('Booking not found')
        }
      } catch (error: any) {
        console.error('Error fetching data:', error)
        if (error.response?.status === 404) {
          setError('Booking not found')
        } else {
          setError(error.response?.data?.message || 'Failed to load booking details')
        }
      } finally {
        setLoading(false)
      }
    }

    if (params.id) {
      fetchData()
    }
  }, [params.id])

  if (loading) {
    return (
      <div className="min-h-screen bg-nomadiq-bone">
        <Header />
        <main className="pt-20">
          <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30">
              <div className="animate-pulse space-y-4">
                <div className="h-8 bg-nomadiq-sand/20 rounded w-1/3"></div>
                <div className="h-4 bg-nomadiq-sand/20 rounded w-2/3"></div>
                <div className="h-32 bg-nomadiq-sand/20 rounded"></div>
              </div>
            </div>
          </div>
        </main>
        <Footer />
      </div>
    )
  }

  if (error || !booking) {
    return (
      <div className="min-h-screen bg-nomadiq-bone">
        <Header />
        <main className="pt-20">
          <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 text-center">
              <h1 className="text-3xl font-serif font-bold mb-4 text-nomadiq-black">Booking Not Found</h1>
              <p className="text-nomadiq-black/70 mb-6">{error || 'The booking you are looking for does not exist.'}</p>
              <Link
                href="/packages"
                className="inline-flex items-center gap-2 px-6 py-3 bg-nomadiq-copper text-white rounded-lg hover:bg-nomadiq-copper/90 transition-colors"
              >
                <ArrowLeft className="w-4 h-4" />
                Back to Packages
              </Link>
            </div>
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
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
          {/* Success Message */}
          <div className="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 border-2 border-green-200 mb-8">
            <div className="flex items-center gap-4 mb-4">
              <div className="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center">
                <CheckCircle className="w-10 h-10 text-white" />
              </div>
              <div>
                <h1 className="text-3xl font-serif font-bold text-nomadiq-black">Booking Confirmed!</h1>
                <p className="text-nomadiq-black/70">Your adventure is all set. We're excited to have you join us!</p>
              </div>
            </div>
          </div>

          {/* Booking Details */}
          <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
            <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">Booking Details</h2>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div className="flex items-start space-x-3">
                <div className="w-10 h-10 rounded-lg bg-nomadiq-copper/10 flex items-center justify-center flex-shrink-0">
                  <span className="text-nomadiq-copper font-bold text-sm">REF</span>
                </div>
                <div>
                  <div className="text-sm text-nomadiq-black/60 mb-1">Booking Reference</div>
                  <div className="font-semibold text-nomadiq-black">{booking.booking_reference}</div>
                </div>
              </div>

              <div className="flex items-start space-x-3">
                <Calendar className="w-5 h-5 text-nomadiq-copper mt-0.5 flex-shrink-0" />
                <div>
                  <div className="text-sm text-nomadiq-black/60 mb-1">Start Date</div>
                  <div className="font-semibold text-nomadiq-black">
                    {new Date(booking.start_date).toLocaleDateString('en-US', {
                      weekday: 'long',
                      year: 'numeric',
                      month: 'long',
                      day: 'numeric',
                    })}
                  </div>
                </div>
              </div>

              <div className="flex items-start space-x-3">
                <Users className="w-5 h-5 text-nomadiq-copper mt-0.5 flex-shrink-0" />
                <div>
                  <div className="text-sm text-nomadiq-black/60 mb-1">Number of People</div>
                  <div className="font-semibold text-nomadiq-black">
                    {booking.number_of_people} {booking.number_of_people === 1 ? 'person' : 'people'}
                  </div>
                </div>
              </div>

              <div className="flex items-start space-x-3">
                <div className="w-5 h-5 text-nomadiq-copper mt-0.5 flex-shrink-0 flex items-center justify-center">
                  <span className="text-lg font-bold">KSh</span>
                </div>
                <div className="group/price">
                  <div className="text-sm text-nomadiq-black/60 mb-1">Total Amount</div>
                  <div className="font-semibold text-nomadiq-black text-xl">
                    {formatKsh(usdToKsh(typeof booking.total_amount === 'number' ? booking.total_amount : parseFloat(booking.total_amount || '0')))}
                  </div>
                  <div className="text-xs text-nomadiq-black/50 hidden group-hover/price:block">
                    {formatUsd(typeof booking.total_amount === 'number' ? booking.total_amount : parseFloat(booking.total_amount || '0'))}
                  </div>
                </div>
              </div>
            </div>

            {booking.package && (
              <div className="pt-6 border-t border-nomadiq-sand/30">
                <h3 className="font-semibold text-nomadiq-black mb-3">Package</h3>
                <div className="flex justify-between items-center">
                  <p className="text-nomadiq-black/80">{booking.package.title}</p>
                  <div className="text-right group/price">
                    <div className="font-semibold text-nomadiq-copper">
                      {formatKsh(usdToKsh(booking.package.price_usd * booking.number_of_people))}
                    </div>
                    <div className="text-xs text-nomadiq-black/50 hidden group-hover/price:block">
                      {formatUsd(booking.package.price_usd * booking.number_of_people)}
                    </div>
                  </div>
                </div>
              </div>
            )}

            {/* Selected Add-ons */}
            {booking.selected_micro_experiences && booking.selected_micro_experiences.length > 0 && (
              <div className="pt-6 border-t border-nomadiq-sand/30">
                <h3 className="font-semibold text-nomadiq-black mb-3">Selected Add-ons</h3>
                <div className="space-y-2">
                  {booking.selected_micro_experiences.map((addon, idx) => {
                    const addonPricePerPerson = addon.price_usd || 0
                    const addonTotal = addonPricePerPerson * booking.number_of_people
                    return (
                      <div key={idx} className="flex justify-between items-center py-2">
                        <div>
                          <span className="text-nomadiq-black/80">{addon.title}</span>
                          {booking.number_of_people > 1 && (
                            <span className="text-xs text-nomadiq-black/50 ml-2">
                              ({booking.number_of_people} × {formatUsd(addonPricePerPerson)})
                            </span>
                          )}
                        </div>
                        {addon.price_usd && (
                          <div className="text-right group/price">
                            <span className="font-semibold text-nomadiq-copper">
                              {formatKsh(usdToKsh(addonTotal))}
                            </span>
                            <div className="text-xs text-nomadiq-black/50 hidden group-hover/price:block">
                              {formatUsd(addonTotal)}
                            </div>
                          </div>
                        )}
                      </div>
                    )
                  })}
                  {booking.addons_total && parseFloat(String(booking.addons_total)) > 0 && (
                    <div className="pt-2 border-t border-nomadiq-sand/30 flex justify-between items-center font-semibold">
                      <span className="text-nomadiq-black">
                        Add-ons Total ({booking.number_of_people} {booking.number_of_people === 1 ? 'person' : 'people'}):
                      </span>
                      <div className="text-right group/price">
                        <span className="text-nomadiq-copper">
                          {formatKsh(usdToKsh(parseFloat(String(booking.addons_total))))}
                        </span>
                        <div className="text-xs text-nomadiq-black/50 hidden group-hover/price:block">
                          {formatUsd(parseFloat(String(booking.addons_total)))}
                        </div>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            )}

            {booking.special_requests && (
              <div className="pt-6 border-t border-nomadiq-sand/30">
                <h3 className="font-semibold text-nomadiq-black mb-2">Special Requests</h3>
                <p className="text-nomadiq-black/70">{booking.special_requests}</p>
              </div>
            )}
          </div>

          {/* Customer Information */}
          {booking.customer && (
            <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
              <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">Your Information</h2>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <div className="text-sm text-nomadiq-black/60 mb-1">Name</div>
                  <div className="font-semibold text-nomadiq-black">{booking.customer.name}</div>
                </div>
                <div>
                  <div className="text-sm text-nomadiq-black/60 mb-1">Email</div>
                  <div className="font-semibold text-nomadiq-black">{booking.customer.email}</div>
                </div>
                <div>
                  <div className="text-sm text-nomadiq-black/60 mb-1">Phone</div>
                  <div className="font-semibold text-nomadiq-black">{booking.customer.phone}</div>
                </div>
                <div>
                  <div className="text-sm text-nomadiq-black/60 mb-1">Country</div>
                  <div className="font-semibold text-nomadiq-black">{booking.customer.country}</div>
                </div>
              </div>
            </div>
          )}

          {/* Next Steps */}
          <div className="bg-gradient-to-br from-nomadiq-copper/10 to-nomadiq-teal/10 rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
            <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">What's Next?</h2>
            <div className="space-y-4">
              <div className="flex items-start space-x-3">
                <div className="w-6 h-6 rounded-full bg-nomadiq-copper text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                  <span className="text-xs font-bold">1</span>
                </div>
                <div>
                  <h3 className="font-semibold text-nomadiq-black mb-1">Confirmation Email</h3>
                  <p className="text-nomadiq-black/70 text-sm">
                    You'll receive a confirmation email with all the details and next steps within the next few minutes.
                  </p>
                </div>
              </div>
              <div className="flex items-start space-x-3">
                <div className="w-6 h-6 rounded-full bg-nomadiq-copper text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                  <span className="text-xs font-bold">2</span>
                </div>
                <div>
                  <h3 className="font-semibold text-nomadiq-black mb-1">Payment Instructions</h3>
                  <p className="text-nomadiq-black/70 text-sm">
                    Payment details and instructions will be sent to your email. You can pay via bank transfer or other methods.
                  </p>
                </div>
              </div>
              <div className="flex items-start space-x-3">
                <div className="w-6 h-6 rounded-full bg-nomadiq-copper text-white flex items-center justify-center flex-shrink-0 mt-0.5">
                  <span className="text-xs font-bold">3</span>
                </div>
                <div>
                  <h3 className="font-semibold text-nomadiq-black mb-1">Pre-Trip Information</h3>
                  <p className="text-nomadiq-black/70 text-sm">
                    We'll send you a detailed itinerary, packing list, and important information before your trip.
                  </p>
                </div>
              </div>
            </div>
          </div>

          {/* Contact Information */}
          <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
            <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">Need Help?</h2>
            <p className="text-nomadiq-black/70 mb-4">
              If you have any questions or need to make changes to your booking, please don't hesitate to contact us.
            </p>
            <div className="flex flex-wrap gap-4">
              <a
                href="mailto:info@nomadiq.com"
                className="inline-flex items-center gap-2 px-4 py-2 bg-nomadiq-sand/20 text-nomadiq-black rounded-lg hover:bg-nomadiq-sand/30 transition-colors"
              >
                <Mail className="w-4 h-4" />
                info@nomadiq.com
              </a>
              <a
                href="tel:+254700757129"
                className="inline-flex items-center gap-2 px-4 py-2 bg-nomadiq-sand/20 text-nomadiq-black rounded-lg hover:bg-nomadiq-sand/30 transition-colors"
              >
                <Phone className="w-4 h-4" />
                +254 700 757 129
              </a>
            </div>
          </div>

          {/* Action Buttons */}
          <div className="flex flex-col sm:flex-row gap-4">
            {(() => {
              const balance = typeof booking.balance === 'number' ? booking.balance : parseFloat(booking.balance || '0')
              const totalPaid = typeof booking.total_paid === 'number' ? booking.total_paid : parseFloat(booking.total_paid || '0')
              const totalAmount = typeof booking.total_amount === 'number' ? booking.total_amount : parseFloat(booking.total_amount || '0')
              // Show button if balance > 0, or if booking is pending and total_paid < total_amount
              const hasBalance = balance > 0 || (booking.status === 'pending' && totalPaid < totalAmount && totalAmount > 0)
              
              return hasBalance && (
                <Link
                  href={`/bookings/${params.id}/payment`}
                  className="flex-1 px-6 py-3 bg-gradient-to-r from-nomadiq-copper to-nomadiq-orange text-white text-center rounded-lg hover:shadow-xl hover:scale-105 transition-all duration-300 font-semibold"
                >
                  Make Payment
                </Link>
              )
            })()}
            <Link
              href="/packages"
              className="flex-1 px-6 py-3 border-2 border-nomadiq-black text-nomadiq-black text-center rounded-lg hover:bg-nomadiq-black hover:text-white transition-colors font-semibold"
            >
              Browse More Packages
            </Link>
            <Link
              href="/"
              className="flex-1 px-6 py-3 bg-nomadiq-copper text-white text-center rounded-lg hover:bg-nomadiq-copper/90 transition-colors font-semibold"
            >
              Back to Home
            </Link>
          </div>
        </div>
      </main>
      <Footer />
    </div>
  )
}

