<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    private $openaiEndpoint = 'https://api.openai.com/v1/chat/completions';

    public function index()
    {
        return view('chatbot.index');
    }

    public function respond(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userMessage = $request->input('message');

        if ($userMessage == "/menu") {
            return response()->json(['response' => $this->getMenu()]);
        } elseif (strpos($userMessage, '/ordine') === 0) {
            $orderDetails = explode(' ', $userMessage);
            $pizzaName = $orderDetails[1] ?? null;
            $quantity = $orderDetails[2] ?? null;

            if (!$pizzaName || !$quantity || !is_numeric($quantity)) {
                return response()->json(['error' => 'Please provide the pizza name and quantity in the format: /ordine [PizzaName] [Quantity].'], 400);
            }

            $this->saveOrder($pizzaName, $quantity);
            return response()->json(['response' => "Your order for $quantity $pizzaName has been placed!"]);
        } else {
            // Construct the chat payload for the chatbot
            $messages = [
                ["role" => "system", "content" => "You are a waiter at pizzeria Ambrosia. Your name is Matteo. Your main task is to provide menu information and assist in ordering a pizza."],
                ["role" => "user", "content" => $userMessage]
            ];

            // Get response from GPT-3
            try {
                $gpt3Response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                    'Content-Type' => 'application/json',
                ])->post($this->openaiEndpoint, [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => $messages
                ]);

                if ($gpt3Response->successful()) {
                    $responseText = $gpt3Response['choices'][0]['message']['content'];
                    return response()->json(['response' => $responseText]);
                } else {
                    throw new \Exception("OpenAI API Error: " . $gpt3Response->body());
                }
            } catch (\Exception $e) {
                \Log::error('OpenAI API Error: ' . $e->getMessage());
                return response()->json(['error' => 'An error occurred while processing your request.'], 500);
            }
        }
    }

    private function saveOrder($pizzaName, $quantity)
    {
        \DB::table('orders')->insert([
            'pizza_name' => $pizzaName,
            'quantity' => $quantity
        ]);
    }

    private function getMenu()
    {
        $menuItems = \DB::table('menu')->get();
        $menuResponse = "";
        foreach ($menuItems as $item) {
            $menuResponse .= $item->name . " - " . $item->ingredients . " - €" . $item->price . "\n";
        }
        return $menuResponse;
    }
}
