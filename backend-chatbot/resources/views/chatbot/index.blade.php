<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
</head>
<body>
    <div>
        <textarea id="chatWindow" disabled style="width: 100%; height: 300px;"></textarea>
    </div>
    <div>
        <input type="text" id="userInput" style="width: 80%;">
        <button onclick="sendMessage()">Send</button>
    </div>

    <script>
        function sendMessage() {
            const input = document.getElementById('userInput');
            const chatWindow = document.getElementById('chatWindow');

            // Append user message to chat window
            chatWindow.value += "You: " + input.value + "\n";

            // Send message to server for processing
            fetch('/chatbot/respond', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: input.value })
            })
            .then(response => response.json())
            .then(data => {
                // Append chatbot's response to chat window
                chatWindow.value += "Bot: " + data.response + "\n";
            });

            // Clear the input field
            input.value = "";
        }
    </script>
</body>
</html>
