<?php

namespace App\Models\People;

use Auth;
use App\User;
use App\Models\Event\Event;
use App\Models\Event\EventInstance;
use App\Models\Event\Attendance;
use App\Http\Utilities\Slim;
use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Illuminate\Database\Eloquent\Model;

class PeopleHistory extends Model {

    protected $table = 'people_history';
    protected $fillable = ['pid', 'action', 'type', 'subtype', 'ref', 'data', 'created_by'];

    /**
     * A PeopleHistory belongs to a People
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function people()
    {
        return $this->belongsTo('App\Models\People\People', 'pid');
    }

    /**
     * A PeopleHistory UpdateBy a People
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\user', 'created_by');
    }

    /**
     * The "booting" method of the model.
     *
     * Overrides parent function
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        if (auth()->check()) {
            // create a event to happen on creating
            static::creating(function ($table) {
                $table->created_by = auth()->id();
            });
        }
    }
}
