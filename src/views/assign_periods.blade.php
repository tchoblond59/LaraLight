<table class="table table-striped">
<tr>
    <td>Début</td>
    <td>Fin</td>
    <td>Niveau lumière</td>
    <td></td>
</tr>
@foreach($periods_configs as $period_config)
    <tr>
        <td>{{$period_config->period->from->format('H:i')}}</td>
        <td>{{$period_config->period->to->format('H:i')}}</td>
        <td>{{$period_config->period->light_level}}</td>
        <td></td>
    </tr>
@endforeach
    </table>