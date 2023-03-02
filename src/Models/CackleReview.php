<?php

namespace Aleksei4er\LaravelCackleSync\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CackleReview extends Model
{
    protected $guarded = ['id'];

    /**
     * Channel relation
     *
     * @return BelongsTo
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(CackleChannel::class, 'channel_id', 'channel_id');
    }

    /**
     * Get formatted applied_date.
     *
     * @return Carbon
     */
    public function getCackleDateAttribute()
    {
        return Carbon::createFromTimestampMs($this->modified);
    }
}
