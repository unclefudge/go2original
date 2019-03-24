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
            $event_name = $attend->event->name;
            $instance_name = $attend->instance->name;
            $instance_startLocal =  $attend->instance->startLocal;
            $attend_time = ($attend->method == 'check-in') ? $attend->inLocal->format('g:i a') : $instance_startLocal->format('g:i a');
            $array['datetime'] = Timezone::convertFromUTC($attend->instance->start, session('tz'));
            $array['icon'] = "<i class='fa fa-map-marker-alt' style='color: #32c5d2'></i>";
            $array['title'] = "Checked in to $event_name";
            $array['title'] .= ($instance_name && $instance_name != $event_name) ? " <small> - $instance_name</small>" : '';
            $array['date'] = $instance_startLocal->format('F jS, Y');
            $array['data'] = "<div class='row'><div class='col-3'>Event</div><div class='col'>$instance_name</div><div class='col-2'><a href='/event/". $attend->event->id."/attendance/".$instance_startLocal->format('Y-m-d')."'><i class='fa fa-external-link-alt icon-edit'></i></a></div></div>";
            $array['data'] .= "<div class='row'><div class='col-3'>Time</div><div class='col'>$attend_time</div></div>";
            $array['data'] .= "<div class='row'><div class='col-3'>Method</div><div class='col'>".ucfirst($attend->method)."</div></div>";
            $activity[] = (object) $array;
        }

        // History
        foreach ($user->history as $history) {
            $array = [];
            $array['datetime'] = Timezone::convertFromUTC($history->created_at, session('tz'));
            if ($history->type == 'profile')
                $array['icon'] = ($history->action == 'created') ? "<i class='fa fa-user-plus' style='color:#5867dd'></i>" : "<i class='fa fa-user-edit' style='color:#5867dd'></i>";
            if ($history->type == 'household')
                $array['icon'] = "<i class='fa fa-user-friends' style='color:#5867dd'></i>";
            $array['title'] = ucfirst($history->type) . ' ' . ucfirst($history->action) . " &nbsp;<small> by " . $history->updateByUser->name . "</small>";
            $array['date'] = $history->created_at->timezone(session('tz'))->format('F jS, Y');
            $array['data'] = '';

            if ($history->data) {
                $json = json_decode($history->data);
                //dd($json);
                foreach ($json as $category) {
                    $array['data'] .= "<h5 style='font-weight:400'>" . strtoupper($category->title) . "</h5><div class='row' style='padding:5px; border-bottom: 1px solid #ccc; font-size: 14px'><div class='col-3'></div><div class='col-4'>Before</div><div class='col-4'>After</div></div>";
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
                        <div class='card'>
                           <div class='card-header' id='heading$x'>
                             <div class='card-title collapsed' data-toggle='collapse' data-target='#collapse$x' aria-expanded='true' aria-controls='collapse$x'>$act->icon $act->title</div>
                           </div>
                           <div id='collapse$x' class='collapse' aria-labelledby='heading$x' data-parent='#activity'>
                             <div class='card-body'>$act->data</div>
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
