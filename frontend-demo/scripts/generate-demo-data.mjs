import { mkdir, writeFile } from 'node:fs/promises';
import path from 'node:path';

const categories = [
  ['bangladesh', 'বাংলাদেশ'],
  ['politics', 'রাজনীতি'],
  ['economy', 'অর্থনীতি'],
  ['world', 'আন্তর্জাতিক'],
  ['sports', 'খেলাধুলা'],
  ['entertainment', 'বিনোদন'],
  ['lifestyle', 'লাইফস্টাইল'],
  ['technology', 'প্রযুক্তি'],
  ['local-news', 'সারাদেশ'],
];

const images = [
  'news-1.jpg', 'news-2.jpg', 'news-3.jpg', 'news-4.jpg', 'news-5.jpg', 'news-6.jpg', 'news-7.jpg', 'news-8.jpg',
  'a.jpg', 'b.jpg', 'c.jpg', 'd.jpg', 'e.jpg', 'f.jpg', 'g.jpg', 'h.jpg', 'i.jpg', 'j.jpg', 'coming-soon-ad.webp',
];

const authors = ['ঢাকা ম্যাগাজিন ডেস্ক', 'নিজস্ব প্রতিবেদক', 'বিশেষ প্রতিনিধি', 'ডিজিটাল ডেস্ক', 'রিপোর্টিং টিম'];
const locations = ['ঢাকা', 'চট্টগ্রাম', 'রাজশাহী', 'খুলনা', 'বরিশাল', 'সিলেট', 'রংপুর', 'ময়মনসিংহ'];
const topics = ['নতুন উদ্যোগ', 'পরিবর্তনের প্রভাব', 'সাম্প্রতিক পরিস্থিতি', 'নাগরিক সেবা', 'বিশেষ প্রতিবেদন', 'স্থানীয় পর্যায়', 'অর্থনৈতিক সম্ভাবনা', 'প্রযুক্তির ব্যবহার'];

const posts = Array.from({ length: 330 }, (_, index) => {
  const id = index + 1;
  const [category, category_bn] = categories[index % categories.length];
  const location = locations[index % locations.length];
  const topic = topics[index % topics.length];
  const published = new Date(Date.UTC(2026, 4, 10, 12, 0, 0) - index * 36 * 60 * 1000);

  return {
    id,
    slug: `demo-news-${id}`,
    title: `${category_bn}: ${topic} নিয়ে ${location} থেকে আপডেট ${id}`,
    excerpt: `${location} অঞ্চলের সর্বশেষ তথ্য, জনজীবনের প্রভাব ও প্রাসঙ্গিক বিশ্লেষণ নিয়ে সংক্ষিপ্ত ডেমো প্রতিবেদন।`,
    content: `${location} অঞ্চলের এই ডেমো প্রতিবেদনে ${topic} সম্পর্কিত প্রেক্ষাপট, নাগরিক প্রতিক্রিয়া এবং ভবিষ্যৎ সম্ভাবনা তুলে ধরা হয়েছে।`,
    author: authors[index % authors.length],
    category,
    category_bn,
    image_path: images[index % images.length],
    view_count: 1200 + ((index * 137) % 48000),
    published_at: published.toISOString(),
    is_breaking: index < 10,
    is_featured: index === 0,
    is_body_news: index >= 1 && index <= 6,
    is_trending: index >= 7 && index <= 14,
    is_editor_pick: index >= 15 && index <= 20,
  };
});

const outDir = path.resolve('public/demo-data');
await mkdir(outDir, { recursive: true });
await writeFile(path.join(outDir, 'posts.json'), `${JSON.stringify(posts, null, 2)}\n`, 'utf8');

console.log(`Generated ${posts.length} demo posts at public/demo-data/posts.json`);
