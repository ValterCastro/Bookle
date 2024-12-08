<?php

namespace App\Http\Controllers;
//require '../../vendor/autoload.php';

use Illuminate\Http\Request;
//use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
//use App\Http\Controllers\Exception;
use Solarium\Client;
use Solarium\Core\Client\Adapter\Curl;
use Symfony\Component\EventDispatcher\EventDispatcher;


class SearchController
{
    public function search(Request $request)
    {


        // $request->validate([
        //     'title' => 'required|string',
        // ]);

        Log::info("hey");


        // $title = $request->input('title');

        $client = new Client();

        $datasetSlug = 'pypiahmad/goodreads-book-reviews1';
        $url = "https://www.kaggle.com/api/v1/datasets/download/$datasetSlug";

        $headers = [
            'Authorization' => 'Bearer ' . env('KAGGLE_KEY')

        ];

        Log::info('Entering search method');
        Log::info('Request URL and Headers', [
            'url' => $url,
            'headers' => $headers,
        ]);


        try {
            $response = $client->get($url, [
                'headers' => $headers,
                'stream' => true,
            ]);


            Log::info('Kaggle API Response', [
                'status' => $response->getStatusCode(),
                'body' => $response->getBody()->getContents(),
            ]);

            $filePath = storage_path('app/goodreads_books.zip');

            $fp = fopen($filePath, 'w');

            while (!$response->getBody()->eof()) {
                fwrite($fp, $response->getBody()->read(1024));
            }

            fclose($fp);

            return response()->json(['message' => 'Dataset Download Successfully.', 'file' => $filePath]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {

            Log::error('Guzzle Request Exception', [
                'message' => $e->getMessage(),
                'request' => [
                    'url' => $url,
                    'headers' => $headers,
                ],
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);
            return response()->json(['error' => 'Request failed. ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showFirstTitle(Request $request)
    {

        $filePath = storage_path('app/goodreads_books.json');

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return response()->json(['error' => 'Unable to open file.'], 500);
        }


        $books = [];
        $lineCount = 0; // Count the number of lines read

        // Read up to a certain number of lines (e.g., 5 lines)
        while (($line = fgets($handle)) !== false && $lineCount < 10) {
            // Decode each line
            $data = json_decode($line, true);

            // Check if data is an array and contains the title
            if (is_array($data) && isset($data['title'])) {
                $books[] = $data;
                $lineCount++;
            }
        }

        fclose($handle);

        // Decode the first line
        if (count($books) > 0) {
            // Store titles in the session
            session(['books' => $books]);

            // Redirect to the search results view
            return redirect()->route('search_results');
        } else {
            return response()->json(['error' => 'No books found.'], 404);
        }
    }

    public function searchSolr(Request $request)
    {
        try {

            $searchTerm = $request->input('search');

            $eventDispatcher = new EventDispatcher();

            // Create the Adapter
            $adapter = new Curl();
            // Configure Solarium client
            $config = [
                'endpoint' => [
                    'localhost' => [
                        'host' => 'solr_pri', // Change to your Solr host
                        'port' => '8983',      // Change to your Solr port
                        'path' => '/',     // Change if Solr is under a different path
                        'core' => 'books',   // Replace with your Solr core name
                    ],
                ],
            ];

            $client = new Client($adapter, $eventDispatcher, $config);

            // Create a query instance
            $query = $client->createSelect();

            $query->addParam("defType", 'edismax');

            $query->addParam("qf", array('description', 'review_text', 'title'));


            //"books politics religion crimes impact social evolution"
            $query->setQuery($searchTerm);

            // Specify the fields to return in the response (similar to 'fl' in Solr)
            $query->setFields(fields: array('isbn', 'average_rating', 'ratings_count', 'title', 'genres', 'description', 'score', 'author_name', 'review_text'));

            $query->setStart(0); // Start at the first document

            // Set the number of rows to return
            $query->setRows(10);


            $resultSet = $client->select($query);

            echo "Results found: " . $resultSet->getNumFound() . "\n";

            foreach ($resultSet as $document) {
                echo "Document ID: " . $document->id . "\n";

                // Output other fields dynamically
                foreach ($document as $field => $value) {
                    // Check if the value is an array
                    if (is_array($value)) {
                        // If it's an array, print its contents using print_r or json_encode
                        echo "$field: " . json_encode($value) . "\n";
                    } else {
                        // If it's not an array, just print the value normally
                        echo "$field: $value\n";
                    }
                }
                echo "\n";
            }

            session(['books' => $resultSet]);

            return redirect()->route('solr_search_results_non_boosted');

        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        
    }
}
