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
            <div class="card-columns">
                @if(!$compilations->isEmpty())
                    @foreach($compilations as $compilation)
                        <a href="{{ route('compilation', ['compilation' => $compilation]) . '/#scroll' }}">
                            <div class="card">
                                <img class="card-img-top"
                                     src="{{ !empty($image = \App\Models\Compilations\Compilation::prettyImage($compilation)) ? $image['url']  : 'no' }}"
                                     alt="Card image cap">
                                <div class="overlay">
                                    Date: {{ $compilation->created_at->toDateString() }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
            <div class="row justify-content-center">
                <div class="pagination-info">
                    {{ $compilations->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
