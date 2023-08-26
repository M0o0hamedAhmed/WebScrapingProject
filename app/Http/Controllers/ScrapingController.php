<?php

namespace App\Http\Controllers;

use App\Models\ScrapedBook;
use http\Env\Request;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ScrapingController extends Controller
{
    public function index()
    {
        $this->scrape();
        $scrapedBooks = ScrapedBook::query()->get();
        return view('scraping',compact('scrapedBooks'));
    }

    public function scrape()
    {
        set_time_limit(300);
        $page = Request()->get('pageNumber',1);
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.kotobati.com/section/%D8%B1%D9%88%D8%A7%D9%8A%D8%A7%D8%AA?'.http_build_query(['page'=>$page]));

        $content = $response->getContent();
        $crawler = new Crawler($content);

        $books = [];
        $base = 'https://www.kotobati.com/';

        $crawler->filter('.book-box')->each(function (Crawler $node) use ($client, $base, &$books) {
            $book = [];
            $book['title'] = $node->filter('div > h3')->text();
            $book['author'] = $node->filter('div > p')->text();
            $href = $node->filter('div > a')->attr('href');

            $linkDetails = $base . $href;
//             get pages
            $response = $client->request('GET', $linkDetails);
            $content = $response->getContent();
            $crawler = new Crawler($content);

            try {

                $book['pages_count'] = $crawler->filter('.book-table-info >li')->eq(0)->filter('p')->eq(1)->text('null');
            } catch (\Exception $e) {
                $book['pages_count'] = null;
            }
            try {
                $book['pages_count'] = is_numeric($book['pages_count']) ? (int)$book['pages_count'] : null;

            } catch (\Exception $e) {
                $book['pages_count'] = null;
            }
            try {
                $book['lang'] = $crawler->filter('.book-table-info >li')->eq(1)->filter('p')->eq(1)->text('null');

            } catch (\Exception $e) {
                $book['lang'] = null;
            }
            try {
                $book['size'] = $crawler->filter('.book-table-info >li')->eq(2)->filter('p')->eq(1)->text('null');
            } catch (\Exception  $e) {
                $book['size'] = null;
            }
            try {
                $book['pdf_link'] = $base . $crawler->filter('.detail-box >div >div')->eq(1)->filter('a')->eq(0)->attr('href');
            } catch (\Exception $e) {
                $book['pdf_link'] = null;
            }
            $books[] = $book;
            ScrapedBook::query()->updateOrCreate(['title' => $book['title']], $book);
        });
//        dd($books);
        $scrapedBooks = ScrapedBook::query()->get();


        return response()->json(['message' => $scrapedBooks]);
    }
}
