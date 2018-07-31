<?php

namespace App\Exceptions;

use Exception;
use Config;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldReport($e)) {
            $this->sendErrorToSlack($e);
        }
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return parent::render($request, $e);
    }

    public function sendErrorToSlack(Exception $e) {
        $url = Config::get('services.slack.exception_webhook');
        if ($url)
        {
            $parsedUrl = parse_url($url);

            $this->client = new \GuzzleHttp\Client([
                'base_uri' => $parsedUrl['scheme'].'://'.$parsedUrl['host'],
            ]);

            $payload = json_encode(
            [
                'text'       => get_class($e).': '.$e->getMessage() . ' (' . $e->getCode() . ')',
                'username'   => 'Exception site d\'inté',
                'icon_emoji'   => ':rotating_light:',
                'attachments' => [
                    [
                        'title' => 'File',
                        'text' => $e->getFile().':'.$e->getLine(),
                        'color' => '#d80012',
                    ],
                    [
                        'title' => 'Trace',
                        'text' => $e->getTraceAsString(),
                        'color' => '#d80012',
                    ],
                ],
            ]);
            $response = $this->client->post($parsedUrl['path'], ['body' => $payload]);
            return $response;
        }
    }
}
