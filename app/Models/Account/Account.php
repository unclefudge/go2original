<?php

namespace App\Models\Account;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';
    protected $fillable = [
        'name', 'slug', 'timezone', 'dateformat', 'country', 'banner', 'grade_adv',
        'status', 'created_by', 'updated_by'];


    /**
     * A Account has many Users
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\User', 'aid');
    }

    /**
     * A Account has many Admins
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function admins()
    {
        return $this->belongsToMany('App\User', 'accounts_security', 'aid', 'uid');
    }

    /**
     * A Account has many Schools
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schools()
    {
        return $this->hasMany('App\Models\People\School', 'aid');
    }

    /**
     * A Account has many Grades
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grades()
    {
        return $this->hasMany('App\Models\People\Grade', 'aid');
    }

    /**
     * A dropdown list of schools for this account
     *
     * @return array
     */
    public function schoolsSelect($prompt = '', $status = 1)
    {
        $array = [];
        foreach ($this->schools as $school)
            if ($school->status == $status)
                $array[$school->id] =  $school->name;

        asort($array);

        if ($prompt == 'all')
            return ($prompt && count($array) > 1) ? $array = array('' => 'All Schools') + $array : $array;
        if ($prompt == 'ALL')
            return ($prompt && count($array) > 1) ? $array = array('all' => 'All Schools') + $array : $array;

        return ($prompt && count($array) > 1) ? $array = array('' => 'Select School') + $array : $array;
    }

    /**
     * A dropdown list of grades for this account
     *
     * @return array
     */
    public function gradesSelect($prompt = '', $status = 1)
    {
        $array = [];
        foreach ($this->grades as $grade)
            if ($grade->status == $status)
                $array[$grade->id] =  "$grade->order:$grade->name";

        // Sort array by the Sort Order field we added to front o string, Once sorted remove it
        asort($array, SORT_NUMERIC);
        $sorted_array = [];
        foreach ($array as $key => $value) {
            $split = explode(':', $value, 2);
            $sorted_array[$key] = $split[1];
        }

        if ($prompt == 'all')
            return ($prompt && count($sorted_array) > 1) ? $sorted_array = array('' => 'All Grades') + $sorted_array : $sorted_array;
        if ($prompt == 'ALL')
            return ($prompt && count($sorted_array) > 1) ? $sorted_array = array('all' => 'All Grades') + $sorted_array : $sorted_array;

        return ($prompt && count($array) > 1) ? $sorted_array = array('' => 'Select Grade') + $sorted_array : $sorted_array;
    }


    /**
     * Display records last update_by + date
     *
     * @return string
     */
    public function displayUpdatedBy()
    {
        $user = User::find($this->updated_by);

        return ($user) ? '<span style="font-weight: 400">Last modified: </span>' . $this->updated_at->diffForHumans() . ' &nbsp; ' .
            '<span style="font-weight: 400">By:</span> ' . $user->name : "$this->updated_by";
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
            });

            // create a event to happen on updating
            static::updating(function ($table) {
                $table->updated_by = auth()->id();
            });
        }
    }
}
