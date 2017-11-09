@if (empty($select))

    <option name=""></option>

@else

    @foreach ($select as $option)
        <option value="{{ $option->id }}">{{ $option->name }}</option>
    @endforeach

@endif