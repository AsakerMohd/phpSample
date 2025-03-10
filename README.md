## PHP Sample App
Currently supports two APIs:

1. GET /outgoing-http-call/test/{id}: Makes an HTTP call to aws.amazon.com
2. GET /aws-sdk-call: Makes AWS SDK ListBuckets call to S3

### Install:

Make sure you have PHP 8 and Composer installed. 

1. Run `composer install` to install depdencies
2. Run `php -S localhost:8080 -t public` to get server up and running.

Please follow https://opentelemetry.io/docs/zero-code/php/ for Opentelemetry Auto Instrumenation.