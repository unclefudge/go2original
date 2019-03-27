<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        {{-- Member bar --}}
        <div class="mbar">
            <div class="mbar-name">
                {{-- Avatar --}}
                <div class="avatar-container" id="avatar">
                    <img class="avatar-image" src="{{ $user->photoSmPath }}?<?=rand(1, 32000)?>" alt="Avatar">
                    <div class="avatar-overlay">
                        <a href="#" class="avatar-edit" title="Edit" id="avatar-edit"><img class="avatar-icon" src="/img/icon-edit-avatar.png" height="35px"></a>
                    </div>
                </div>
                <div class="mbar-fullname">
                    {{ $user->firstname }} {{ $user->lastname }} <a href="/people" class="btn btn-secondary mbar-btn" style="margin-left: 15px" data-toggle="modal" data-target="#modal_personal"> Edit</a>
                </div>
                <span class="mbar-type">{{ $user->type }}</span>
                @if ($user->phone)
                    <span class="mbar-split">&nbsp;|&nbsp;</span>
                    <span class="mbar-link">{!! ($user->phone) ? "<a href='tel:'".preg_replace("/[^0-9]/", "", $user->phone)."> $user->phone </a>" : '' !!}</span>
                @endif
                @if ($user->email)
                    <span class="mbar-split">&nbsp;|&nbsp;</span>
                    <span class="mbar-link"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></span>
                @endif
            </div>
        </div>
    </div>

    <div class="kt-subheader__toolbar">
        <div class="kt-subheader__wrapper">
            <div>
                <div class="dropdown dropdown-inline">
                    <a href="#" class="btn btn-light kt-subheader__btn-options dropdown-toggle" style="padding: .5rem 1rem" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ ($user->status) ? 'Active' : 'Inactive' }}</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        @if (!$user->status)
                            <a class="dropdown-item" href="{{ ($user->status) ? '#' : "/people/$user->id/status/1" }}"><i class="fa fa-eye" style="width: 25px"></i> Make active</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-uid="{{ $user->id }}" data-name="{{ $user->name }}" id="but_del_person"><i class="fa fa-trash-alt" style="width: 25px"></i> Delete</a>
                        @else
                            <a class="dropdown-item" href="{{ (!$user->status) ? '#' : "/people/$user->id/status/0" }}"><i class="fa fa-eye-slash" style="width: 25px"></i> Make inactive</a>
                        @endif
                    </div>
                </div>
                <div class="dropdown dropdown-inline">
                    <a href="#" class="btn kt-subheader__btn-secondary kt-subheader__btn-options" style="padding: 1.4rem 1rem;" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" style="font-size: 18px !important; color: #fff"></i> </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#"><i class="fa fa-lock" style="width: 25px"></i> Permissions</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-user-friends" style="width: 25px"></i> Merge this profile</a>
                    </div>
                </div>
            </div>
            <div style="padding: 10px; opacity: .8">
                <span style="color: #fff">
                    {{ $user->gender }} {{ ($user->gender && $user->dob) ? '|' : '' }} {{ ($user->dob) ? "$user->age years" : '' }}
                </span>
            </div>
        </div>
    </div>
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


