<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    // Cadang Penyelesaian AI untuk Tiket
    public function suggestSolution(Request $request)
    {
        $promptText = $request->input('prompt');

        if (empty($promptText)) {
            return response()->json(['reply' => 'Problem description or message cannot be empty.']);
        }

        $systemPrompt = "You are RESOLV AI, a professional, fast, and straight-to-the-point IT support specialist.\n"
                      . "RESPONSE FORMAT RULES (VERY IMPORTANT):\n"
                      . "1. Strictly DO NOT use Markdown symbols like **, *, #, or ---.\n"
                      . "2. Provide concise, compact, and clear answers.\n"
                      . "3. Use numbers (1, 2, 3) or regular dashes (-) only for point lists.\n"
                      . "4. ENSURE YOU PROVIDE 1 BLANK LINE (DOUBLE NEWLINE) between each point or solution step so the answer is spaced out and easy to read.\n"
                      . "5. Directly provide the brief root cause and solution steps without long introductions.\n\n"
                      . "Complaint / Problem: " . $promptText;

        try {
            $endpointPrimary = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=' . trim($this->apiKey);

            $response = Http::timeout(20)
                ->withoutVerifying()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($endpointPrimary, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $systemPrompt]
                            ]
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No suggestion received from AI.';
                
                $cleanReply = preg_replace('/[\*\#\_]/', '', $reply);
                $cleanReply = str_replace('---', '', $cleanReply);

                return response()->json(['reply' => trim($cleanReply)]);
            }

            // Mesej mesra jika API gagal / sibuk (Status bukan 200 OK)
            return response()->json([
                'reply' => 'Our AI system is currently experiencing high traffic or temporary disruptions. Please try resending your message shortly.'
            ]);

        } catch (\Exception $e) {
            // Tangkap sebarang ralat cURL / Timeout / Server Error dan tukar kepada mesej mesra
            return response()->json([
                'reply' => 'Sorry, the system is unable to connect to the AI server at the moment. Please try again in a few moments.'
            ]);
        }
    }

    // Chatbot AI
    public function chat(Request $request)
    {
        $promptText = $request->input('prompt', '');
        $file = $request->file('file');

        if (empty($promptText) && !$file) {
            return response()->json(['reply' => 'Please enter a message or attach a file.']);
        }

        $parts = [];

        if ($file && str_contains($file->getClientMimeType(), 'image')) {
            $parts[] = [
                'inline_data' => [
                    'mime_type' => $file->getClientMimeType(),
                    'data' => base64_encode(file_get_contents($file->getRealPath()))
                ]
            ];
        } elseif ($file) {
            if (in_array($file->getClientOriginalExtension(), ['txt', 'log', 'json', 'csv'])) {
                $fileContent = file_get_contents($file->getRealPath());
                $promptText .= "\n\n[File Content (" . $file->getClientOriginalName() . ")]:\n" . $fileContent;
            } else {
                $promptText .= "\n\n[Attached File Received]: " . $file->getClientOriginalName();
            }
        }

        $systemPrompt = "You are RESOLV AI, a professional, fast, and straight-to-the-point IT support specialist.\n"
                      . "RESPONSE FORMAT RULES (VERY IMPORTANT):\n"
                      . "1. Strictly DO NOT use Markdown symbols like **, *, #, or ---.\n"
                      . "2. Provide concise, compact, and clear answers.\n"
                      . "3. Use numbers (1, 2, 3) or regular dashes (-) only for point lists.\n"
                      . "4. ENSURE YOU PROVIDE 1 BLANK LINE (DOUBLE NEWLINE) between points so the answer is spaced out and easy to read.\n"
                      . "5. Directly provide the brief root cause and solution steps without long introductions.\n\n"
                      . "User Message/Question: " . $promptText;

        $parts[] = ['text' => $systemPrompt];

        try {
            $endpointPrimary = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key=' . trim($this->apiKey);

            $response = Http::timeout(20)
                ->withoutVerifying()
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($endpointPrimary, [
                    'contents' => [
                        [
                            'parts' => $parts
                        ]
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No answer received from AI.';
                
                $cleanReply = preg_replace('/[\*\#\_]/', '', $reply);
                $cleanReply = str_replace('---', '', $cleanReply);

                return response()->json(['reply' => trim($cleanReply)]);
            }

            // Mesej mesra jika API gagal / sibuk (Status bukan 200 OK)
            return response()->json([
                'reply' => 'Our AI system is currently experiencing high traffic or temporary disruptions. Please try resending your message shortly.'
            ]);

        } catch (\Exception $e) {
            // Tangkap sebarang ralat cURL / Timeout / Server Error dan tukar kepada mesej mesra
            return response()->json([
                'reply' => 'Sorry, the system is unable to connect to the AI server at the moment. Please try again in a few moments.'
            ]);
        }
    }
}