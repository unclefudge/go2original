<div class="m-portlet">
    <div class="m-portlet__body">
        {{-- Rego Form Info --}}
        <div class="row" style="padding-bottom: 10px">
            <div class="col-10">
                <h4>Registration Form</h4>
            </div>
            <div class="col-2">
                @if ($event->status)
                    <a href="#" class="pull-right" data-toggle="modal" data-target="#modal_personal">Edit</a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="alert m-alert m-alert--default" role="alert">
                    This is what your first time students will fill out the first time they enter your system. You'll notice the headers "1st Check-In", "2nd Check-In", "3rd Check-In", etc. We developed this system to allow you to collect different data across multiple check-ins if you choose. This
                    was meant to speed up the registration process by allowing you to collect only the most important data you want quickly the first day, and collect the rest on a different day. But of course, you can collect everything at once if you want to. It's up to you!
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                @if ($event->status)
                    <button type="submit" class="btn btn-primary">Save</button>
                @endif
            </div>
        </div>
    </div>
</div>

