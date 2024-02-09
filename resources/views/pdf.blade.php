<h2>{{ $record->full_name }}</h2>
<ul>
    @foreach ($record->events as $event)
        <li><b>{{ $event->type_event }}</b> {{ $event->start }} - {{ $event->end }}</li>
    @endforeach
</ul>
