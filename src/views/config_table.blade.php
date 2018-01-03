<table class="table table-striped">
<tr>
    <td>Nom</td>
    <td>Mode</td>
    <td>Capteur présence</td>
    <td>Capteur lumière</td>
    <td></td>
</tr>
@foreach($laralight_configs as $config)
    <tr>
        <td>{{$config->sensor->name}}</td>
        <td>{{$config->mode}}</td>
        @if($config->pir_sensor)
            <td>{{$config->pir_sensor->name}}</td>
        @else
            <td>Aucun</td>
        @endif
        @if($config->lux_sensor)
            <td>{{$config->lux_sensor->name}}</td>
        @else
            <td>Aucun</td>
        @endif
        <td></td>
    </tr>
    @endforeach
    </table>