/****************LaraLight JS Plugin****************/
$(function() {
    e.channel('chan-laralight')
        .listen('.Tchoblond59.LaraLight.Events.LaraLightEvent', function (e) {
            console.log('LaraLightEvent', e);
            $('input[data-sensor-id='+e.sensor.id+']').slider('setValue', e.level);
            $('div[data-sensor-id='+e.sensor.id+'] button.mode').text('Mode '+capitalize(e.config.mode))
        })

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
    });

    $('form.form-mode').submit(function (e) {
        form = $(this);
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            dataType: 'json',
            success: function (data) {
                console.log(data);
            }
        });
        e.preventDefault();
    })
});

function capitalize(s)
{
    return s[0].toUpperCase() + s.slice(1);
}
/*****************************************************/