@extends('layouts.app')

@section('content')
    <div class="container">
        <form id="events" method="post" action="/admin/save">
            {{ csrf_field() }}
            @if (isset($data[0]->id))
                <input type="hidden" name="id" value="{{ $data[0]->id }}"/>
            @else
                <input type="hidden" name="id" value=""/>
            @endif
            <div>
                <label for="name">Nombre</label>
                @if (isset($data[0]->event))
                    <input type="text" name="name" value="{{ $data[0]->event }}" size="100" maxlength="255" required/>
                @else
                    <input type="text" name="name" value="" size="100" maxlength="255" required/>
                @endif
            </div>
            <div>
                <label for="topic">Que tema te interesa?</label>
                <select id="topic" name="topic" required>
                    <option value="">Selecciona un tema</option>
                    @isset($data[0]->topic_id)
                        <option value="{{ $data[0]->topic_id }}" selected>{{ $data[0]->topic_name }}</option>
                    @endisset
                </select>
            </div>
            <div>
                <label for="applicant">Que tipo de participante eres?</label>
                <select id="applicant" name="applicant" required>
                    <option value="">Selecciona tipo de participante</option>
                    @isset($data[0]->participant_id)
                        <option value="{{ $data[0]->participant_id }}" selected>{{ $data[0]->participant_name }}</option>
                    @endisset
                </select>
            </div>
            <div>
                <label for="sponsor">Convocante</label>
                <textarea name="sponsor" required>
                    @isset($data[0]->sponsor)
                        {{ $data[0]->sponsor }}
                    @endisset
                </textarea>
            </div>
            <div>
                <label for="description">Convoca</label>
                <textarea name="description" required>
                    @isset($data[0]->sponsor_description)
                        {{ $data[0]->sponsor_description }}
                    @endisset
                </textarea>
            </div>
            <div>
                <label for="price">Premio</label>
                @if (isset($data[0]->price))
                    <input type="text" name="price" value="{{ $data[0]->price }}" required size="100"/>
                @else
                    <input type="text" name="price" value="" required size="100"/>
                @endif
            </div>
            <div>
                <label for="link">Pagina Web</label>
                @if (isset($data[0]->web_page))
                    <input type="url" name="link" size="100" value="{{ $data[0]->web_page }}" required />
                @else
                    <input type="url" name="link" size="100" value="" required />
                @endif
            </div>
            <div>
                <label for="date">Fecha</label>
                @if (isset($data[0]->end_date))
                    <input id="datepicker" type="text" name="date" value="{{ $data[0]->end_date }}" size="50" required/>
                @else
                    <input id="datepicker" type="text" name="date" value="" size="50" required/>
                @endif
            </div>
            <div>
                <label for="range">Ambito</label>
                <select id="range" name="range" required>
                    <option value="">Selecciona ambito</option>
                    @isset($data[0]->range_id)
                        <option value="{{ $data[0]->range_id }}" selected>{{ $data[0]->range_name }}</option>
                    @endisset
                </select>
            </div>
            <div>
                <label for="community">Cobertura</label>
                <select id="community" name="community" required>
                    <option value="">Selecciona cobertura</option>
                    @isset($data[0]->community_id)
                        <option value="{{ $data[0]->community_id }}" selected>{{ $data[0]->community_name }}</option>
                    @endisset
                </select>
            </div>
            <div>
                <label for="application">Postulacion</label>
                <select id="application" name="application" required>
                    <option value="">Selecciona postulacion</option>
                    @isset($data[0]->application_id)
                        <option value="{{ $data[0]->application_id }}" selected>{{ $data[0]->application_name }}</option>
                    @endisset
                </select>
            </div>
            <div>
                <label for="status">Estatus</label>
                <select id="status" name="status" required>
                    <option value="">Selecciona estatus</option>
                    @isset($data[0]->status_id)
                        <option value="{{ $data[0]->status_id }}" selected>{{ $data[0]->status_name }}</option>
                    @endisset
                </select>
            </div>
            <div>
                <input type="submit" name="submit" value="Agregar" />
                <input type="button" name="cancel" value="Cancelar" onclick="window.location.replace('/admin'); return false;"/>
            </div>
        </form>
    </div>
@endsection