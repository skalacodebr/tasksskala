<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agente de Chat - Levantamento de Requisitos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .chat-container {
            height: calc(100vh - 200px);
            min-height: 400px;
        }
        .messages-container {
            height: calc(100% - 80px);
            overflow-y: auto;
        }
        .message {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .typing-indicator {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            background-color: #e5e7eb;
            border-radius: 18px;
        }
        .typing-dot {
            width: 8px;
            height: 8px;
            margin: 0 2px;
            background-color: #6b7280;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-10px); }
        }
        .workflow-selector {
            transition: all 0.3s ease;
        }
        .workflow-selector:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-lg shadow-lg">
            <!-- Header -->
            <div class="bg-indigo-600 text-white p-6 rounded-t-lg">
                <h1 class="text-2xl font-bold">Agente de Chat Inteligente</h1>
                <p class="mt-2 text-indigo-100">Assistente para levantamento de requisitos</p>
            </div>

            <!-- Workflow Selector (shown before chat starts) -->
            <div id="workflowSelector" class="p-6">
                <h2 class="text-xl font-semibold mb-4">Selecione um fluxo de conversa:</h2>
                <div class="grid gap-4">
                    <div class="workflow-selector border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-indigo-400" 
                         onclick="startChat('requisitos_software.json')">
                        <h3 class="font-semibold text-lg">Levantamento de Requisitos de Software</h3>
                        <p class="text-gray-600 mt-1">Colete informações detalhadas sobre um novo projeto de software</p>
                    </div>
                    <!-- Adicione mais workflows aqui conforme necessário -->
                </div>
            </div>

            <!-- Chat Interface (hidden initially) -->
            <div id="chatInterface" class="hidden">
                <div class="chat-container p-6">
                    <!-- Messages -->
                    <div class="messages-container" id="messagesContainer">
                        <!-- Messages will be inserted here -->
                    </div>

                    <!-- Input -->
                    <div class="mt-4 flex gap-2">
                        <input type="text" 
                               id="messageInput" 
                               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               placeholder="Digite sua mensagem..."
                               disabled>
                        <button id="sendButton" 
                                onclick="sendMessage()" 
                                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:bg-gray-400"
                                disabled>
                            Enviar
                        </button>
                    </div>
                </div>

                <!-- Progress -->
                <div class="bg-gray-50 px-6 py-3 rounded-b-lg border-t">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <span id="progressText">Iniciando conversa...</span>
                        </div>
                        <button onclick="resetChat()" class="text-sm text-red-600 hover:text-red-800">
                            Reiniciar Conversa
                        </button>
                    </div>
                    <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                        <div id="progressBar" class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Download Results (shown when chat is completed) -->
        <div id="completedSection" class="hidden mt-6 bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Conversa Concluída!</h2>
            <p class="text-gray-600 mb-4">Os requisitos foram coletados com sucesso. Você pode baixar o resumo da conversa.</p>
            <div class="flex gap-4">
                <button onclick="downloadSummary()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Baixar Resumo
                </button>
                <button onclick="startNewChat()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Nova Conversa
                </button>
            </div>
        </div>
    </div>

    <script>
        let sessionId = null;
        let currentStep = 0;
        let totalSteps = 0;
        let collectedData = {};
        let isTyping = false;

        // Initialize
        document.getElementById('messageInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !isTyping) {
                sendMessage();
            }
        });

        function startChat(workflowPath) {
            // Hide selector, show chat
            document.getElementById('workflowSelector').classList.add('hidden');
            document.getElementById('chatInterface').classList.remove('hidden');

            // Start chat session
            fetch('{{ route("agente-chat.start") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    workflow_path: workflowPath
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showError(data.error);
                    return;
                }
                
                sessionId = data.session_id;
                currentStep = data.current_step;
                totalSteps = data.total_steps;
                
                // Add assistant message
                addMessage('assistant', data.response);
                
                // Enable input
                document.getElementById('messageInput').disabled = false;
                document.getElementById('sendButton').disabled = false;
                document.getElementById('messageInput').focus();
                
                updateProgress();
            })
            .catch(error => {
                showError('Erro ao iniciar chat: ' + error.message);
            });
        }

        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message || isTyping) return;
            
            // Add user message
            addMessage('user', message);
            
            // Clear input
            input.value = '';
            
            // Show typing indicator
            showTypingIndicator();
            isTyping = true;
            
            // Disable input while processing
            input.disabled = true;
            document.getElementById('sendButton').disabled = true;
            
            // Send message
            fetch('{{ route("agente-chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                // Remove typing indicator
                removeTypingIndicator();
                isTyping = false;
                
                if (data.error) {
                    showError(data.error);
                    return;
                }
                
                // Add assistant response
                addMessage('assistant', data.response);
                
                if (data.completed) {
                    // Chat completed
                    collectedData = data.collected_data;
                    showCompletedSection();
                } else {
                    // Update progress
                    currentStep = data.current_step;
                    updateProgress();
                    
                    // Re-enable input
                    input.disabled = false;
                    document.getElementById('sendButton').disabled = false;
                    input.focus();
                }
            })
            .catch(error => {
                removeTypingIndicator();
                isTyping = false;
                showError('Erro ao enviar mensagem: ' + error.message);
                
                // Re-enable input
                input.disabled = false;
                document.getElementById('sendButton').disabled = false;
            });
        }

        function addMessage(role, content) {
            const container = document.getElementById('messagesContainer');
            const messageDiv = document.createElement('div');
            messageDiv.className = 'message mb-4 flex ' + (role === 'user' ? 'justify-end' : 'justify-start');
            
            const bubble = document.createElement('div');
            bubble.className = 'max-w-md px-4 py-2 rounded-lg ' + 
                (role === 'user' 
                    ? 'bg-indigo-600 text-white' 
                    : 'bg-gray-100 text-gray-800');
            bubble.textContent = content;
            
            messageDiv.appendChild(bubble);
            container.appendChild(messageDiv);
            
            // Scroll to bottom
            container.scrollTop = container.scrollHeight;
        }

        function showTypingIndicator() {
            const container = document.getElementById('messagesContainer');
            const typingDiv = document.createElement('div');
            typingDiv.id = 'typingIndicator';
            typingDiv.className = 'message mb-4 flex justify-start';
            
            typingDiv.innerHTML = `
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            `;
            
            container.appendChild(typingDiv);
            container.scrollTop = container.scrollHeight;
        }

        function removeTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) {
                indicator.remove();
            }
        }

        function updateProgress() {
            const progress = ((currentStep + 1) / totalSteps) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressText').textContent = `Etapa ${currentStep + 1} de ${totalSteps}`;
        }

        function showCompletedSection() {
            document.getElementById('completedSection').classList.remove('hidden');
            document.getElementById('messageInput').disabled = true;
            document.getElementById('sendButton').disabled = true;
            document.getElementById('progressText').textContent = 'Conversa concluída!';
            document.getElementById('progressBar').style.width = '100%';
        }

        function showError(message) {
            addMessage('system', '❌ ' + message);
        }

        function resetChat() {
            if (confirm('Tem certeza que deseja reiniciar a conversa? Todo o progresso será perdido.')) {
                location.reload();
            }
        }

        function startNewChat() {
            location.reload();
        }

        function downloadSummary() {
            // Create summary content
            const summary = `# Resumo do Levantamento de Requisitos

## Dados Coletados

${JSON.stringify(collectedData, null, 2)}

## Histórico da Conversa

${Array.from(document.querySelectorAll('.message')).map(msg => {
    const bubble = msg.querySelector('div');
    const role = bubble.classList.contains('bg-indigo-600') ? 'Usuário' : 'Assistente';
    return `**${role}:** ${bubble.textContent}`;
}).join('\n\n')}

---
Gerado em: ${new Date().toLocaleString('pt-BR')}
`;

            // Download file
            const blob = new Blob([summary], { type: 'text/markdown' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `requisitos_${new Date().getTime()}.md`;
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
</body>
</html>