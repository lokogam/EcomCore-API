<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Aws\S3\S3Client;
use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;

class SetupAwsResources extends Command
{
    protected $signature = 'aws:setup';
    protected $description = 'Setup AWS resources (S3 bucket and SQS queue)';

    public function handle()
    {
        $this->info('AWS Configuration:');
        $this->info('AWS Endpoint: ' . env('AWS_ENDPOINT'));
        $this->info('AWS Region: ' . env('AWS_DEFAULT_REGION'));
        $this->info('AWS Bucket: ' . env('AWS_BUCKET'));
        $this->info('SQS Queue: ' . env('SQS_QUEUE', 'default'));

        $awsConfig = [
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
            'http'    => [
                'verify' => false
            ]
        ];

        try {
            $this->info('Initializing S3 Client...');
            $s3Client = new S3Client($awsConfig);

            $this->info('Attempting to create S3 bucket...');
            $s3Client->createBucket([
                'Bucket' => env('AWS_BUCKET')
            ]);
            $this->info('S3 bucket created successfully');

            $this->info('Initializing SQS Client...');
            $sqsClient = new SqsClient($awsConfig);

            $this->info('Attempting to create SQS queue...');
            $result = $sqsClient->createQueue([
                'QueueName' => env('SQS_QUEUE', 'default')
            ]);
            $this->info('SQS queue created successfully');
        } catch (AwsException $e) {
            $this->error('AWS Error: ' . $e->getAwsErrorMessage());
            $this->error('Error Code: ' . $e->getAwsErrorCode());
            $this->error('Request ID: ' . $e->getAwsRequestId());
            $this->error('Error Type: ' . $e->getAwsErrorType());
            $this->error('Full Exception: ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->error('General Error: ' . $e->getMessage());
            $this->error('Error trace: ' . $e->getTraceAsString());
        }
    }
}
