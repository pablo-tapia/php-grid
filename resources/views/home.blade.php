@extends('layouts.main')

@section('content')
    <h1>Titulo</h1>
    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed rhoncus posuere luctus.
        Duis sit amet ornare enim. Suspendisse eget pulvinar nisl, at varius lacus. Integer a nisi
        vel orci efficitur tristique congue vitae massa. Aenean dignissim congue sem id commodo. Cras auctor
        augue non lorem scelerisque volutpat. Aenean eget ante elit. Etiam ut est at ipsum consectetur
        pellentesque sit amet at est. Quisque volutpat ante vitae rhoncus porta. Donec vulputate lobortis
        mollis. Praesent lobortis, quam sed lacinia hendrerit, dui libero euismod ipsum, eget pretium neque
        sem nec purus. Mauris tincidunt varius nisl, gravida tristique lorem finibus sed. Mauris auctor sed felis
        vitae aliquam. Pellentesque nec ullamcorper turpis, quis gravida urna. Proin vel massa tincidunt, dapibus
        mauris in, sagittis lorem. In rhoncus sit amet metus nec pellentesque.
    </p>
    <form id="search" method="get" action="/search">
        {{ csrf_field() }}
        <div>
            <label for="topic">Que tema te interesa?</label>
            <select id="topic" name="topic" required>
                <option value="">Selecciona un tema</option>
            </select>
        </div>
        <div>
            <label for="applicant">Que tipo de participante eres?</label>
            <select id="applicant" name="applicant" required>
                <option value="">Selecciona tipo de participante</option>
            </select>
        </div>
        <div>
            <label for="status">Estatus</label>
            <select id="status" name="status" required>
                <option value="">Selecciona estatus</option>
            </select>
        </div>
        <div>
            <label for="community">Cobertura</label>
            <select id="community" name="community">
                <option value="">Selecciona cobertura</option>
            </select>
        </div>
        <div>
            <label for="range">Ambito</label>
            <select id="range" name="range">
                <option value="">Selecciona ambito</option>
            </select>
        </div>
        <div>
            <input type="submit" name="buscar" value="Buscar" />
        </div>
    </form>
    <div id="container">
        <div class="slider">
            SLIDER GOES HERE
        </div>
    </div>
@endsection

