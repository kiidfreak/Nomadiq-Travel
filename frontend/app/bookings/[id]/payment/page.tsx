'use client'

import { useEffect, useState } from 'react'
import { useParams, useRouter } from 'next/navigation'
import Link from 'next/link'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { bookingsApi, paymentsApi } from '@/lib/api'
import { CreditCard, Smartphone, Building2, CheckCircle, XCircle, Clock, ArrowLeft } from 'lucide-react'

interface Booking {
  id: number
  booking_reference: string
  total_amount: number
  balance: number
  total_paid: number
  is_fully_paid: boolean
  package?: {
    title: string
  }
}

interface Payment {
  id: number
  amount: number
  payment_method: string
  payment_status: string
  transaction_id: string
  created_at: string
  paid_at?: string
}

export default function PaymentPage() {
  const params = useParams()
  const router = useRouter()
  const [booking, setBooking] = useState<Booking | null>(null)
  const [payments, setPayments] = useState<Payment[]>([])
  const [loading, setLoading] = useState(true)
  const [submitting, setSubmitting] = useState(false)
  const [selectedMethod, setSelectedMethod] = useState<'mpesa' | 'bank_transfer' | 'card'>('mpesa')
  const [paymentData, setPaymentData] = useState({
    amount: '',
    phone_number: '',
  })

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [bookingResponse, paymentsResponse] = await Promise.all([
          bookingsApi.getById(params.id as string),
          paymentsApi.getByBooking(params.id as string),
        ])

        if (bookingResponse.data.success) {
          const bookingData = bookingResponse.data.data.booking || bookingResponse.data.data
          setBooking(bookingData)
          setPaymentData(prev => ({ ...prev, amount: bookingData.balance?.toString() || '' }))
        }

        if (paymentsResponse.data.success) {
          setPayments(paymentsResponse.data.data.payments || [])
        }
      } catch (error) {
        console.error('Error fetching data:', error)
      } finally {
        setLoading(false)
      }
    }

    if (params.id) {
      fetchData()
    }
  }, [params.id])

  const handlePayment = async (e: React.FormEvent) => {
    e.preventDefault()
    setSubmitting(true)

    try {
      const response = await paymentsApi.create({
        booking_id: booking!.id,
        amount: parseFloat(paymentData.amount),
        payment_method: selectedMethod,
        phone_number: selectedMethod === 'mpesa' ? paymentData.phone_number : undefined,
      })

      if (response.data.success) {
        // Refresh data
        const [bookingResponse, paymentsResponse] = await Promise.all([
          bookingsApi.getById(params.id as string),
          paymentsApi.getByBooking(params.id as string),
        ])

        if (bookingResponse.data.success) {
          const bookingData = bookingResponse.data.data.booking || bookingResponse.data.data
          setBooking(bookingData)
        }

        if (paymentsResponse.data.success) {
          setPayments(paymentsResponse.data.data.payments || [])
        }

        // Reset form
        setPaymentData({ amount: '', phone_number: '' })
        alert('Payment initiated successfully! Please complete the payment on your phone (for M-Pesa) or send proof of payment.')
      }
    } catch (error: any) {
      console.error('Error creating payment:', error)
      const errorMessage = error.response?.data?.message || 'Failed to process payment. Please try again.'
      alert(errorMessage)
    } finally {
      setSubmitting(false)
    }
  }

  if (loading) {
    return (
      <div className="min-h-screen bg-nomadiq-bone">
        <Header />
        <main className="pt-20">
          <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30">
              <div className="animate-pulse space-y-4">
                <div className="h-8 bg-nomadiq-sand/20 rounded w-1/3"></div>
                <div className="h-32 bg-nomadiq-sand/20 rounded"></div>
              </div>
            </div>
          </div>
        </main>
        <Footer />
      </div>
    )
  }

  if (!booking) {
    return (
      <div className="min-h-screen bg-nomadiq-bone">
        <Header />
        <main className="pt-20">
          <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
            <h1 className="text-3xl font-serif font-bold mb-4 text-nomadiq-black">Booking not found</h1>
            <Link href="/packages" className="text-nomadiq-copper hover:text-nomadiq-copper/80 font-medium">
              ← Back to Packages
            </Link>
          </div>
        </main>
        <Footer />
      </div>
    )
  }

  const balance = typeof booking.balance === 'number' ? booking.balance : parseFloat(booking.balance || '0')
  const totalPaid = typeof booking.total_paid === 'number' ? booking.total_paid : parseFloat(booking.total_paid || '0')

  return (
    <div className="min-h-screen bg-nomadiq-bone">
      <Header />
      <main className="pt-20">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
          {/* Header */}
          <div className="mb-8">
            <Link
              href={`/bookings/${params.id}`}
              className="inline-flex items-center gap-2 text-nomadiq-copper hover:text-nomadiq-copper/80 mb-4"
            >
              <ArrowLeft className="w-4 h-4" />
              Back to Booking
            </Link>
            <h1 className="text-3xl md:text-4xl font-serif font-bold text-nomadiq-black mb-2">
              Make Payment
            </h1>
            <p className="text-nomadiq-black/70">Booking Reference: {booking.booking_reference}</p>
          </div>

          {/* Payment Summary */}
          <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
            <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">Payment Summary</h2>
            <div className="space-y-4">
              <div className="flex justify-between items-center py-3 border-b border-nomadiq-sand/30">
                <span className="text-nomadiq-black/70">Total Amount:</span>
                <span className="text-xl font-semibold text-nomadiq-black">
                  ${typeof booking.total_amount === 'number' ? booking.total_amount.toFixed(2) : parseFloat(booking.total_amount || '0').toFixed(2)}
                </span>
              </div>
              <div className="flex justify-between items-center py-3 border-b border-nomadiq-sand/30">
                <span className="text-nomadiq-black/70">Total Paid:</span>
                <span className="text-lg font-semibold text-green-600">${totalPaid.toFixed(2)}</span>
              </div>
              <div className="flex justify-between items-center py-3">
                <span className="text-nomadiq-black font-semibold">Remaining Balance:</span>
                <span className={`text-2xl font-bold ${balance > 0 ? 'text-nomadiq-copper' : 'text-green-600'}`}>
                  ${balance.toFixed(2)}
                </span>
              </div>
            </div>
          </div>

          {/* Payment Methods */}
          {balance > 0 && (
            <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30 mb-8">
              <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">Select Payment Method</h2>
              
              <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <button
                  onClick={() => setSelectedMethod('mpesa')}
                  className={`p-4 rounded-lg border-2 transition-all ${
                    selectedMethod === 'mpesa'
                      ? 'border-nomadiq-copper bg-nomadiq-copper/10'
                      : 'border-nomadiq-sand/30 hover:border-nomadiq-copper/50'
                  }`}
                >
                  <Smartphone className={`w-8 h-8 mx-auto mb-2 ${selectedMethod === 'mpesa' ? 'text-nomadiq-copper' : 'text-nomadiq-black/50'}`} />
                  <div className="font-semibold text-nomadiq-black">M-Pesa</div>
                  <div className="text-sm text-nomadiq-black/60">Mobile Money</div>
                </button>

                <button
                  onClick={() => setSelectedMethod('bank_transfer')}
                  className={`p-4 rounded-lg border-2 transition-all ${
                    selectedMethod === 'bank_transfer'
                      ? 'border-nomadiq-copper bg-nomadiq-copper/10'
                      : 'border-nomadiq-sand/30 hover:border-nomadiq-copper/50'
                  }`}
                >
                  <Building2 className={`w-8 h-8 mx-auto mb-2 ${selectedMethod === 'bank_transfer' ? 'text-nomadiq-copper' : 'text-nomadiq-black/50'}`} />
                  <div className="font-semibold text-nomadiq-black">Bank Transfer</div>
                  <div className="text-sm text-nomadiq-black/60">Direct Transfer</div>
                </button>

                <button
                  onClick={() => setSelectedMethod('card')}
                  className={`p-4 rounded-lg border-2 transition-all ${
                    selectedMethod === 'card'
                      ? 'border-nomadiq-copper bg-nomadiq-copper/10'
                      : 'border-nomadiq-sand/30 hover:border-nomadiq-copper/50'
                  }`}
                >
                  <CreditCard className={`w-8 h-8 mx-auto mb-2 ${selectedMethod === 'card' ? 'text-nomadiq-copper' : 'text-nomadiq-black/50'}`} />
                  <div className="font-semibold text-nomadiq-black">Card</div>
                  <div className="text-sm text-nomadiq-black/60">Coming Soon</div>
                </button>
              </div>

              {/* Payment Form */}
              <form onSubmit={handlePayment} className="space-y-4">
                <div>
                  <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                    Amount to Pay *
                  </label>
                  <input
                    type="number"
                    step="0.01"
                    min="0.01"
                    max={balance}
                    required
                    value={paymentData.amount}
                    onChange={(e) => setPaymentData({ ...paymentData, amount: e.target.value })}
                    className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                  />
                </div>

                {selectedMethod === 'mpesa' && (
                  <div>
                    <label className="block text-sm font-semibold text-nomadiq-black mb-2">
                      M-Pesa Phone Number *
                    </label>
                    <input
                      type="tel"
                      placeholder="254712345678"
                      required
                      value={paymentData.phone_number}
                      onChange={(e) => setPaymentData({ ...paymentData, phone_number: e.target.value })}
                      className="w-full px-4 py-3 border border-nomadiq-sand/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-nomadiq-copper"
                    />
                    <p className="text-sm text-nomadiq-black/60 mt-1">Enter your M-Pesa registered phone number</p>
                  </div>
                )}

                {selectedMethod === 'bank_transfer' && (
                  <div className="bg-nomadiq-sand/20 rounded-lg p-4 space-y-2 text-sm">
                    <p className="font-semibold text-nomadiq-black">Bank Transfer Details:</p>
                    <p><strong>Bank:</strong> KCB Bank Kenya</p>
                    <p><strong>Account:</strong> Nomadiq</p>
                    <p><strong>Account Number:</strong> 1234567890</p>
                    <p><strong>Reference:</strong> {booking.booking_reference}</p>
                    <p className="text-nomadiq-black/70 mt-2">
                      After transfer, send proof of payment to: <strong>payments@nomadiq.com</strong>
                    </p>
                  </div>
                )}

                {selectedMethod === 'card' && (
                  <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p className="text-yellow-800">Card payments are coming soon. Please use M-Pesa or Bank Transfer for now.</p>
                  </div>
                )}

                <button
                  type="submit"
                  disabled={submitting || selectedMethod === 'card'}
                  className="w-full px-6 py-4 bg-gradient-to-r from-nomadiq-copper to-nomadiq-orange text-white font-semibold rounded-lg hover:shadow-xl hover:scale-105 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed"
                >
                  {submitting ? 'Processing...' : selectedMethod === 'mpesa' ? 'Initiate M-Pesa Payment' : 'Record Payment'}
                </button>
              </form>
            </div>
          )}

          {/* Payment History */}
          {payments.length > 0 && (
            <div className="bg-white rounded-2xl p-8 border border-nomadiq-sand/30">
              <h2 className="text-2xl font-serif font-bold mb-6 text-nomadiq-black">Payment History</h2>
              <div className="space-y-4">
                {payments.map((payment) => (
                  <div key={payment.id} className="flex items-center justify-between p-4 border border-nomadiq-sand/30 rounded-lg">
                    <div className="flex items-center gap-4">
                      {payment.payment_status === 'completed' ? (
                        <CheckCircle className="w-6 h-6 text-green-600" />
                      ) : payment.payment_status === 'failed' ? (
                        <XCircle className="w-6 h-6 text-red-600" />
                      ) : (
                        <Clock className="w-6 h-6 text-yellow-600" />
                      )}
                      <div>
                        <div className="font-semibold text-nomadiq-black">
                          ${parseFloat(payment.amount.toString()).toFixed(2)} - {payment.payment_method.toUpperCase()}
                        </div>
                        <div className="text-sm text-nomadiq-black/60">
                          {payment.transaction_id} • {new Date(payment.created_at).toLocaleDateString()}
                        </div>
                      </div>
                    </div>
                    <div className={`px-3 py-1 rounded-full text-sm font-semibold ${
                      payment.payment_status === 'completed' ? 'bg-green-100 text-green-800' :
                      payment.payment_status === 'failed' ? 'bg-red-100 text-red-800' :
                      'bg-yellow-100 text-yellow-800'
                    }`}>
                      {payment.payment_status.toUpperCase()}
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}

          {balance <= 0 && (
            <div className="bg-green-50 border-2 border-green-200 rounded-2xl p-8 text-center">
              <CheckCircle className="w-16 h-16 text-green-600 mx-auto mb-4" />
              <h2 className="text-2xl font-serif font-bold text-nomadiq-black mb-2">Payment Complete!</h2>
              <p className="text-nomadiq-black/70">This booking is fully paid. Thank you!</p>
            </div>
          )}
        </div>
      </main>
      <Footer />
    </div>
  )
}

