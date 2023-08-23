<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ScrapingController extends Controller
{
    public function index()
    {
        return view('scraping');
    }

    public function scrape()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.kotobati.com/section/%D8%B1%D9%88%D8%A7%D9%8A%D8%A7%D8%AA');

        $content = $response->getContent();
        $crawler = new Crawler($content);

        $books = [];

        $crawler->filter('.book-box')->each(function (Crawler $node) use ($client, $books) {
            $title = $node->filter('div > h3')->text();
            $author = $node->filter('div > p')->text();
            $href = $node->filter('div > a')->attr('href');
            $linkDetails = 'https://www.kotobati.com/' . $href;
//             get pages
            $response = $client->request('GET', $linkDetails);
            $content = $response->getContent();
            $crawler = new Crawler($content);
            $pagesCount = $crawler->filter('.book-table-info >li')->eq(0)->filter('p')->eq(1)->text();
            $lang = $crawler->filter('.book-table-info >li')->eq(1)->filter('p')->eq(1)->text();
            $size = $crawler->filter('.book-table-info >li')->eq(2)->filter('p')->eq(1)->text();
//            $lang = $crawler->filter('.book-table-info >li')->eq(3)->filter('p')->eq(1)->text();
//            $lang = $crawler->filter('.book-table-info >li')->eq(4)->filter('p')->eq(1)->text();
                    dd( $size );

            $pdfLink = $node->filter('a.book-link')->attr('href');
            $books[] = [
                'title' => $title,
                'author' => $author,
                'pages_count' => $pagesCount,
                'lang' => $lang,
                'size' => $size,
                'pdf_link' => $pdfLink,
            ];
        });

        DB::table('scraped_books')->insert($books);

        return response()->json(['message' => $crawler]);
    }
}
