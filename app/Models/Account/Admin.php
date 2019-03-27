<?php

namespace App\Models\Account;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'accounts_security';
    protected $fillable = [
        'aid', 'uid', 'admin', 'billing', 'people', 'checkin', 'events', 'groups',
        'registrations', 'payments', 'created_by', 'updated_by'];

    /**
     * A Admin belongs to a account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account\Account', 'aid');
    }

    /**
     * A Admin belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'uid');
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
                $table->updated_by = auth()->id();
                $table->aid = session('aid');
            });

            // create a event to happen on updating
            static::updating(function ($table) {
                $table->updated_by = auth()->id();
            });
        }
    }
}
