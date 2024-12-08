<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Style for the book list */
        .book-list {
            list-style: none; /* Remove bullet points */
            padding: 0;
            margin: 0;
        }

        .book-item {
            margin-bottom: 20px; /* Space between books */
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd; /* Separator between items */
        }

        .book-item h3 {
            font-size: 18px;
            margin: 0;
        }

        .book-item a {
            color: #1a0dab; /* Google-like link color */
            text-decoration: none;
        }

        .book-item a:hover {
            text-decoration: underline; /* Underline on hover */
        }

        .book-meta {
            color: #70757a; /* Subtle gray for metadata */
            font-size: 14px;
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
                            <a href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
    <div>
        @if (count($books) > 0)
            <ul class="book-list">
                @foreach ($books as $book)
                    <li class="book-item">
                        <!-- Book title -->
                        <h3><a href="#">{{ $book['title'] }}</a></h3>
                    </li>
                @endforeach
            </ul>
        @else
            <p>No books found for your query.</p>
        @endif
    </div>
</body>

</html>
