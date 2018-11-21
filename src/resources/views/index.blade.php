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
            <h2>About</h2>
        </div>

        <div>
            <p>
                YouCompilation непозволит пропустить видео на интересующую вас тематику. С помощью данного сервиса вы сможете получить подборки видео каждый день. Подборки состоят из тегов, теги в тоже время отображает интересующую вас информацию. А также, вы не сможете забыть о подборках, ибо система нотификации пришлет вам на почту напоминание.
            </p>
        </div>

        <div class="col-md-10 ml-auto mr-auto">
            <div class="service_area">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="service_block">
                            <div class="animated wow animated" style="visibility: visible; animation-name: rollIn;">
                                <span><i class="material-icons" style="font-size: 40px">open_in_new</i></span>
                            </div>
                            <h3 class="animated fadeInUp wow animated" style="visibility: visible; animation-name: fadeInUp;">Просматривать свежий контент</h3>
                            <p class="animated fadeInDown wow animated" style="visibility: visible; animation-name: fadeInDown;">
                                Поиск осуществляется по новому и популярному контенту. Не бойтесь пропустить интересующее вас видео, сервис найдет его и покажем вам.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="service_block">
                            <div class="animated wow animated" style="visibility: visible; animation-name: rollIn;">
                                <span><i class="material-icons" style="font-size: 40px">fiber_new</i></span>
                            </div>
                            <h3 class="animated fadeInUp wow animated" style="visibility: visible; animation-name: fadeInUp;">Получать напоминания</h3>
                            <p class="animated fadeInDown wow animated" style="visibility: visible; animation-name: fadeInDown;">
                                Каждый день вы будете получать оповещение на почту с ссылкой и краткой информацией.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="service_block">
                            <div class="animated wow animated" style="visibility: visible; animation-name: rollIn;">
                                <span><i class="material-icons" style="font-size: 40px">share</i></span>
                            </div>
                            <h3 class="animated fadeInUp wow animated" style="visibility: visible; animation-name: fadeInUp;">Делиться с друзьями</h3>
                            <p class="animated fadeInDown wow animated" style="visibility: visible; animation-name: fadeInDown;">
                                Вы сможете делиться подборками с друзьями и близкими отправив им ссылку.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <span>

            </span>
        </div>

        <div>
            <div class="title">
                <h2>For what?</h2>
            </div>
            <p>
                Не всем удаётся уделять большое количества времени на YouTube, также мы не можем знать обо всех интересных каналах и теряем большое количество интересной информации. Наш сервис, это окно в мир актуальной и популярной информации, окно с свежими видео которые вам должны понравиться, ведь вся найденная информация отражает введенные в настройках теги.
            </p>
        </div>
        <div>
            <div class="title">
                <h2>What's next?</h2>
            </div>
            <p>
                После регистрации вам нужно заполнить теги в настройках пользователя. Выберите теги с помощью поисковика и умного подсказчика, либо если тега не существует напишите их через запятые.
            </p>
        </div>
    </div>

    @if(!$compilations->isEmpty())
        <div class="container">
            <div class="title">
                <h3>Latest compilations</h3>
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
