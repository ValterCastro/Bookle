<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>

    <style>
        .book-title,
        .book-description {
            height: 3.5vw;

            display: -webkit-box;

            -webkit-box-orient: vertical;

            -webkit-line-clamp: 2;

            overflow: hidden;

            text-overflow: ellipsis;

            max-height: 6vw;

        }

        .book-description {            
            overflow: auto;
        }
    </style>

</head>

<body>
    <div style="padding:1%;">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div style="display:flex;align-items:center;">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>


                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active mx-3">
                            <a href="{{route('home')}}">Home <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div>
        @if(count($books) > 0)
            <div style="display: flex;text-align:center;flex-wrap:wrap;">
                @foreach($books as $book)
                    <div style="width:20%;margin:1vw;padding:2vw;">
                        <p class="book-title">{{ $book['title'] }}</p>
                        <img style="height:10vw;" src="{{$book['image_url']}}" alt="Image Description">
                        <p style="margin:0;">Nº ratings:{{$book['ratings_count']}}</p>
                        <div class="container mt-1">
                            <?php        for ($i = 1; $i <= 5; $i++): ?>
                            <i
                                class="<?php            echo $i <= intval(floatval($book['average_rating'])) ? 'fas fa-star text-warning' : 'far fa-star'; ?>"></i>
                            <?php        endfor; ?>
                        </div>
                        <p class="book-description">{{$book['description']}}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>

</html>