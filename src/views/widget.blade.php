<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{$widget->name}} <a href="{{url('/LaraLight/widget/'.$widget->id)}}"><i class="fa fa-cogs pull-right" aria-hidden="true"></i></a></h3>
    </div>
    <div class="panel-body" data-sensor-id="{{$sensor->id}}">
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
            <form class="form-mode" action="{{url('/LaraLight/mode/update')}}" method="post">
                {{csrf_field()}}
                <input type="hidden" name="id" value="{{$widget->id}}">
                <input type="hidden" name="sensor_id" value="{{$sensor->id}}">
                <button class="btn btn-primary mode" type="submit">Mode {{ucfirst($ll_config->mode)}}</button>
            </form>
        </div>
    </div>
</div>
