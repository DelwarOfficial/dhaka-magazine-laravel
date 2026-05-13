<?php

namespace App\Support;

use App\Models\Post;
use Illuminate\Support\Str;

class ImageResolver
{
    public static function postImageUrl(Post $post): string
    {
        // Try featuredMedia with new column names first, then fall back
        if ($post->relationLoaded('featuredMedia') && $post->featuredMedia) {
            $media = $post->featuredMedia;
            if ($media->file_url) {
                return $media->file_url;
            }
            if ($media->url) {
                return $media->url;
            }
            if ($media->file_path) {
                return self::imageUrl($media->file_path);
            }
        }

        // Fall back to legacy image_path or featured_image
        $path = $post->image_path ?: $post->featured_image;
        if ($path) {
            return self::imageUrl($path);
        }

        return self::placeholderImageUrl();
    }

    public static function imageUrl(?string $path): string
    {
        if (! $path) {
            return asset('images/news-1.jpg');
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        if (Str::startsWith($path, ['/images/', 'images/'])) {
            return asset(ltrim($path, '/'));
        }

        if (! Str::contains($path, '/') && file_exists(public_path("images/{$path}"))) {
            return asset("images/{$path}");
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    public static function placeholderImageUrl(): string
    {
        foreach (['placeholder.jpg', 'news-1.jpg', 'coming-soon-ad.webp'] as $filename) {
            if (file_exists(public_path("images/{$filename}"))) {
                return asset("images/{$filename}");
            }
        }

        return asset('images/news-1.jpg');
    }
}
