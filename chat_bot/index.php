<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Chatbot UI with Typing Effect</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/markdown-it/dist/markdown-it.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-okaidia.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-javascript.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
    <div id="chatbot">
        <div id="chat-header">
            <img src="assets/img/minhaj-university-lahore.png" alt="Bot Avatar">
            <h4>MUL AI Assistant</h4>
            <div>
                <button id="fullscreen-btn">🗖</button>
                <button id="close-chat">&#10005;</button>
            </div>
        </div>
        <div id="chat-body">
            <div id="chat-messages">
                <div class="message bot-message">
                    Assalamualaikum! 🌟<br><br>
                    Welcome to MUL AI Assistant! How can I assist you today? 😊
                    <div class="message-time"></div>
                </div>
            </div>
        </div>
        <div id="chat-input">                        
            <input type="text" id="user-input" placeholder="Type your message...">
            <button id="send-btn">Send</button>
        </div>
    </div>
    <button id="chatbot-btn">💬</button>
    <script>
        const chatbotBtn = $('#chatbot-btn');
        const chatbot = $('#chatbot');
        const closeChat = $('#close-chat');
        const fullscreenBtn = $('#fullscreen-btn');
        const sendBtn = $('#send-btn');
        const chatMessages = $('#chat-messages');
        const userInput = $('#user-input');
        let userTyping = false;

        const md = window.markdownit({
            html: true,
            linkify: true,
            typographer: true,
            breaks: true,
            highlight: function (str, lang) {
                if (lang && Prism.languages[lang]) {
                    try {
                        return '<pre class="language-' + lang + '"><code>' +
                            Prism.highlight(str, Prism.languages[lang], lang) +
                            '</code></pre>';
                    } catch (__) {}
                }
                return '<pre class="language-plaintext"><code>' + md.utils.escapeHtml(str) + '</code></pre>';
            }
        });

        chatbotBtn.on('click', function () {
            chatbot.css('display', 'flex');
            chatbotBtn.css('display', 'none');
        });

        closeChat.on('click', function () {
            chatbot.css('display', 'none');
            chatbotBtn.css('display', 'block');
        });

        fullscreenBtn.on('click', function () {
            if (!document.fullscreenElement) {
                chatbot[0].requestFullscreen().catch(err => {
                    console.error(`Error attempting to enable full-screen mode: ${err.message}`);
                });
            } else {
                document.exitFullscreen().catch(err => {
                    console.error(`Error attempting to exit full-screen mode: ${err.message}`);
                });
            }
        });

        userInput.on('input', function () {
            if ($(this).val().trim() && !userTyping) {
                showTypingIndicator('user');
                userTyping = true;
            } else if (!$(this).val().trim() && userTyping) {
                removeTypingIndicator('user');
                userTyping = false;
            }
        });

        sendBtn.on('click', function () {
            const message = userInput.val().trim();
            if (message) {
                removeTypingIndicator('user');
                userTyping = false;
                addMessage(message, 'user-message');
                userInput.val('');
                showTypingIndicator('bot');
                botResponse(message);
            }
        });

        userInput.on('keydown', function (event) {
            if (event.key === 'Enter') {
                const message = $(this).val().trim();
                if (message) {
                    removeTypingIndicator('user');
                    userTyping = false;
                    addMessage(message, 'user-message');
                    $(this).val('');
                    showTypingIndicator('bot');
                    botResponse(message);
                }
            }
        });

        function sendMessageToApi(userMessage) {
            return $.ajax({
                url     : 'get_AnsFromChatBot.php',
                method  : 'GET',
                data    : {
                    "question"  : userMessage 
                },
                success: function(response) {
                    removeTypingIndicator('bot');
                    userTyping = false;

                    // Ensure the response is a string
                    if (typeof response === 'string') {
                        return response;
                    } else {
                        console.error("API response is not a string:", response);
                        return '';  // Return empty string to avoid issues
                    }
                }
            });
        }

        function botResponse(userMessage) {
            sendMessageToApi(userMessage).then(function (response) {
                console.log("Received response:", response); // Debugging log
                if (response) {
                    var parsedMessage = md.render(response);
                    addMessage(parsedMessage, 'bot-message');
                } else {
                    console.warn("Empty or invalid response");
                }
            }).catch(function (error) {
                console.error("Error rendering markdown:", error);
            });
        }

        function addMessage(text, className) {
            const messageDiv = $('<div>', {
                class: `message ${className}`,
                html: `${text}<div class="message-time">${getCurrentTime()}</div>`
            });
            chatMessages.append(messageDiv);
            chatMessages.scrollTop(chatMessages[0].scrollHeight);
        }

        function showTypingIndicator(type) {
            const typingIndicator = $('<div>', {
                class: 'typing-indicator',
                id: `${type}-typing`,
                html: `
                    <span class="typing-dots"></span>
                    <span class="typing-dots"></span>
                    <span class="typing-dots"></span>
                `
            });
            chatMessages.append(typingIndicator);
            chatMessages.scrollTop(chatMessages[0].scrollHeight);
        }

        function removeTypingIndicator(type) {
            const typingIndicator = $(`#${type}-typing`);
            if (typingIndicator.length) {
                typingIndicator.remove();
            }
        }

        function getCurrentTime() {
            const date = new Date();
            const options = { hour: '2-digit', minute: '2-digit', hour12: false };
            return date.toLocaleTimeString([], options);
        }
    </script>
</body>
</html>
