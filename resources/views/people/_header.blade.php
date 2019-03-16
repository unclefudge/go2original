{{-- Member bar --}}
<div class="member-bar {{ (!$user->status) ? 'member-inactive' : '' }}">
    <!--<i class="iicon-user-member-bar hidden-xs-down"></i>-->

    <style>
        /* Container needed to position the overlay. Adjust the width as needed */
        .avatar-container {
            position: relative;
            width: 90px;

            margin: -20px 20px -20px -90px;  /* margin: 0 20px 0 -80px; */
            cursor: pointer;
            float: left;
        }

        /* The overlay effect (full height and width) - lays on top of the container and over the image */
        .avatar-overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            height: 100%;
            width: 96px;
            opacity: 0;
            transition: .3s ease;
            background: rgb(0, 0, 0);
            background: rgba(0, 0, 0, 0.3); /* Black see-through */
            /*border-radius: 0%;*/
        }

        /* When you mouse over the container, fade in the overlay icon*/
        .avatar-container:hover .avatar-overlay {
            opacity: 1;
        }
        .avatar-image {
            height: 96px;
            border-radius: 0%;
        }

        .avatar-edit:hover {
            color: #FF0000;
            background: rgb(0, 0, 0);
            background: rgba(0, 0, 0, 0.3); /* Black see-through */
        }

        .avatar-icon {
            position: absolute;
            bottom: 1px;
            right: 1px;
        }

        @media screen and (max-width: 825px) {
            .avatar-container {
                display: block;
                float: none;
                margin: 0px;
                width: auto;
                text-align: center;
            }
            .avatar-overlay {
                left: 50%;
                right: 50%;
                width: auto;
            }
            .avatar-icon {
                position: absolute;
                top:60px;
                left: 12px
            }
        }
    </style>
    <div class="member-name">
        {{-- Avatar --}}
        <div class="avatar-container" id="avatar">
            <img class="avatar-image" src="{{ $user->photoSmPath }}?<?=rand(1, 32000)?>" alt="Avatar">
            <div class="avatar-overlay">
                <a href="#" class="avatar-edit" title="Edit" id="avatar-edit">
                    <img  class="avatar-icon" src="/img/icon-edit-avatar.png" height="35px">
                </a>
            </div>
        </div>
        <div class="member-fullname">{{ $user->firstname }} {{ $user->lastname }}</div>
        <span class="member-number">{{ $user->type }}</span>
        <span class="member-split">&nbsp;|&nbsp;</span>
        <span class="dropdown" style="text-transform: none">
            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" style="padding: 1px 1px 1px 8px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ ($user->status) ? 'Active' : 'Inactive' }}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ ($user->status) ? '#' : "/people/$user->id/status/1" }}">Active</a>
                <a class="dropdown-item" href="{{ (!$user->status) ? '#' : "/people/$user->id/status/0" }}">Inactive</a>
                @if (!$user->status)
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" id="but_del_person">Delete</a>
                @endif
            </div>
        </span>
    </div>

    <?php
    $active_profile = $active_settings = $active_attendance = '';
    list($first, $rest) = explode('/', Request::path(), 2);
    if (!ctype_digit($rest)) {
        list($uid, $rest) = explode('/', $rest, 2);
        $active_settings = (preg_match('/^settings*/', $rest)) ? 'active' : '';
        $active_attendance = (preg_match('/^attendance*/', $rest)) ? 'active' : '';
    } else
        $active_profile = 'active';
    ?>

    <ul class="member-bar-menu">
        <li class="member-bar-item {{ $active_profile }}"><i class="iicon-profile"></i><a class="member-bar-link" href="/people/{{ $user->id }}" title="Profile">PROFILE</a></li>
        <li class="member-bar-item "><i class="iicon-document"></i><a class="member-bar-link" href="/people/{{ $user->id }}/activity" title="Activity">Activity</a></li>

        <li class="member-bar-item "><i class="iicon-lock"></i><a class="member-bar-link" href="/people/{{ $user->id }}" title="Security">SECURITY</a></li>
    </ul>
</div>


{{-- Show Avatar Modal --}}
<div class="modal fade" id="modal_avatar_show" tabindex="-1" role="dialog" aria-labelledby="Avatar Show" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #32c5d2">
                <h5 class="modal-title text-white" id="ModalLabel">{{ $user->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- Photo --}}
            <img class="img-fluid" width=100%" src="{{ $user->photo_path }}">

            <div class="text-center">
                <button type="button" class="btn btn-secondary" style="border: 0; background: #FFF" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Avatar Modal --}}
<div class="modal fade" id="modal_avatar_edit" tabindex="-1" role="dialog" aria-labelledby="Avatar Edit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::model($user, ['action' => ['People\PeopleController@updatePhoto', $user->id]]) !!}
            {!! Form::hidden('previous_photo', $user->photo, ['id' => 'photo_name']) !!}
            <div class="modal-header" style="background: #32c5d2">
                <h5 class="modal-title text-white" id="ModalLabel">{{ $user->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Slim Photo --}}
            <div class="slim {!! fieldHasError('photo', $errors) !!}" data-ratio="1:1" data-push=true data-download="true" data-size="400,400" data-label="<i class='fa fa-camera fa-4x'></i><br>Click and smile!">
                @if ($user->photo)
                    <img src="{{ $user->photo_path }}?<?=rand(1, 32000)?>" alt=""/>
                @endif
                <input type="file" name="photo"/>
            </div>

            <div class="modal-footer" style="background-color: #F7F7F7; padding: 20px">
                <button type="button" class="btn btn-secondary" style="border: 0; background: #F7F7F7" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
