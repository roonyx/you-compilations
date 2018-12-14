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
                                 src="{!! $video->prettyImage()['url'] !!}"
                                 alt="Card image cap">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">{{ $video->title }}</h5>
                            <div>
                                @if(!empty($views = $video->viewsFormatted()))
                                    <p class="card-text">{{ $views }} views</p>
                                @endif
                                @if(!empty($interval = $video->interval()))
                                    <p class="card-text">{{ $interval }} ago</p>
                                @endif
                            </div>
                            <div style="margin-top: 10px">
                                @if(!is_null($video->author))
                                    <a href="{{ $video->author->channelLink() }}" class="card-text" target="_blank">
                                        <div>
                                            <div style="display: inline; position: relative; top: 4px;">
                                                <img class="rounded-circle img-fluid" width="20px" height="20px" style="margin-top: -10px"
                                                     src="{{ $video->author->thumbnails[\App\Entity\Enums\AvatarSize::DEFAULT]['url'] }}">
                                            </div>
                                            <div style="display: inline;">
                                                {{ $video->author->name }}
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
