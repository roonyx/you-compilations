<?php
/** @var \App\Models\Compilations\Compilation $compilation */

/** @var \App\Models\Compilations\Video $videos */
$videos = $compilation->videos;
?>

<a href="{{ @route('compilation', ['compilation' => $compilation]) }}">
Your compilation is collected! Date: {{ $compilation->created_at->toDateString() }}
</a>

<ul>
    @foreach($videos as $video)
        <li><span>{{ $video->title }}</span></li>;
    @endforeach
</ul>

© 2018, made with favorite by <a href="https://roonyx.tech">RoonyxTeam</a>.
