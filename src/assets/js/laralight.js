/****************LaraLight JS Plugin****************/
$(function() {
    e.channel('chan-laralight')
        .listen('.Tchoblond59.LaraLight.Events.LaraLightEvent', function (e) {
            console.log('LaraLightEvent', e);
            $('input[data-sensor-id='+e.sensor.id+']').slider('setValue', e.level);
        })
});
