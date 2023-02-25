<?php

namespace Aleksei4er\LaravelCackleSync\Console\Commands;

use Aleksei4er\LaravelCackleSync\Jobs\LoadChannels;
use Aleksei4er\LaravelCackleSync\Jobs\LoadComments;
use Aleksei4er\LaravelCackleSync\Jobs\LoadReviews;
use Aleksei4er\LaravelCackleSync\Models\CackleComment;
use Aleksei4er\LaravelCackleSync\Models\CackleReview;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CackleSync extends Command
{
    protected $signature = 'cackle:sync {--type=*}';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $types = $this->getTypes();
        if (empty($types)) {
            return;
        }

        LoadChannels::dispatch()->onQueue('cackle');

        if (\in_array('comments', $types, true)) {
            $lastCommentId = CackleComment::max('id') ?? 0;
            LoadComments::dispatch($lastCommentId)->onQueue('cackle');
        }

        if (\in_array('reviews', $types, true)) {
            $modified = CackleReview::max('modified') ?? 0;
            LoadReviews::dispatch($modified)->onQueue('cackle');
        }
    }

    protected function getTypes(): array
    {
        $types = $this->option('type');

        if (!\is_array($types)) {
            $types = explode(',', (string) $types);
        }

        if (\count($types) === 1 && Str::contains($types[0], ',')) {
            $types = explode(',', (string) $types[0]);
        }

        return $types;
    }
}
