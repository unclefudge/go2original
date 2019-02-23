<style>
    /* Container needed to position the overlay. Adjust the width as needed */
    .image-container {
        position: relative;
        width: 100%;
        cursor: pointer;
        float: left;
    }

    /* The overlay effect (full height and width) - lays on top of the container and over the image */
    .image-overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        transition: .3s ease;
        background: rgb(0, 0, 0);
        background: rgba(0, 0, 0, 0.3); /* Black see-through */
        border-radius: 10%;
    }

    /* When you mouse over the container, fade in the overlay icon*/
    .image-container:hover .image-overlay {
        opacity: 1;
    }

    .image-edit:hover {
        color: #FF0000;
        background: rgb(0, 0, 0);
        background: rgba(0, 0, 0, 0.3); /* Black see-through */
    }

    .image-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        right: 50%;
    }
</style>

<div class="m-portlet">
    <div class="m-portlet__body" style="padding: 10px">
        <div class="row justify-content-md-center">
            <div class="col-12 text-center">
                {{-- Image --}}
                <div class="image-container" id="bg_image">
                    <img src="{{ $event->background_path }}" class="img-fluid">
                    <div class="image-overlay">
                        <a href="#" class="image-edit" title="Edit" id="avatar-edit">
                            <img class="image-icon" src="/img/icon-edit-avatar.png" height="35px" style="margin: -18px 0 0 -18px">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Background Modal --}}
<div class="modal fade" id="modal_background" tabindex="-1" role="dialog" aria-labelledby="Avatar Edit" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {!! Form::model($event, ['action' => ['Event\EventController@updatePhoto', $event->id]]) !!}
            {!! Form::hidden('previous_photo', $event->background, ['id' => 'photo_name']) !!}
            <div class="modal-header" style="background: #32c5d2">
                <h5 class="modal-title text-white" id="ModalLabel">{{ $event->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Slim Photo --}}
            <div class="slim {!! fieldHasError('photo', $errors) !!}" data-push=true data-download="true" data-save-initial-image="true" data-label="<i class='fa fa-image fa-4x'></i><br>Drop your image here" style="min-height: 200px">
                @if ($event->background)
                    <img src="{{ $event->background_path }}" alt=""/>
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
