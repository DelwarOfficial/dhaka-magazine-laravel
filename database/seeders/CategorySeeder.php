<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Support\CategoryRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        $columns = collect(Schema::getColumnListing('categories'));
        $parentsBySlug = [];

        foreach (CategoryRepository::parents() as $parent) {
            $parentsBySlug[$parent['slug']] = $this->upsertCategory($parent, null, $columns);

            foreach ($parent['children'] as $child) {
                $this->upsertCategory($child, $parentsBySlug[$parent['slug']] ?? null, $columns);
            }
        }
    }

    private function upsertCategory(array $category, ?int $parentId, $columns): ?int
    {
        $payload = [
            'name' => $category['name_bn'],
            'name_bn' => $category['name_bn'],
            'name_en' => $category['name_en'] ?? null,
            'slug' => $category['slug'],
            'parent_id' => $parentId,
            'meta_title' => $category['meta_title'],
            'meta_description' => $category['meta_description'],
            'status' => $category['status'],
            'active' => $category['status'],
            'is_active' => $category['status'],
            'sort_order' => $category['sort_order'],
            'menu_order' => $category['menu_order'],
            'order' => $category['sort_order'],
            'updated_at' => now(),
        ];

        $existing = $this->existingCategory($category, $columns);
        $exists = (bool) $existing;

        if (!$exists && $columns->contains('created_at')) {
            $payload['created_at'] = now();
        }

        $payload = collect($payload)
            ->only($columns)
            ->all();

        if ($existing) {
            $existing->fill($payload)->save();
        } else {
            Category::updateOrCreate(['slug' => $category['slug']], $payload);
        }

        return Category::where('slug', $category['slug'])->value('id');
    }

    private function existingCategory(array $category, $columns): ?Category
    {
        $query = Category::query()->where('slug', $category['slug']);

        if ($columns->contains('name_bn')) {
            $query->orWhere('name_bn', $category['name_bn']);
        }

        if ($columns->contains('name')) {
            $query->orWhere('name', $category['name_bn']);
        }

        return $query->first();
    }
}
