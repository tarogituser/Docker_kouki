<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebSocketテスト</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        #messages { border: 1px solid #ccc; padding: 10px; min-height: 200px; overflow-y: scroll; margin-bottom: 10px; }
        #messageInput { width: 80%; padding: 8px; }
        #sendButton { padding: 8px 15px; }
    </style>
</head>
<body>
    <h1>簡単なWebSocketテスト</h1>

    <div id="messages"></div>

    <input type="text" id="messageInput" placeholder="メッセージを入力...">
    <button id="sendButton">送信</button>

    <script>
        const messagesDiv = document.getElementById('messages');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');

        // WebSocket接続の確立
        // Docker Composeでwebsocket-serverサービス名でアクセスできるようになる
        const socket = new WebSocket('ws://localhost:8080'); // または ws://YOUR_DOCKER_HOST_IP:8080

        socket.onopen = function(event) {
            console.log('WebSocket接続が確立されました。');
            messagesDiv.innerHTML += '<p><strong>[システム]</strong> 接続されました。</p>';
        };

        socket.onmessage = function(event) {
            console.log('メッセージを受信しました:', event.data);
            messagesDiv.innerHTML += '<p><strong>[相手]</strong> ' + event.data + '</p>';
            messagesDiv.scrollTop = messagesDiv.scrollHeight; // スクロールを一番下へ
        };

        socket.onclose = function(event) {
            console.log('WebSocket接続が閉じられました。');
            messagesDiv.innerHTML += '<p><strong>[システム]</strong> 接続が閉じられました。</p>';
        };

        socket.onerror = function(event) {
            console.error('WebSocketエラー:', event);
            messagesDiv.innerHTML += '<p style="color: red;"><strong>[システム]</strong> エラーが発生しました。</p>';
        };

        sendButton.onclick = function() {
            const message = messageInput.value;
            if (message) {
                socket.send(message); // メッセージをサーバーに送信
                messagesDiv.innerHTML += '<p><strong>[自分]</strong> ' + message + '</p>';
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
                messageInput.value = ''; // 入力フィールドをクリア
            }
        };

        // Enterキーで送信
        messageInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                sendButton.click();
            }
        });
    </script>
</body>
</html>