<?php

namespace App\Http\Controllers\People;

use DB;
use Auth;
use Validator;
use App\User;
use Camroncade\Timezone\Facades\Timezone;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityController extends Controller {

    /**
     * Get Activity (ajax)
     */
    public function getActivity()
    {
        //$user = User::find(3);
        //$offset = 0;
        $user = User::findOrFail(request('uid'));
        $offset = request('offset');

        $activity = [];


        // Attendance
        foreach ($user->attendance as $attend) {
            $array = [];
            $array['datetime'] = Timezone::convertFromUTC($attend->instance->start, session('tz'));
            $array['icon'] = "<i class='fa fa-map-marker-alt' style='color: #32c5d2'></i>";
            $array['title'] = "Checked in to " . $attend->instance->event->name;
            $array['title'] .= ($attend->instance->name && $attend->instance->name != $attend->instance->event->name) ? ' <small> - ' . $attend->instance->name . '</small>' : '';
            $array['date'] = $attend->instance->start->timezone(session('tz'))->format('F jS, Y');
            $array['data'] = "<div class='row'><div class='col-3'>Event:</div><div class='col'>" . $attend->instance->name . "</div></div>";
            $array['data'] .= "<div class='row'><div class='col-3'>Time:</div><div class='col'>" . $attend->instance->start->timezone(session('tz'))->format('g:i a') . "</div></div>";
            $array['data'] .= "<div class='row'><div class='col-3'>Method:</div><div class='col'>$attend->method</div></div>";
            $activity[] = (object) $array;
        }

        // History
        foreach ($user->history as $history) {
            $array = [];
            $array['datetime'] = Timezone::convertFromUTC($history->created_at, session('tz'));
            $array['icon'] = ($history->action == 'created') ? "<i class='fa fa-user-plus' style='color:#5867dd'></i>" : "<i class='fa fa-user-edit' style='color:#5867dd'></i>";
            $array['title'] = "Profile " . ucfirst($history->action) . " by " . $history->updateByUser->name;
            $array['date'] = $history->created_at->timezone(session('tz'))->format('F jS, Y');
            $array['data'] = '';

            if ($history->data) {
                $json = json_decode($history->data);
                //dd($json);
                foreach ($json as $category) {
                    $array['data'] .= "<h5>".ucwords($category->title)."</h5><div class='row' style='padding:5px; border-bottom: 1px solid #ccc; font-size: 14px'><div class='col-3'></div><div class='col-4'>Before</div><div class='col-4'>After</div></div>";
                    foreach ($category->data as $row) {
                        foreach ($row as $field)
                            $array['data'] .= "<div class='row' style='padding:5px'><div class='col-3'>$field->field</div><div class='col-4' style='color: #999'>$field->before</div><div class='col-4'>$field->after</div></div>";
                        $array['data'] .= '<br>';
                    }
                }
                $activity[] = (object) $array;
            }
        }

        // Sort Newest to Oldest date
        usort($activity, function ($a, $b) {
            return strtotime($b->datetime) - strtotime($a->datetime);
        });

        $output = '';
        if (count($activity)) {
            $x = 1;
            $records = 20;
            if ($offset < count($activity))
                foreach ($activity as $act) {
                    if ($x > $offset && $x <= ($offset + $records)) {
                        $output .= "
            <div class='m-accordion__item'>
                <div class='m-accordion__item-head collapsed'  role='tab' id='m_accordion_item_" . $x . "_head' data-toggle='collapse' href='#m_accordion_item_" . $x . "_body' aria-expanded=' false'>
                    <span class='m-accordion__item-icon'>$act->icon</span>
                    <span class='m-accordion__item-title'>
                        <div style='font-weight: 500'>$act->title</div>
                        <div><small>$act->date</small> </div>
                    </span>
                    <span class='m-accordion__item-mode'></span>
                </div>

                <div class='m-accordion__item-body collapse' id='m_accordion_item_" . $x . "_body' role='tabpanel' aria-labelledby='m_accordion_item_" . $x . "_head' data-parent='#activity'>
                    <div class='m-accordion__item-content'>
                        <p>$act->data</p>
                    </div>
                </div>
            </div>";
                    }
                    $x ++;
                    if ($x > ($offset + $records))
                        break;
                }

            if (count($activity) >= $x)
                $output .= '
            <div id="remove-row">
                 <button id="btn-more" data-offset="' . ($x - 1) . '" class="btn btn-secondary btn-block"> Load More</button>
            </div>';
        } else {
            $output = 'No past activity<br><br><br><br><br><br><br><br>';
        }

        return $output;
    }
}
