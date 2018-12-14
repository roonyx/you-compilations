<?php
/** @var \App\Models\Compilations\Compilation[]|\Illuminate\Support\Collection $compilations */
/** @var \App\Models\Compilations\Tag[] $tags */
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
        .spinner {
            animation: rotator 1.4s linear infinite;
        }

        @keyframes rotator {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(270deg);
            }
        }

        .path {
            stroke-dasharray: 187;
            stroke-dashoffset: 0;
            transform-origin: center;
            animation: dash 1.4s ease-in-out infinite, colors 5.6s ease-in-out infinite;
        }

        @keyframes colors {
            0% {
                stroke: #4285F4;
            }
            25% {
                stroke: #DE3E35;
            }
            50% {
                stroke: #F7C223;
            }
            75% {
                stroke: #1B9A59;
            }
            100% {
                stroke: #4285F4;
            }
        }

        @keyframes dash {
            0% {
                stroke-dashoffset: 187;
            }
            50% {
                stroke-dashoffset: 46.75;
                transform: rotate(135deg);
            }
            100% {
                stroke-dashoffset: 187;
                transform: rotate(450deg);
            }
        }
    </style>

    @verbatim
        <script>
            var isEmpty =  <?= $compilations->isEmpty() ? 'true' : 'false' ?>;
            var isInQueue = <?= (string)$isStandingInQueue ? 'true' : 'false' ?>;

            if (isEmpty && isInQueue) {
                var timeOut = setInterval(function() {
                    $.ajax({
                        type: 'GET',
                        url: '<?= route('compilations-exists', ['id' => Auth::authenticate()->id])?>',
                    }).done(function (result) {
                        result = JSON.parse(result);
                        if (result.exists) {
                            console.log('reload');
                            window.location.reload(false);
                        }
                    }).fail(function () {
                        console.log('fail');
                    });
                }, 1000);
            }

        </script>
    @endverbatim

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
                @if(!$isStandingInQueue)
                    <div class="alert alert-info">
                        <div class="container">
                            <div class="alert-icon">
                                <i class="material-icons">info_outline</i>
                            </div>
                            <b>Info alert:</b> Enter your tags.
                        </div>
                    </div>
                @else
                    <center>
                        <div class="container spinner-container">
                            <svg class="spinner" width="65px" height="65px" viewBox="0 0 66 66"
                                 xmlns="http://www.w3.org/2000/svg">
                                <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33"
                                        r="30"></circle>
                            </svg>
                        </div>
                    </center>
                @endif
            @else
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
