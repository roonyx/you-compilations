<?php
/** @var \App\Models\Compilations\Compilation[]|\Illuminate\Support\Collection $compilations */
/** @var \App\Models\Tag[] $tags */
/** @var bool $isStandingInQueue */

/** @var \App\Models\User $user */
$user = \Auth::user();
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
    </style>

    @include('scroll')

    <div class="container">
        <div class="title">
            <h2>Your compilations</h2>
        </div>
        <div class="col-md-10 ml-auto mr-auto">
            @if($compilations->isEmpty())
                <div class="alert alert-info">
                    <div class="container">
                        <div class="alert-icon">
                            <i class="material-icons">info_outline</i>
                        </div>
                        @if($isStandingInQueue)
                            <b>Info alert:</b> Your first compilation is already creating!
                        @else
                            <b>Info alert:</b> In the user settings, specify the tags you are interested in.
                        @endif
                    </div>
                </div>
            @else
                <div class="card-columns">
                    @foreach($compilations as $compilation)
                        <a href="{{ route('compilation', ['compilation' => $compilation]) . '/#scroll' }}">
                            <div class="card">
                                @isset($compilation->videos[0])
                                    <img class="card-img-top"
                                         src="{{ $compilation->videos[0]->thumbnails[\App\Entity\Enums\VideoSize::MEDIUM]['url'] }}"
                                         alt="Card image cap">
                                @endisset
                                <div class="overlay">
                                    Date: {{ $compilation->created_at->toDateString() }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="row justify-content-center">
                    <div class="pagination-info">
                        {{ $compilations->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        if ((new URL(window.location.href)).searchParams.get("page")) {
            window.location.hash = '#scroll';
            $.scrollTop($('#scroll').offsetTop);
        }
    </script>

@endsection
