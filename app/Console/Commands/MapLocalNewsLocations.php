<?php

namespace App\Console\Commands;

use App\Http\Controllers\HomeController;
use App\Models\Post;
use App\Support\ArticleFeed;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MapLocalNewsLocations extends Command
{
    protected $signature = 'homepage:map-local-news-locations {--force : Rebuild Local News location IDs for the legacy visible posts}';

    protected $description = 'Assign complete division/district/upazila IDs to the posts currently visible in the Local News homepage component.';

    public function handle(): int
    {
        if (! $this->columnsReady()) {
            $this->error('Location ID columns or lookup tables are missing. Run php artisan migrate first.');

            return self::FAILURE;
        }

        $articles = ArticleFeed::homepageArticles(app(HomeController::class)->fallbackArticles());
        $legacyIds = $this->articleIdsAt($articles, [18, 10, 6, 4, 15, 19, 8, 2, 11]);

        if ($legacyIds === []) {
            $this->warn('No legacy Local News posts were found to map.');

            return self::SUCCESS;
        }

        $locations = $this->locationPool();

        if ($locations === []) {
            $this->error('No complete division/district/upazila records are available.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($legacyIds, $locations) {
            foreach ($legacyIds as $index => $postId) {
                $post = Post::query()->find($postId);

                if (! $post) {
                    continue;
                }

                if (! $this->option('force') && $post->division_id && $post->district_id && $post->upazila_id) {
                    $this->line("Skipped post {$postId}; it already has complete location IDs.");

                    continue;
                }

                $location = $locations[$index % count($locations)];

                // Migration/update safety: only the legacy Local News pool is touched,
                // and every mapped post receives all three CMS location IDs together.
                $post->forceFill([
                    'division_id' => $location['division_id'],
                    'district_id' => $location['district_id'],
                    'upazila_id' => $location['upazila_id'],
                    'division' => $location['division'],
                    'district' => $location['district'],
                    'upazila' => $location['upazila'],
                ])->save();

                $this->info("Mapped post {$postId} to {$location['division']} / {$location['district']} / {$location['upazila']}.");
            }
        });

        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        $this->info('Local News location mapping complete. Application and view caches were cleared.');

        return self::SUCCESS;
    }

    private function columnsReady(): bool
    {
        return Schema::hasTable('posts')
            && Schema::hasTable('divisions')
            && Schema::hasTable('districts')
            && Schema::hasTable('upazilas')
            && Schema::hasColumn('posts', 'division_id')
            && Schema::hasColumn('posts', 'district_id')
            && Schema::hasColumn('posts', 'upazila_id');
    }

    private function articleIdsAt(array $articles, array $indexes): array
    {
        return collect($indexes)
            ->map(fn(int $index) => $articles[$index]['id'] ?? null)
            ->filter(fn($id) => filled($id))
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    private function locationPool(): array
    {
        return collect([
            ['Dhaka', 'Dhaka', 'Savar'],
            ['Dhaka', 'Gazipur', 'Kaliakair'],
            ['Chattogram', 'Chattogram', 'Sitakunda'],
            ['Rajshahi', 'Rajshahi', 'Paba'],
            ['Khulna', 'Khulna', 'Dumuria'],
            ['Barishal', 'Barishal', 'Barishal Sadar'],
            ['Sylhet', 'Sylhet', 'Sylhet Sadar'],
            ['Rangpur', 'Rangpur', 'Rangpur Sadar'],
            ['Mymensingh', 'Mymensingh', 'Mymensingh Sadar'],
        ])->map(function (array $location) {
            [$divisionName, $districtName, $upazilaName] = $location;

            $divisionId = DB::table('divisions')->where('name', $divisionName)->value('id');
            $district = DB::table('districts')
                ->where('division_id', $divisionId)
                ->where('name', $districtName)
                ->first(['id', 'name']);

            $upazila = $district
                ? DB::table('upazilas')
                    ->where('division_id', $divisionId)
                    ->where('district_id', $district->id)
                    ->where('name', $upazilaName)
                    ->first(['id', 'name'])
                : null;

            if (! $divisionId || ! $district || ! $upazila) {
                return null;
            }

            return [
                'division_id' => (int) $divisionId,
                'district_id' => (int) $district->id,
                'upazila_id' => (int) $upazila->id,
                'division' => $divisionName,
                'district' => $district->name,
                'upazila' => $upazila->name,
            ];
        })->filter()->values()->all();
    }
}
