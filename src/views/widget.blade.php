<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">{{$widget->name}} <a href="{{url('/LaraLight/widget/'.$widget->id)}}"><i class="fa fa-cogs pull-right" aria-hidden="true"></i></a></h3>
    </div>
    <div class="panel-body">
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
    </div>
</div>
<script>
    $(function() {
        $('input[data-provide]').on('slideStop', function () {
            var form = $(this).closest('form');
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                }
            })
        })
    });
</script>