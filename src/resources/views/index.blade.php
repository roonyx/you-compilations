<?php
/** @var \App\Models\Compilations\Compilation[] $compilations */
?>

@extends('layouts.app')

@section('content')

    <style>
        .overlay {
            display: none;
            position: absolute;
            padding: 5px;
            width: 100%;
            height: 30%;
            top: 100%;
            left: 0;
            background-color: rgb(255, 255, 255);
            color: black;
            text-align: center;
            transition: all 200ms ease-in;
        }

        .card:hover .overlay {
            display: block;
            top: 70%;
        }

        .service_block {
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>

    @include('scroll')

    <div class="container">
        <div class="title">
            <h3>Latest compilations</h3>
        </div>
        <div class="col-md-10 ml-auto mr-auto">
            @if(!$compilations->isEmpty())
                <div class="row">
                    @foreach($compilations as $compilation)
                        <div class="col-md-4">
                            <div class="card">
                                <a href="{{ route('compilation', ['compilation' => $compilation]) . '/#scroll' }}">
                                    <img class="card-img-top"
                                         src="{!! \App\Models\Compilations\Compilation::prettyImage($compilation)['url'] !!}"
                                         alt="Card image cap">
                                </a>
                                <div class="card-body">
                                    <span>
                                        <div style="display: inline; position: relative; top: 3px">
                                            <i class="material-icons">person</i>
                                        </div>
                                        @php
                                            $authors = $compilation->authors();
                                            $authors = $authors->map(function (\App\Models\Compilations\Author $author) {
                                                return [
                                                    'url' => $author->channelLink(),
                                                    'title' => $author->name,
                                                    'img' => $author->thumbnails[\App\Entity\Enums\AvatarSize::DEFAULT]['url'],
                                                ];
                                            })->unique()->splice(0, 8);
                                        @endphp
                                        @foreach($authors as $author)
                                            <a href="{{ $author['url'] }}" target="_blank">
                                                <img class="rounded-circle img-fluid" width="20px" height="20px"
                                                     style="margin-top: -10px"
                                                     src="{{ $author['img'] }}" title="{{ $author['title'] }}">
                                            </a>
                                        @endforeach
                                    </span>
                                    @if($duration = $compilation->duration())
                                        <p class="card-text" style="margin-top: -10px">
                                        <div>
                                            <div style="display: inline; position: relative; top: 6px;">
                                                <i class="material-icons">access_time</i>
                                            </div>
                                            <div style="display: inline;">
                                                <span>{{ $duration }}</span>
                                            </div>
                                        </div>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="row justify-content-center">
                <div class="pagination-info">
                    {{ $compilations->links() }}
                </div>
            </div>
        </div>

@endsection
