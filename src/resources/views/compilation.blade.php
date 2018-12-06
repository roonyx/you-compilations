<?php

/** @var \App\Models\Compilations\Compilation $compilation */
/** @var \App\Models\Compilations\Video[] $videos */

$videos = $compilation->videos;
?>

@extends('layouts.app')

@section('content')

    @include('scroll')

    <div class="container">
        <div class="title">
            <h2>Compilation videos. Date: {{ $compilation->created_at->toDateString() }}</h2>
        </div>

        <div class="col-md-10 ml-auto mr-auto">
            <div class="card-columns">
                @foreach($compilation->videos as $video)
                    <div class="card">
                        <a href="https://www.youtube.com/watch?v={{ $video->content_id }}" target="_blank">
                            <img class="card-img-top"
                                 src="{{ $video->thumbnails[App\Entity\Enums\VideoSize::MEDIUM]['url'] }}"
                                 alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $video->title }}</h5>
                            <p class="card-text">{!! (parseUrlInText(str_limit(htmlspecialchars($video->description), 200))) !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
