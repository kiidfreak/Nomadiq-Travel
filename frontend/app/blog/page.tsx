'use client'

import { useState, useEffect } from 'react'
import Link from 'next/link'
import Header from '@/components/Header'
import Footer from '@/components/Footer'
import { blogPostsApi } from '@/lib/api'

interface BlogPost {
  id: number
  title: string
  slug: string
  excerpt?: string
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

export default function BlogPage() {
  const [posts, setPosts] = useState<BlogPost[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)

  useEffect(() => {
    fetchPosts()
  }, [])

  const fetchPosts = async () => {
    try {
      setLoading(true)
      const response = await blogPostsApi.getAll()
      if (response.data.success) {
        setPosts(response.data.data || [])
      }
    } catch (err: any) {
      setError(err.message || 'Failed to load blog posts')
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
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        {/* Header */}
        <div className="text-center mb-16">
          <h1 className="text-5xl md:text-6xl font-serif text-nomadiq-black mb-6">
            Blog
          </h1>
          <p className="text-xl text-nomadiq-black/70 max-w-2xl mx-auto">
            Stories, tips, and insights from Kenya's coast
          </p>
        </div>

        {/* Loading State */}
        {loading && (
          <div className="text-center py-16">
            <p className="text-nomadiq-black/60">Loading posts...</p>
          </div>
        )}

        {/* Error State */}
        {error && (
          <div className="text-center py-16">
            <p className="text-red-600">{error}</p>
          </div>
        )}

        {/* Blog Posts Grid */}
        {!loading && !error && (
          <>
            {posts.length === 0 ? (
              <div className="text-center py-16">
                <p className="text-nomadiq-black/60 text-lg">
                  No blog posts available yet. Check back soon!
                </p>
              </div>
            ) : (
              <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {posts.map((post) => (
                  <article
                    key={post.id}
                    className="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow"
                  >
                    {post.featured_image && (
                      <div className="aspect-video bg-nomadiq-sand/30 overflow-hidden">
                        <img
                          src={post.featured_image}
                          alt={post.title}
                          className="w-full h-full object-cover"
                        />
                      </div>
                    )}
                    <div className="p-6">
                      {post.category && (
                        <span className="inline-block text-sm text-nomadiq-copper font-medium mb-2">
                          {post.category.name}
                        </span>
                      )}
                      <h2 className="text-2xl font-serif text-nomadiq-black mb-3">
                        <Link
                          href={`/blog/${post.slug}`}
                          className="hover:text-nomadiq-copper transition-colors"
                        >
                          {post.title}
                        </Link>
                      </h2>
                      {post.excerpt && (
                        <p className="text-nomadiq-black/70 mb-4 line-clamp-3">
                          {post.excerpt}
                        </p>
                      )}
                      <div className="flex items-center justify-between text-sm text-nomadiq-black/60">
                        {post.author && (
                          <span>By {post.author.name}</span>
                        )}
                        <time dateTime={post.published_at || post.created_at}>
                          {formatDate(post.published_at || post.created_at)}
                        </time>
                      </div>
                    </div>
                  </article>
                ))}
              </div>
            )}
          </>
        )}
      </div>
      <Footer />
    </main>
  )
}

