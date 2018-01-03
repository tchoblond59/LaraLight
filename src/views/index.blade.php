@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-md-12">
                <ul class="nav nav-tabs">
                    <li class="{{ $state=='config' ? 'active' : '' }}"><a href="{{url('LaraLight/widget/'.$widget->id)}}">LaraLight</a></li>
                    <li class="{{ $state=='periods' ? 'active' : '' }}"><a href="{{url('LaraLight/widget/period/'.$widget->id)}}">Periode</a></li>
                    <li class="{{ $state=='assign_period' ? 'active' : '' }}"><a href="{{url('LaraLight/widget/periodConfig/'.$widget->id)}}">Assignation période</a></li>
                    <li role="presentation" class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            Créer <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="#" data-toggle="modal" data-target="#modalLumiere">Lumière</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#modalPeriod">Période</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#modalAssignPeriod">Assigner Période</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                @include($sub_view)
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalLumiere">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Créer une lumière</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{url('/LaraLight/config/create')}}" id="modalLumiereForm">
                        {{csrf_field()}}
                        <input type="hidden" name="relay_id" value="{{$sensor->id}}">
                        <div class="form-group">
                            <label for="mode">Mode par défaut:</label>
                            <select class="form-control" name="mode">
                                <option value="manuel">Manuel</option>
                                <option value="semiauto">Semi Auto</option>
                                <option value="auto">Automatique</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tempo lumière (en minutes):</label>
                            <input class="form-control" name="delay" type="number" min="0"></input>
                        </div>
                        <div class="form-group">
                            <label>Lux trigger:</label>
                            <input class="form-control" name="lux_limit" type="number" min="0"></input>
                        </div>
                        <div class="form-group">
                            <label for="pir_sensor">Capteur de mouvement:</label>
                            <select class="form-control" name="pir_sensor">
                                <option value="">Aucun</option>
                                @foreach(\App\Sensor::all() as $sensor)
                                    <option value="{{$sensor->id}}">{{$sensor->name}}</option>
                                @endforeach()
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="lux_sensor">Capteur de lumière:</label>
                            <select class="form-control" name="lux_sensor">
                                <option value="">Aucun</option>
                                @foreach(\App\Sensor::all() as $sensor)
                                    <option value="{{$sensor->id}}">{{$sensor->name}}</option>
                                @endforeach()
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="modalLumiereForm">Ajouter</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalPeriod">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Créer une période</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{url('/LaraLight/config/createPeriod')}}" id="modalPeriodForm">
                        {{csrf_field()}}
                        <input type="hidden" name="relay_id" value="{{$sensor->id}}">
                        <div class="form-group">
                            <label>Début:</label>
                            <input class="form-control" name="from" type="time" value="00:00"></input>
                        </div>
                        <div class="form-group">
                            <label>Fin:</label>
                            <input class="form-control" name="to" type="time" value="23:59"></input>
                        </div>
                        <div class="form-group">
                            <label>Niveau de lumière:</label>
                            <input class="form-control" name="light_level" type="number" value="100"></input>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="modalPeriodForm">Ajouter</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalAssignPeriod">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Assigner une période</h4>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{url('/LaraLight/config/assignPeriod')}}" id="modalAssignPeriodForm">
                        {{csrf_field()}}
                        <input type="hidden" name="relay_id" value="{{$sensor->id}}">
                        <div class="form-group">
                            <label>Période:</label>
                            <select class="form-control" name="period">
                                @foreach(\Tchoblond59\LaraLight\Models\Period::all() as $period)
                                    <option value="{{$period->id}}">{{$period->light_level}}% de {{$period->from->format('H:i')}} à {{$period->to->format('H:i')}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="modalAssignPeriodForm">Ajouter</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection