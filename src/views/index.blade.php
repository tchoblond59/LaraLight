@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h1>Configuration du capteur {{$widget->sensor->name}}</h1>
        </div>
        <div class="row">
            <div class="col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="post"
                      action="{{url('LaraLight/widget/configuration/'.$widget->id)}}">
                    {{csrf_field()}}
                    <fieldset>

                        <!-- Form Name -->
                        <legend></legend>

                        <!-- Select Basic -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="mode">Mode</label>
                            <div class="col-md-4">
                                <select id="mode" name="mode" class="form-control">
                                    @if($ll_config->mode == \Tchoblond59\LaraLight\Models\LaraLightMode::Manual)
                                        <option value="{{\Tchoblond59\LaraLight\Models\LaraLightMode::Manual}}"
                                                selected>Manuel
                                        </option>
                                    @else
                                        <option value="{{\Tchoblond59\LaraLight\Models\LaraLightMode::Manual}}">Manuel
                                        </option>
                                    @endif
                                    @if($ll_config->mode == \Tchoblond59\LaraLight\Models\LaraLightMode::Auto)
                                        <option value="{{\Tchoblond59\LaraLight\Models\LaraLightMode::Auto}}" selected>
                                            Automatique
                                        </option>
                                    @else
                                        <option value="{{\Tchoblond59\LaraLight\Models\LaraLightMode::Auto}}">
                                            Automatique
                                        </option>
                                    @endif
                                    @if($ll_config->mode == \Tchoblond59\LaraLight\Models\LaraLightMode::TimeOnly)
                                        <option value="{{\Tchoblond59\LaraLight\Models\LaraLightMode::TimeOnly}}"
                                                selected>Basé sur l'heure de la journée
                                        </option>
                                    @else
                                        <option value="{{\Tchoblond59\LaraLight\Models\LaraLightMode::TimeOnly}}">Basé
                                            sur l'heure de la journée
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="lux_limit">Lux Limit</label>
                            <div class="col-md-4">
                                <input id="lux_limit" name="lux_limit" placeholder="250" class="form-control input-md"
                                       type="number" value="{{$ll_config->lux_limit}}">
                                <span class="help-block">Limite de lux à partir de laquelle la lumière doit s'allumer</span>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="delay">Délai</label>
                            <div class="col-md-4">
                                <input id="delay" name="delay" placeholder="1" class="form-control input-md"
                                       type="number" value="{{$ll_config->delay}}">
                                <span class="help-block">Délai en minutes avant lequel la lumière s'eteint</span>
                            </div>
                        </div>

                        <!-- Select Basic -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="lux_sensor">Capteur de lumière</label>
                            <div class="col-md-4">
                                <select id="lux_sensor" name="lux_sensor" class="form-control">
                                    @foreach(\App\Sensor::all() as $ze_sensor)
                                        @if($ze_sensor->id == $ll_config->light_sensor_id)
                                            <option value="{{$ze_sensor->id}}" selected>{{$ze_sensor->name}}</option>
                                        @else
                                            <option value="{{$ze_sensor->id}}">{{$ze_sensor->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Select Basic -->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="pir_sensor">Capteur de mouvement</label>
                            <div class="col-md-4">
                                <select id="pir_sensor" name="pir_sensor" class="form-control">
                                    @foreach(\App\Sensor::all() as $ze_sensor)
                                        @if($ze_sensor->id == $ll_config->pir_sensor_id)
                                            <option value="{{$ze_sensor->id}}" selected>{{$ze_sensor->name}}</option>
                                        @else
                                            <option value="{{$ze_sensor->id}}">{{$ze_sensor->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="level_min">Niveau minimum</label>
                            <div class="col-md-4">
                                <input id="level_min" name="level_min" placeholder="1" class="form-control input-md"
                                       type="number" value="{{$ll_config->level_min}}">
                                <span class="help-block">Ex: Si le niveau minimum est de 10 alors lorsque la commande sera en dessous 10% la lumière sera a 10%</span>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="level_max">Niveau maximum</label>
                            <div class="col-md-4">
                                <input id="level_max" name="level_max" placeholder="1" class="form-control input-md"
                                       type="number" value="{{$ll_config->level_max}}">
                                <span class="help-block">Ex: Si le niveau maximum est de 90 alors lorsque la commande sera au dessus de 90% la lumière sera a 90%</span>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="dimmer_delay">Délai transition</label>
                            <div class="col-md-4">
                                <input id="dimmer_delay" name="dimmer_delay" placeholder="1"
                                       class="form-control input-md" type="number" value="{{$ll_config->dimmer_delay}}">
                                <span class="help-block">Temps pour passer de 0 à 100% en secondes</span>
                            </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="enable_delay">Fonction délai</label>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="enable_delay"
                                           id="enable_delay" {{$ll_config->enable_delay ? "checked" : ""}}>
                                    <label class="form-check-label" for="enable_delay">Activer ou désactiver</label>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-7">
                            <button class="btn btn-secondary float-right">Valider</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

        <hr>
        <div class="row">
            <div class="col-md-12">
                <h1>Configuration des périodes</h1>
                <ul class="nav nav-tabs">
                    <li class="{{ $state=='config' ? 'active' : '' }} nav-link"><a
                                href="{{url('LaraLight/widget/'.$widget->id)}}">LaraLight</a></li>
                    <li class="{{ $state=='periods' ? 'active' : '' }} nav-link"><a
                                href="{{url('LaraLight/widget/period/'.$widget->id)}}">Periode</a></li>
                    <li class="{{ $state=='periods_configs' ? 'active' : '' }} nav-link"><a
                                href="{{url('LaraLight/widget/periodConfig/'.$widget->id)}}">Assignation période</a>
                    </li>
                    <li role="presentation" class="dropdown nav-link">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="true" aria-expanded="false">
                            Créer <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" data-toggle="modal"
                               data-target="#modalLumiere">Lumière</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalPeriod">Période</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalAssignPeriod">Assigner
                                Période</a>
                            <a class="dropdown-item" href="#" data-toggle="modal"
                               data-target="#modalCommand">Commandes</a>
                        </div>
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
    </div>
    <div class="modal fade" tabindex="-1" role="dialog" id="modalLumiere">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Créer une lumière</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
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
                    <h4 class="modal-title">Créer une période</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
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
                    <h4 class="modal-title">Assigner une période</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{url('/LaraLight/config/assignPeriod')}}" id="modalAssignPeriodForm">
                        {{csrf_field()}}
                        <input type="hidden" name="relay_id" value="{{$sensor->id}}">
                        <div class="form-group">
                            <label>Période:</label>
                            <select class="form-control" name="period">
                                @foreach(\Tchoblond59\LaraLight\Models\Period::all() as $period)
                                    <option value="{{$period->id}}">{{$period->light_level}}%
                                        de {{$period->from->format('H:i')}} à {{$period->to->format('H:i')}}</option>
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
    <div class="modal fade" tabindex="-1" role="dialog" id="modalCommand">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Créer une commande</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{url('/LaraLight/config/createCommand')}}" id="formModalCommand">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label>Nom de la commande:</label>
                            <input class="form-control" name="name" type="text" required>
                        </div>
                        <div class="form-group">
                            <label>Type:</label>
                            <select class="form-control" name="type">
                                <option value="SWITCH_OFF_ALL">Tout éteindre</option>
                                <option value="SWITCH_ON_ALL">Tout allumer</option>
                                <option value="SET_LEVEL">Régler sur la valeur</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Sensor:</label>
                            <select class="form-control" name="sensor">
                                @foreach (\Tchoblond59\LaraLight\Models\LaraLight::where('classname', '\Tchoblond59\LaraLight\Models\LaraLight')->get() as $light)
                                    <option value="{{$light->id}}">{{$light->name}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group">
                            <label>Valeur:</label>
                            <input class="form-control" name="value" type="number" value="0">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="formModalCommand">Ajouter</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection