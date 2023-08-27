<!DOCTYPE html>
<html>
<head>
    <title>Web Scraping</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
            crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"
            integrity="sha384-Rx+T1VzGupg4BHQYs2gCW9It+akI2MM/mndMCy36UVfodzcJcF0GGLxZIzObiEfa"
            crossorigin="anonymous"></script>
</head>
<body>
<div class="badge bg-primary text-wrap p-3 d-flex h1 display-1  m-2  justify-content-center">Web Scraping Example</div>
<table class="table table-dark">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">author</th>
        <th scope="col">pages Count</th>
        <th scope="col">Language</th>
        <th scope="col">Size</th>
        <th scope="col">Pdf Link</th>
    </tr>
    </thead>
    <tbody id="book-list">
    @foreach($scrapedBooks as $scrapedBook)

        <tr>
            <th scope="row">{{$scrapedBook->id}}</th>
            <td>{{$scrapedBook->title}}</td>
            <td>{{$scrapedBook->author}}</td>
            <td>{{ $scrapedBook->pages_count }}</td>
            <td>{{ $scrapedBook->lang }}</td>
            <td>{!! $scrapedBook->size ?? '<span class="text-danger">غير متاح </span>' !!}</td>
            <td> {!! is_null($scrapedBook->pdf_link) ? '<span class="text-danger">غير متاح </span>' :' <a class="btn btn-primary" href="{{$scrapedBook->pdf_link}}" role="button" target="_blank" >Download</a>'!!} </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class=" badge bg-white text-wrap p-3 d-flex h1 display-1  m-2  justify-content-center">
    <button id="load_books" type="button" class="btn btn-primary">Scraping Books</button>
</div>

<input id="pageNumber" name="pageNumber" type="hidden" value="1">

<script>
    $(document).ready(function () {

        $(document).on('click', '#load_books', (function () {
            // $.get('/scrape', function (response) {
            //     $('#message').text(response.message);
            //     // You can also refresh the page or update the content dynamically
            // });

            console.log($('#pageNumber').val())
            $('#pageNumber').val((index, value) => +value + 1)
            console.log($('#pageNumber').val())
            let bookHtml = [];
            $('#load_books').prop('disabled', true);

            $.ajax({
                url: "{{route('getMoreData')}}",
                dataType: "json",
                type: "get",
                data: {'pageNumber': $('#pageNumber').val()},
                success: function (data) {
                    console.log('start')
                    console.log(data.message)
                    data.message.forEach(function (item) {
                        var bookElement = `   <tr>
            <th scope="row">${item.id}</th>
            <td>${item.title}</td>
            <td>${item.author}</td>
            <td>${item.pages_count}</td>
            <td>${item.language}</td>
            <td>${item.size}</td>
            <td><a class="btn btn-primary" href="${item.pdf_link}" role="button" target="_blank">Download</a></td>
        </tr>`;

                        bookHtml.push(bookElement);
                        $('#load_books').prop('disabled', false);

                    });
                    $('#book-list').html(bookHtml.join(''))
                    console.log('end')
                },
                error: function (e) {
                }
            });
        }));

    });
</script>
</body>
</html>
