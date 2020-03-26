<div class="card">
    <div class="card-header">
        <h5 class="card-title">{{$widget->name}} <a href="{{url('/LaraLight/widget/'.$widget->id)}}"><i
                        class="fa fa-cogs pull-right" aria-hidden="true"></i></a></h5>
    </div>
    <div class="card-body" data-sensor-id="{{$sensor->id}}">
        <form action="{{url('/LaraLight/action/setLevel')}}" method="post">
            {{csrf_field()}}
            <input type="hidden" name="id" value="{{$widget->id}}">
            <input type="hidden" name="sensor_id" value="{{$sensor->id}}">
            <input data-provide="slider" name="level"
                   data-sensor-id="{{$sensor->id}}"
                   data-slider-id='ex1Slider'
                   type="text" data-slider-min="0"
                   data-slider-max="100" data-slider-step="1" data-slider-value="{{$ll_config->state}}"/>
        </form>
        <hr>
        <div class="text-center">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-danger laralight_btn_submit" type="submit"
                        form="laralight_close_{{$widget->id}}">Eteindre
                </button>
                <button class="btn btn-sm btn-primary mode" type="submit" form="laralight_form_{{$widget->id}}">
                    Mode {{ucfirst($ll_config->mode)}}</button>
                <button type="button" class="btn btn-sm btn-success laralight_btn_submit" type="submit"
                        form="laralight_open_{{$widget->id}}">Allumer
                </button>
            </div>
            <form class="form-mode" action="{{url('/LaraLight/mode/update')}}" method="post"
                  id="laralight_form_{{$widget->id}}">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$widget->id}}">
                <input type="hidden" name="sensor_id" value="{{$sensor->id}}">
            </form>
            <form action="{{url('/LaraLight/action/setLevel')}}" method="post"
                  id="laralight_close_{{$widget->id}}">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$widget->id}}">
                <input type="hidden" name="sensor_id" value="{{$sensor->id}}">
                <input type="hidden" name="level" value="0">
                <input type="hidden" name="force" value="true">
            </form>
            <form action="{{url('/LaraLight/action/setLevel')}}" method="post"
                  id="laralight_open_{{$widget->id}}">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$widget->id}}">
                <input type="hidden" name="sensor_id" value="{{$sensor->id}}">
                <input type="hidden" name="level" value="100">
                <input type="hidden" name="force" value="true">
            </form>
        </div>
    </div>
</div>
