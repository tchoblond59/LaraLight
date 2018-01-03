<table class="table table-striped">
<tr>
    <td>Début</td>
    <td>Fin</td>
    <td>Niveau lumière</td>
    <td></td>
</tr>
@foreach($periods as $period)
    <tr>
        <td>{{$period->from->format('H:i')}}</td>
        <td>{{$period->to->format('H:i')}}</td>
        <td>{{$period->light_level}}</td>
        <td></td>
    </tr>
    @endforeach
    </table>