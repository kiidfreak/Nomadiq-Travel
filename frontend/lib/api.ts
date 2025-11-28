import axios from 'axios'

// Force the API URL to use the correct domain
const apiUrl = typeof window !== 'undefined'
  ? (process.env.NEXT_PUBLIC_API_URL || 'https://nomadiq-travel-production.up.railway.app/api')
  : (process.env.NEXT_PUBLIC_API_URL || 'https://nomadiq-travel-production.up.railway.app/api')

// Debug: Log the API URL being used (only in development)
if (typeof window !== 'undefined' && process.env.NODE_ENV === 'development') {
  console.log('🌊 API URL:', apiUrl)
}

const api = axios.create({
  baseURL: apiUrl,
  headers: {
    'Content-Type': 'application/json',
  },
})

export default api

export const packagesApi = {
  getAll: () => api.get('/packages'),
  getFeatured: () => api.get('/packages/featured'),
  getById: (id: string | number) => api.get(`/packages/${id}`),
  search: (params: { destination_id?: number; min_price?: number; max_price?: number; duration?: number }) =>
    api.get('/packages/search', { params }),
}

export const testimonialsApi = {
  getAll: () => api.get('/testimonials'),
  getFeatured: () => api.get('/testimonials/featured'),
  create: (data: { customer_name: string; package_name: string; rating: number; comment: string }) =>
    api.post('/testimonials', data),
}

export const memoriesApi = {
  getAll: () => api.get('/memories'),
  getLatest: (limit?: number) => api.get('/memories/latest', { params: { limit } }),
  getBySlots: () => api.get('/memories/by-slots'),
  getById: (id: string | number) => api.get(`/memories/${id}`),
}

export const bookingsApi = {
  create: (data: {
    package_id: number
    start_date: string
    number_of_people: number
    special_requests?: string
    selected_micro_experiences?: number[]
    customer: {
      name: string
      email: string
      phone: string
      country: string
    }
  }) => api.post('/bookings', data),
  getById: (id: string | number) => api.get(`/bookings/${id}`),
  confirm: (id: string | number) => api.patch(`/bookings/${id}/confirm`),
}

export const inquiriesApi = {
  create: (data: {
    name: string
    email: string
    phone?: string
    message: string
    package_id?: number
  }) => api.post('/inquiries', data),
}

export const proposalsApi = {
  create: (data: {
    name: string
    email: string
    phone?: string
    message: string
    travel_dates?: string
    number_of_people?: number
  }) => api.post('/proposals', data),
}

export const paymentsApi = {
  create: (data: {
    booking_id: number
    amount: number
    payment_method: 'mpesa' | 'bank_transfer' | 'card'
    phone_number?: string
    transaction_id?: string
  }) => api.post('/payments', data),
  getById: (id: string | number) => api.get(`/payments/${id}`),
  getByBooking: (bookingId: string | number) => api.get(`/bookings/${bookingId}/payments`),
  verify: (id: string | number, data: { payment_status: 'pending' | 'completed' | 'failed'; transaction_id?: string }) =>
    api.patch(`/payments/${id}/verify`, data),
}

export const blogPostsApi = {
  getAll: () => api.get('/blog-posts'),
  getById: (id: string | number) => api.get(`/blog-posts/${id}`),
  getBySlug: (slug: string) => api.get(`/blog-posts/slug/${slug}`),
}

export const microExperiencesApi = {
  getAll: (packageId?: number) => api.get('/micro-experiences', { params: packageId ? { package_id: packageId } : {} }),
  getById: (id: string | number) => api.get(`/micro-experiences/${id}`),
  getByCategory: (category?: string) => api.get('/micro-experiences/category', { params: { category } }),
}

export const settingsApi = {
  getAll: () => api.get('/settings'),
  getByKey: (key: string) => api.get(`/settings/${key}`),
}

