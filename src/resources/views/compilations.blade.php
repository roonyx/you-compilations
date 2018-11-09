<?php

/** @var \App\Models\Compilations\Compilation[] $compilations */
/** @var \App\Models\Tag[] $tags */

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
                ajax: {
                    url: function (params) {
                        return '{{ route('api_tags_search') }}' + '/' + (params.term ? params.term : '');
                    },
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    data: function (term) {
                        return {
                            term: term.term
                        };
                    },
                    processResults: function (data) {
                        var myResults = [];
                        $.each(data, function (index, item) {
                            myResults.push({
                                'id': item[0].id,
                                'text': item[0].text
                            });
                        });
                        return {
                            results: myResults
                        };
                    }
                }
            });
        });



    </script>

    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">

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
                                <input type="submit" class="btn btn-primary btn-sm" value="Save">
                            </form>
                        </div>
                    </div>

                    <div class="section">
                        @foreach($compilations as $compilation)

                            <div class="card">
                                <div class="card-header">
                                    <a href="{{ route('compilation', ['compilation' => $compilation]) }}">{{$compilation->created_at->toDateTimeString()}}</a>
                                </div>
                                <div class="card-body">
                                    @foreach($compilation->videos as $video)
                                        <div class="video">
                                            <a href="https://www.youtube.com/embed/{{$video->content_id}}">{{$video->title}}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        @endforeach

                        <center>
                            {{ $compilations->links() }}
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
