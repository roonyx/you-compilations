<?php
/** @var \App\Models\Compilations\Compilation[] $compilations */
?>

@extends('layouts.app')

@section('content')

    @include('scroll')

    <div class="container">
        <div class="title">
            <h2>About</h2>
        </div>

        <div>
            <p>
                YouCompilation will not allow you to skip videos on interesting topics. With this service you can get a collection of videos every day. Collections consist of tags. Tags displays the information you are interested in. If you forget about the collection, the notification system will send you a reminder.
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
                            <h3 class="animated fadeInUp wow animated" style="visibility: visible; animation-name: fadeInUp;">New content</h3>
                            <p class="animated fadeInDown wow animated" style="visibility: visible; animation-name: fadeInDown;">
                                Watch the latest videos. Do not be afraid to miss the video you are interested in. Service will find it and show you.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="service_block">
                            <div class="animated wow animated" style="visibility: visible; animation-name: rollIn;">
                                <span><i class="material-icons" style="font-size: 40px">fiber_new</i></span>
                            </div>
                            <h3 class="animated fadeInUp wow animated" style="visibility: visible; animation-name: fadeInUp;">Receive reminders</h3>
                            <p class="animated fadeInDown wow animated" style="visibility: visible; animation-name: fadeInDown;">
                                Every day you will receive an email notification with a link and brief information.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="service_block">
                            <div class="animated wow animated" style="visibility: visible; animation-name: rollIn;">
                                <span><i class="material-icons" style="font-size: 40px">share</i></span>
                            </div>
                            <h3 class="animated fadeInUp wow animated" style="visibility: visible; animation-name: fadeInUp;">Share with friends</h3>
                            <p class="animated fadeInDown wow animated" style="visibility: visible; animation-name: fadeInDown;">
                                You can share the collections with your friends and family by sending them a link.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="title">
                <h2>For what?</h2>
            </div>
            <p>
                Not everyone is able to devote a lot of time on YouTube. Also, we can't know about all the interesting channels and lose a lot of interesting information. Our service is a window into the world of current and popular information. A window with fresh videos that you should like, because all the information found reflects the tags entered in the settings.
            </p>
        </div>
        <div>
            <div class="title">
                <h2>What's next?</h2>
            </div>
            <p>
                After registration you need to fill in the tags in the user settings. Select tags with the help of a search engine and a smart tooltip. If the tag does not exist write them separated by commas.
            </p>
        </div>
    </div>
@endsection
