/****************LaraLight JS Plugin****************/
$(function() {
    e.channel('chan-laralight')
        .listen('.Tchoblond59.LaraLight.Events.LaraLightEvent', function (e) {
            console.log('LaraLightEvent', e);
            console.log('Here we go');
            $('input[data-sensor-id='+e.sensor.id+']').slider('setValue', e.level);
        })
});
