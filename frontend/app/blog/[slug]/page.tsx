'use client'

import { useState, useEffect } from 'react'
import { useParams } from 'next/navigation'
import Link from 'next/link'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { blogPostsApi } from '@/lib/api'

interface BlogPost {
  id: number
  title: string
  slug: string
  content: string
  featured_image?: string
  category?: {
    id: number
    name: string
  }
  author?: {
    id: number
    name: string
  }
  published_at: string
  created_at: string
}

export default function BlogPostPage() {
  const params = useParams()
  const slug = params.slug as string
  const [post, setPost] = useState<BlogPost | null>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)

  useEffect(() => {
    if (slug) {
      fetchPost()
    }
  }, [slug])

  const fetchPost = async () => {
    try {
      setLoading(true)
      const response = await blogPostsApi.getBySlug(slug)
      if (response.data.success) {
        setPost(response.data.data)
      } else {
        setError('Post not found')
      }
    } catch (err: any) {
      setError(err.message || 'Failed to load blog post')
    } finally {
      setLoading(false)
    }
  }

  const formatDate = (dateString: string) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    })
  }

  return (
    <main className="min-h-screen pt-20">
      <Header />
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Loading State */}
        {loading && (
          <div className="text-center py-16">
            <p className="text-nomadiq-black/60">Loading post...</p>
          </div>
        )}

        {/* Error State */}
        {error && (
          <div className="text-center py-16">
            <p className="text-red-600 mb-4">{error}</p>
            <Link
              href="/blog"
              className="text-nomadiq-copper hover:underline"
            >
              Back to Blog
            </Link>
          </div>
        )}

        {/* Blog Post */}
        {!loading && !error && post && (
          <article>
            {/* Back Link */}
            <Link
              href="/blog"
              className="inline-block text-nomadiq-copper hover:underline mb-8"
            >
              ← Back to Blog
            </Link>

            {/* Header */}
            <header className="mb-8">
              {post.category && (
                <span className="inline-block text-sm text-nomadiq-copper font-medium mb-4">
                  {post.category.name}
                </span>
              )}
              <h1 className="text-4xl md:text-5xl font-serif text-nomadiq-black mb-6">
                {post.title}
              </h1>
              <div className="flex items-center gap-4 text-sm text-nomadiq-black/60">
                {post.author && (
                  <span>By {post.author.name}</span>
                )}
                <time dateTime={post.published_at || post.created_at}>
                  {formatDate(post.published_at || post.created_at)}
                </time>
              </div>
            </header>

            {/* Featured Image */}
            {post.featured_image && (
              <div className="mb-8 rounded-lg overflow-hidden">
                <img
                  src={post.featured_image}
                  alt={post.title}
                  className="w-full h-auto"
                />
              </div>
            )}

            {/* Content */}
            <div
              className="prose prose-lg max-w-none text-nomadiq-black/80 leading-relaxed"
              dangerouslySetInnerHTML={{ __html: post.content }}
            />
          </article>
        )}
      </div>
      <Footer />
    </main>
  )
}

