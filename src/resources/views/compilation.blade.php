<?php

/** @var \App\Models\Compilations\Compilation $compilation */
/** @var \App\Models\Compilations\Video[] $videos */

$videos = $compilation->videos;
?>

@extends('layouts.app')

@section('content')

    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    @foreach($compilation->videos as $video)

                        <div class="card">
                            <div class="card-header">{{$video->title}}</div>
                            <div class="card-body">
                                <img src="{{ $video->thumbnails[App\Entity\Enums\VideoSize::STANDARD]['url'] }}" width="400" alt="">
                                {{--<iframe width="560" height="315"--}}
                                        {{--src="https://www.youtube.com/embed/{{$video->content_id}}" frameborder="0"--}}
                                        {{--allow="accelerometer; encrypted-media; gyroscope; picture-in-picture"--}}
                                        {{--allowfullscreen>--}}
                                {{--</iframe>--}}
                            </div>
                            <div class="card-description">
                                <div class="content">
                                    <span>{{!! nl2br($video->description) !!}}</span>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
