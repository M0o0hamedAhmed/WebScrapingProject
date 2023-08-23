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

<script>
    $(document).ready(function () {
        $('#scrapeButton').click(function () {
            $.get('/scrape', function (response) {
                $('#message').text(response.message);
                // You can also refresh the page or update the content dynamically
            console.log(response.message)
            });
        });
    });
</script>
</body>
</html>
