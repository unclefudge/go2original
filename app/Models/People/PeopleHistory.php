<?php

namespace App\Models\People;

use DB;
use Auth;
use App\User;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PeopleHistory extends Model {

    protected $table = 'users_history';
    protected $fillable = ['uid', 'action', 'type', 'subtype', 'ref', 'data', 'created_by'];

    /**
     * A UserHistory belongs to a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'uid');
    }

    /**
     * A UserHistory UpdateBy a User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updateByUser()
    {
        return $this->belongsTo('App\User', 'created_by');
    }


    /**
     * User History JSON Sample format:
     * {
     *   "1": {
     *         "title": "Personal",
     *         "data": [{
     *                   "1": {
     *                         "field": "Name",
     *                         "before": " ",
     *                          "after": "John Doe"
     *                        },
     *                   "2": {
     *                        "field": "Type",
     *                        "before": "",
     *                        "after": "Student"
     *                        }
     *                 }]
     *        },
     *   "2": {
     *         "title": "Student",
     *         "data": [{
     *                   "1": {
     *                         "field": "Grade",
     *                         "before": "",
     *                         "after": "10th"
     *                        },
     *                   "2": {
     *                         "field": "School",
     *                         "before": "",
     *                         "after": "Clarence High"
     *                        }
     *                 }]
     *        }
     * }
     *
     *
     *
     * Determine which fields have changed and generate User History JSON data
     *
     * @params $user, $type, $before, $after
     */
    static public function addHistory($user, $type, $b = '', $a = '')
    {
        if (!$b) $b = new User;
        if (!$a) $a = $user;
        $result = [];

        echo "Creating history for $user->name<br>";

        $p1 = '{"1": {"field": "Name", "before": " ", "after": "John Doe"}, "2": {"field": "Type", "before": "", "after": "Student"}}';
        $s2 = '{"1": {"field": "Grade", "before": "", "after": "10th"}, "2": {"field": "School", "before": "", "after": "Clarence High"}}';
        $new = '{"1": {"title": "Personal", "data": [' . $p1 . ']}, "2": {"title": "Student", "data": [' . $s2 . ']}}';
        $json = json_decode($new);

        //
        // Personal
        //
        $data = [];
        // Name
        if ($a->firstname != $b->firstname || $a->lastname != $b->lastname) {
            $data['Name'] = [];
            $data['Name']['a'] = $a->name;
            $data['Name']['b'] = $b->name;
        }
        // Address
        if ($a->address != $b->address || $a->suburb != $b->suburb || $a->state != $b->state || $a->postcode != $b->postcode) {
            $data['Address'] = [];
            $data['Address']['a'] = $a->address_formatted;
            $data['Address']['b'] = $b->address_formatted;
        }

        $dates = ['dob', 'wwc_exp', 'wwc_verified', 'media_consent_at', 'last_login'];
        $personal = ['type', 'gender', 'dob', 'email', 'phone', 'instagram'];
        foreach ($personal as $key) {
            if ($a[$key] != $b[$key]) {
                $KEY = ucfirst($key);
                if ($key == 'dob') $KEY = 'Birthdate';
                $data[$KEY] = [];

                if (in_array($key, $dates)) {
                    $data[$KEY]['a'] = ($a[$key]) ? $a[$key]->format(session('df')) : '';
                    $data[$KEY]['b'] = ($b[$key]) ? $b[$key]->format(session('df')) : '';
                } else {
                    $data[$KEY]['a'] = $a[$key];
                    $data[$KEY]['b'] = $b[$key];
                }
            }
        }
        if (count($data)) {
            $json = "{";
            $x = 1;
            foreach ($data as $key => $val)
                $json .= '"' . ($x ++) . '": {"field": "' . $key . '", "before": "' . $val['b'] . '", "after": "' . $val['a'] . '"}, ';
            $json = rtrim($json, ', ') . "}";
            $result['Personal'] = $json;
        }

        //
        // Student
        //
        $data = [];
        $student = ['grade', 'school_id', 'media_consent'];
        foreach ($student as $key) {
            if ($a[$key] != $b[$key]) {
                $KEY = ucfirst($key);
                if ($key == 'school_id') $KEY = 'School';
                if ($key == 'media_consent') $KEY = 'Media Consent';

                $data[$KEY] = [];

                if ($key == 'school_id') {
                    $data[$KEY]['a'] = ($a->school_id) ? $a->school->name : '';
                    $data[$KEY]['b'] = ($b->school_id) ? $b->school->name : '';
                } elseif ($key == 'grade') {
                    $data[$KEY]['a'] = ($a->grade) ? $a->grade_ordinal : '';
                    $data[$KEY]['b'] = ($b->grade) ? $b->grade_ordinal : '';
                } elseif ($key == 'media_consent') {
                    $data[$KEY]['a'] = ($a->media_consent) ? "$a->media_consent<br>" . $a->media_consent_at->format(session('df')) . "<br>By " . $a->mediaConsentByUser->name : '';
                    $data[$KEY]['b'] = ($b->media_consent) ? "$b->media_consent<br>" . $b->media_consent_at->format(session('df')) . "<br>By " . $b->mediaConsentByUser->name : '';
                }
            }
        }
        if (count($data)) {
            $json = "{";
            $x = 1;
            foreach ($data as $key => $val)
                $json .= '"' . ($x ++) . '": {"field": "' . $key . '", "before": "' . $val['b'] . '", "after": "' . $val['a'] . '"}, ';
            $json = rtrim($json, ', ') . "}";
            $result['Student'] = $json;
        }

        //
        // Volunteer
        //
        $data = [];
        $volunteer = ['wwc_no', 'wwc_exp', 'wwc_verified', 'wwc_verified_by'];
        if ($a->wwc_no != $b->wwc_no || $a->wwc_exp != $b->wwc_exp || $a->wwc_verified != $b->wwc_verified) {
            $data['WWC Registration'] = [];
            $wwc_no_after = ($a->wwc_no) ? "No: $a->wwc_no<br>" : '';
            $wwc_no_before = ($b->wwc_no) ? "No: $b->wwc_no<br>" : '';

            $data['WWC Registration']['a'] = ($a->wwc_exp) ? $wwc_no_after . "Exp: " . $a->wwc_exp->format(session('df')) : $wwc_no_after;
            $data['WWC Registration']['b'] = ($b->wwc_exp) ? $wwc_no_before . "Exp: " . $b->wwc_exp->format(session('df')) : $wwc_no_before;
            if ($a->wwc_verified != $b->wwc_verified) {
                $data['WWC Registration']['a'] .= ($a->wwc_verified) ? "Verified by<br>" . $a->wwcVerifiedByUser->name . '<br>' . $a->wwc_verified->format(session('df')) : '';
                $data['WWC Registration']['b'] .= ($b->wwc_verified) ? "Verified by<br>" . $b->wwcVerifiedByUser->name . '<br>' . $b->wwc_verified->format(session('df')) : '';
            }
        }
        if ($data) {
            $json = "{";
            $x = 1;
            foreach ($data as $key => $val)
                $json .= '"' . ($x ++) . '": {"field": "' . $key . '", "before": "' . $val['b'] . '", "after": "' . $val['a'] . '"}, ';
            $json = rtrim($json, ', ') . "}";
            $result['Volunteer'] = $json;
        }

        //
        // Combine categories and create final json output
        $finalJSON = '';
        if ($result) {
            $finalJSON = '{';
            $x = 1;
            foreach ($result as $category => $json)
                $finalJSON .= '"' . ($x ++) . '": {"title": "' . $category . '", "data": [' . $json . ']}, ';
            $finalJSON = rtrim($finalJSON, ', ') . "}";
        }

        // Save history record to DB
        $action = 'updated';
        $timestamp = Carbon::now()->toDateTimeString();
        if ($type == 'profile') {
            // Adjust timestamp for Profile created if User has attended events prior to User creation date ie. backdating
            //  - a cleaner way to reflect profile creation for when users + events have been imported
            $action = (PeopleHistory::where('uid', $user->id)->where('type', $type)->first()) ? 'updated' : 'created';
            if ($action == 'created')
                $timestamp = ($user->firstEvent() && $user->firstEvent()->start->lt($user->created_at)) ? $user->firstEvent()->start : $user->created_at;
        }

        DB::table('users_history')->insert([
            'uid'        => $user->id, 'action' => $action, 'type' => $type, 'data' => $finalJSON,
            'created_by' => Auth::user()->id, 'created_at' => $timestamp, 'updated_at' => $timestamp]);
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
