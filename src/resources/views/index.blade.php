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
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 text-center">
                <div class="about-title">
                    <h2>About Us</h2>
                    <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium <br>voluptatum
                        deleniti atque corrupti quos dolores e</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12 wow fadeInUp delay-02s animated"
                 style="visibility: visible; animation-name: fadeInUp;">
                <div class="img">
                    <i class="fa fa-refresh"></i>
                </div>
                <h3 class="abt-hd">Our process</h3>
                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum
                    deleniti atque corrupti quos dolores</p>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 wow fadeInUp delay-04s animated"
                 style="visibility: visible; animation-name: fadeInUp;">
                <div class="img">
                    <i class="fa fa-eye"></i>
                </div>
                <h3 class="abt-hd">Our Vision</h3>
                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum
                    deleniti atque corrupti quos dolores</p>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 wow fadeInUp delay-06s animated"
                 style="visibility: visible; animation-name: fadeInUp;">
                <div class="img">
                    <i class="fa fa-cogs"></i>
                </div>
                <h3 class="abt-hd">Our Approach</h3>
                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum
                    deleniti atque corrupti quos dolores</p>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 wow fadeInUp delay-08s animated"
                 style="visibility: visible; animation-name: fadeInUp;">
                <div class="img">
                    <i class="fa fa-dot-circle-o"></i>
                </div>
                <h3 class="abt-hd">Our Objective</h3>
                <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum
                    deleniti atque corrupti quos dolores</p>
            </div>
        </div>
    </div>

    @if(!$compilations->isEmpty())
        <div class="container">
            <div class="title">
                <h2>Latest compilations</h2>
            </div>
            <div class="col-md-10 ml-auto mr-auto">
                <div class="card-columns">
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
                </div>
            </div>
        </div>
    @endif

@endsection
