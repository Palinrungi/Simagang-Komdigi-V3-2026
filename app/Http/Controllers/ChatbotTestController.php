<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatbotTestController extends Controller
{
    public function ask(Request $request)
    {
        return new StreamedResponse(function () {
            echo "data: " . json_encode(['type' => 'token', 'data' => 'H']) . "\n\n";
            ob_flush(); flush(); sleep(1);
            echo "data: " . json_encode(['type' => 'token', 'data' => 'a']) . "\n\n";
            ob_flush(); flush(); sleep(1);
            echo "data: " . json_encode(['type' => 'token', 'data' => 'l']) . "\n\n";
            ob_flush(); flush(); sleep(1);
            echo "data: " . json_encode(['type' => 'token', 'data' => 'o']) . "\n\n";
            ob_flush(); flush(); sleep(1);
            echo "data: [DONE]\n\n";
            ob_flush(); flush();
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type'  => 'text/event-stream',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
