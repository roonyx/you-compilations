<?php
/** @var \App\Models\Compilations\Compilation[]|\Illuminate\Support\Collection $compilations */
/** @var \App\Models\Tag[] $tags */
/** @var bool $isStandingInQueue */

/** @var \App\Models\User $user */
$user = \Auth::user();
?>

@extends('layouts.app')

@section('content')

    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet"/>
    <script src="{{ asset('js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('.tags-multiple-select').select2({
                placeholder: "Choose tags...",
                tags: true,
                tokenSeparators: [','],
            });
        });
    </script>

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
        <div class="col-md-10 ml-auto mr-auto">
            <div class="card">
                <div class="card-header">Enter your tags:</div>
                <div class="card-body">
                    <form action="{{ route('tags_store') }}" method="post" name="create_tags">
                        @csrf
                        <select class="tags-multiple-select" style="width: 80%" name="tags[]" multiple="multiple">
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}" selected>{{ $tag->name }}</option>
                            @endforeach
                        </select>
                        <input type="submit" class="btn btn-danger btn-sm" value="Save">
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                {{--<div class="card-columns">--}}
                <div class="row">
                    @foreach($compilations as $compilation)

                            <div class="col-md-4">
                                <div class="card">
                                    <img class="card-img-top"
                                         src="{{  $compilation->videos[0]->thumbnails[\App\Entity\Enums\VideoSize::MEDIUM]['url'] }}"
                                         alt="Card image cap">
                                    <div class="card-body">
                                        <p class="card-text">Date: {{ $compilation->created_at->toDateString() }}</p>
                                    </div>
                                </div>
                            </div>

                        {{--<a href="{{ route('compilation', ['compilation' => $compilation]) . '/#scroll' }}">--}}
                            {{--<div class="card">--}}
                                {{--@isset($compilation->videos[0])--}}
                                    {{--<img class="card-img-top"--}}
                                         {{--src="{{ $compilation->videos[0]->thumbnails[\App\Entity\Enums\VideoSize::MEDIUM]['url'] }}"--}}
                                         {{--alt="Card image cap">--}}
                                {{--@endisset--}}
                                {{--<div class="overlay">--}}
                                    {{--Date: {{ $compilation->created_at->toDateString() }}--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</a>--}}
                    @endforeach
                </div>
                {{--</div>--}}
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
