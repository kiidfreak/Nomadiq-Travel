/**
 * Currency conversion utilities
 */

let usdToKshRate: number = 140 // Default rate

/**
 * Fetch the current USD to KSh conversion rate from the API
 */
export async function fetchCurrencyRate(): Promise<number> {
  try {
    const response = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL || 'https://nevcompany2.test/api'}/settings`
    )
    const data = await response.json()
    if (data.success && data.data?.usd_to_ksh_rate) {
      usdToKshRate = data.data.usd_to_ksh_rate
    }
  } catch (error) {
    console.error('Error fetching currency rate:', error)
    // Keep default rate
  }
  return usdToKshRate
}

/**
 * Convert USD to Kenyan Shillings
 */
export function usdToKsh(usd: number): number {
  if (isNaN(usd) || !isFinite(usd)) {
    return 0
  }
  return usd * usdToKshRate
}

/**
 * Format currency in KSh
 */
export function formatKsh(amount: number): string {
  if (isNaN(amount) || !isFinite(amount)) {
    return 'KSh 0'
  }
  return new Intl.NumberFormat('en-KE', {
    style: 'currency',
    currency: 'KES',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(amount)
}

/**
 * Format currency in USD
 */
export function formatUsd(amount: number): string {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(amount)
}

/**
 * Set the conversion rate (for testing or manual updates)
 */
export function setUsdToKshRate(rate: number): void {
  usdToKshRate = rate
}

/**
 * Get the current conversion rate
 */
export function getUsdToKshRate(): number {
  return usdToKshRate
}

