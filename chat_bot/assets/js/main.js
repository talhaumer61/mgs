const chatbotBtn        = document.getElementById('chatbot-btn');
const chatbot           = document.getElementById('chatbot');
const closeChat         = document.getElementById('close-chat');
const fullscreenBtn      = document.getElementById('fullscreen-btn');        
const md                 = window.markdownit({
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
chatbotBtn.addEventListener('click', () => {
    chatbot.style.display = 'flex';
    chatbotBtn.style.display = 'none';
});
closeChat.addEventListener('click', () => {
    chatbot.style.display = 'none';
    chatbotBtn.style.display = 'block';
});       
fullscreenBtn.addEventListener('click', () => {
    if (!document.fullscreenElement) {
        chatbot.requestFullscreen().catch(err => {
            console.error(`Error attempting to enable full-screen mode: ${err.message}`);
        });
    } else {
        document.exitFullscreen().catch(err => {
            console.error(`Error attempting to exit full-screen mode: ${err.message}`);
        });
    }
});
const sendBtn           = document.getElementById('send-btn');
const chatMessages      = document.getElementById('chat-messages');
const userInput         = document.getElementById('user-input');
let userTyping          = false;
userInput.addEventListener('input', () => {
    if (userInput.value.trim() && !userTyping) {
        showTypingIndicator('user');
        userTyping = true;
    } else if (!userInput.value.trim() && userTyping) {
        removeTypingIndicator('user');
        userTyping = false;
    }
});
sendBtn.addEventListener('click', () => {
    const message = userInput.value.trim();
    if (message) {
        removeTypingIndicator('user');
        userTyping = false;
        addMessage(message, 'user-message');
        userInput.value = '';
        showTypingIndicator('bot');
        botResponse(message);
    }
});
userInput.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {                
        const message = userInput.value.trim();
        if (message) {
            removeTypingIndicator('user');
            userTyping = false;
            addMessage(message, 'user-message');
            userInput.value = '';
            showTypingIndicator('bot');
            botResponse(message);
        }
    }
});
function sendMessageToApi(userMessage) {
    try {
        return(fetch('get_AnsFromChatBot.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ question: userMessage })
        }))
        .then(response => response.json())
        .then(data => {
            removeTypingIndicator('bot');
            userTyping = false;
            return data.response;
        })
        .catch(error => {
            console.error('Error:', error);
            return "Sorry, there was an error processing your request.";
        });
    } catch (error) {
        console.error('Error:', error);
        addMessage('Chatbot', 'Sorry, I encountered an error while processing your question. Please try again later.');
    }
}
function botResponse(userMessage) {
    sendMessageToApi(userMessage).then(response => {
        var parsedMessage = md.render(response)
        addMessage(parsedMessage, 'bot-message');
    });
}
function addMessage(text, className) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${className}`;
    messageDiv.innerHTML = `${text}<div class="message-time">${getCurrentTime()}</div>`;
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}
function showTypingIndicator(type) {
    const typingIndicator = document.createElement('div');
    typingIndicator.className = 'typing-indicator';
    typingIndicator.id = `${type}-typing`;
    typingIndicator.innerHTML = `
        <span class="typing-dots"></span>
        <span class="typing-dots"></span>
        <span class="typing-dots"></span>
    `;
    chatMessages.appendChild(typingIndicator);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}
function removeTypingIndicator(type) {
    const typingIndicator = document.getElementById(`${type}-typing`);
    if (typingIndicator) {
        typingIndicator.remove();
    }
}
function getCurrentTime() {
    const date = new Date();
    const options = { hour: '2-digit', minute: '2-digit', hour12: false };
    return date.toLocaleTimeString([], options);
}