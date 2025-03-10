<?php

namespace MyApp;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use GuzzleHttp\Client;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class Routes
{
    public static function register(App $app)
    {
        // 1. Outgoing HTTP Call
        $app->get('/outgoing-http-call/test/{id}', function (Request $request, Response $response, $args) {
            $id = $args['id']; // capturing the {id} part of the URL

            // Use Guzzle to make a GET request to aws.amazon.com
            $client = new Client();

            try {
                $res = $client->request('GET', 'https://aws.amazon.com');
                $statusCode = $res->getStatusCode();
                $body = [
                    'message' => "Successfully fetched aws.amazon.com for ID: $id",
                    'statusCode' => $statusCode
                ];
            } catch (\Exception $e) {
                $body = [
                    'message' => "Error fetching aws.amazon.com",
                    'error' => $e->getMessage()
                ];
            }

            // Return response as JSON
            $response->getBody()->write(json_encode($body));
            return $response->withHeader('Content-Type', 'application/json');
        });

        // 2. AWS SDK Call (List S3 buckets)
        $app->get('/aws-sdk-call', function (Request $request, Response $response) {
            /**
             * You should configure AWS credentials in a way that
             * the SDK picks them up automatically (e.g. environment
             * variables, credentials file, or via constructor).
             *
             * For demonstration, we'll show a basic constructor approach
             * but do not commit credentials to source control in real apps!
             */
            $s3Client = new S3Client([
                'version' => 'latest',
                'region'  => 'us-east-1', // change to your region
                'credentials' => [
                    'key'    => 'YOUR_AWS_ACCESS_KEY',
                    'secret' => 'YOUR_AWS_SECRET_KEY',
                ],
            ]);

            try {
                $result = $s3Client->listBuckets();
                $buckets = $result['Buckets'];

                if (empty($buckets)) {
                    $body = ['message' => 'No buckets found.'];
                } else {
                    $bucketNames = array_map(function ($bucket) {
                        return $bucket['Name'];
                    }, $buckets);

                    $body = [
                        'message' => 'Buckets listed successfully',
                        'buckets' => $bucketNames
                    ];
                }
            } catch (AwsException $e) {
                $body = [
                    'message' => 'Error listing buckets',
                    'error' => $e->getMessage()
                ];
            }

            // Return response as JSON
            $response->getBody()->write(json_encode($body));
            return $response->withHeader('Content-Type', 'application/json');
        });
    }
}
