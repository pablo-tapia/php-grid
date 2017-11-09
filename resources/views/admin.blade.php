@extends('layouts.app')

@section('content')
    <div class="container">
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
        <p>
            Agregar nuevo premio: <a href="{{ route('add') }}">Agregar</a>
        </p>
        @if (session('status'))
            <div class="alert-success alert">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert-danger alert">
                {{ session('error') }}
            </div>
        @endif
        <div id="jsGridAdmin" style="margin: 0 auto;"></div>
    </div>
@endsection