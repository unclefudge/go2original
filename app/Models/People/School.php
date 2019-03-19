<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';
    protected $fillable = ['name', 'grade_from', 'grade_to', 'status', 'aid',  'created_by', 'updated_by'];

    /**
     * A School belongs to a account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Account\Account', 'aid');
    }

    /**
     * A School has many Students
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('App\User', 'school_id');
    }

    /**
     * A School has many grades.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function grades()
    {
        return $this->belongsToMany('App\Models\People\Grade', 'schools_grades', 'sid', 'gid');
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
