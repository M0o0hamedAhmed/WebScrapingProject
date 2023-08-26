<!DOCTYPE html>
<html>
<head>
    <title>Web Scraping</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<h1>Web Scraping Example</h1>
<button id="scrapeButton">Scrape Data</button>
<div id="message"></div>
<h1>Scraped Books</h1>
<ul id="book-list">
    @foreach($scrapedBooks as $scrapedBook)
        <li>{{$scrapedBook->title}}</li>
    @endforeach
</ul>

<button id="load_books">Load Books</button>
<input id="pageNumber" name="pageNumber" type="number" value="1">

<script>
    $(document).ready(function () {

        $(document).on('click','#load_books',(function () {
            // $.get('/scrape', function (response) {
            //     $('#message').text(response.message);
            //     // You can also refresh the page or update the content dynamically
            // });

            console.log($('#pageNumber').val())
            $('#pageNumber').val((index, value) => +value + 1)
            console.log($('#pageNumber').val())
            $.ajax({
                url: "{{route('getMoreData')}}",
                dataType: "json",
                type: "get",
                data: {'pageNumber' : $('#pageNumber').val()},
                success: function (data) {
                    console.log(data)
                },
                error: function (e) {
                }
            });
        }));

    });
</script>
</body>
</html>
