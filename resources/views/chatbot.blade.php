<style>
    @keyframes float-bot {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }
    .animate-float-bot {
        animation: float-bot 3.5s ease-in-out infinite;
    }
</style>

<div id="chatbot-widget" class="fixed bottom-36 sm:bottom-24 {{ request()->is('/') ? 'right-2 md:right-4' : 'right-6 md:right-8' }} z-50 translate-y-[10px]">
    <!-- Bubble Tanya SIMA -->
    <div id="chatbot-bubble-hint" class="absolute bottom-full mb-4 right-0 sm:right-4 bg-white text-blue-600 font-bold text-xs sm:text-sm px-5 py-2.5 rounded-full shadow-[0_5px_15px_rgba(0,0,0,0.15)] border border-gray-200 flex items-center justify-center z-40 whitespace-nowrap cursor-pointer hover:bg-gray-50 hover:scale-105 transition-all duration-500 opacity-0 pointer-events-none">
        ada pertanyaan?
        <!-- Segitiga penunjuk ke bawah -->
        <div class="absolute -bottom-1.5 right-10 w-3 h-3 bg-white transform rotate-45 border-b border-r border-gray-200 rounded-sm"></div>
    </div>

    <!-- Tombol buka -->
    <button id="chatbot-toggle"
        class="w-20 h-20 sm:w-28 sm:h-28 flex items-center justify-center hover:scale-110 transition-transform duration-300 focus:outline-none drop-shadow-2xl filter drop-shadow-[0_10px_15px_rgba(0,0,0,0.3)]">
        <img src="{{ asset('storage/chatbot_icon/SIMA nobg3.png') }}" class="w-full h-full object-contain animate-float-bot" alt="SIMA Bot">
    </button>

    <div id="chatbot-panel-wrapper" class="hidden absolute bottom-full mb-4 right-0 z-50 origin-bottom-right">
        <!-- Panel chat -->
        <div id="chatbot-panel" class="bg-[#f0f7ff] rounded-3xl shadow-2xl border border-gray-100 flex flex-col overflow-hidden w-[90vw] sm:w-[450px] h-[60vh] max-h-[600px] relative">
        
        <!-- Header -->
        <div class="bg-white px-4 py-3 flex items-center justify-between z-10 relative">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 overflow-hidden">
                    <img src="{{ asset('storage/chatbot_icon/SIMA head.png') }}" class="w-full h-full object-cover" alt="SIMA Bot">
                </div>
                <span class="font-bold text-gray-800 text-base">SIMA Bot</span>
            </div>
            <div class="flex items-center gap-3 text-gray-500">
                <button id="close-chatbot-panel" class="hover:text-gray-800"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 relative">
            <!-- Background Image Samar -->
            <div class="absolute inset-0 z-0 flex items-center justify-center opacity-[0.05] pointer-events-none">
                <img src="{{ asset('storage/chatbot_icon/SIMA nobg2.png') }}" class="w-3/4 max-h-[80%] object-contain" alt="Background">
            </div>

            <!-- Placeholder Tengah -->
            <div id="chatbot-placeholder" class="absolute inset-0 flex items-center justify-center pointer-events-none transition-opacity duration-300">
                <div class="w-48 h-48 bg-white/30 rounded-full flex items-center justify-center">
                    <div class="w-36 h-36 bg-white/70 backdrop-blur-md rounded-2xl shadow-sm flex flex-col items-center justify-center relative">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-md border-4 border-blue-50 overflow-hidden">
                            <img src="{{ asset('storage/chatbot_icon/SIMA nobg2.png') }}" class="w-full h-full object-cover" alt="SIMA Bot">
                        </div>
                        <div class="absolute bottom-4 right-6 bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full border-2 border-white shadow-md z-10">AI</div>
                    </div>
                </div>
            </div>

            <div id="chatbot-messages" class="absolute inset-0 overflow-y-auto p-5 space-y-4 pb-32 z-10"></div>
        </div>

        <!-- Input Area (Curved Bottom Sheet) -->
        <div class="bg-white rounded-t-[1.25rem] pt-3 pb-3 px-4 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] absolute bottom-0 left-0 right-0 z-20">
            <input id="chatbot-input" type="text" placeholder="How can I help you today?"
                class="w-full bg-transparent text-gray-700 text-xs placeholder-gray-400 focus:outline-none mb-2" />
            
            <div class="flex items-center justify-end text-gray-400">
                <button id="chatbot-send" class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all hover:scale-105">
                    <svg class="w-4 h-4 transform -rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>
        </div>
        
        <!-- Segitiga penunjuk ke toggle untuk panel chat -->
        <div class="absolute -bottom-3 right-10 sm:right-14 w-6 h-6 bg-white shadow-xl transform rotate-45 rounded-sm pointer-events-none z-10 border-b border-r border-gray-100"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatToggle = document.getElementById('chatbot-toggle');
    const chatPanelWrapper = document.getElementById('chatbot-panel-wrapper');
    const chatPanel = document.getElementById('chatbot-panel');
    const chatInput = document.getElementById('chatbot-input');
    const chatSend = document.getElementById('chatbot-send');
    const chatMessages = document.getElementById('chatbot-messages');
    const bubbleHint = document.getElementById('chatbot-bubble-hint');

    if (!chatToggle || !chatPanel) return;

    if (bubbleHint) {
        bubbleHint.addEventListener('click', () => {
            chatToggle.click();
        });
    }

    // Tampilkan pop-up bubble setelah 1 detik jika panel chat belum dibuka
    const isLandingPage = window.location.pathname === '/';
    let bubbleTimeout;
    
    if (isLandingPage) {
        setTimeout(() => {
            if (bubbleHint && chatPanelWrapper.classList.contains('hidden')) {
                bubbleHint.classList.remove('opacity-0', 'pointer-events-none');
                bubbleHint.classList.add('opacity-100');
                
                // Hilangkan otomatis setelah 5 detik
                bubbleTimeout = setTimeout(() => {
                    hideBubbleHint();
                }, 5000);
            }
        }, 1000);
    }

    function hideBubbleHint() {
        if (bubbleHint) {
            bubbleHint.classList.remove('opacity-100');
            bubbleHint.classList.add('opacity-0', 'pointer-events-none');
            clearTimeout(bubbleTimeout);
        }
    }

    let isFirstTimeOpen = true;

    function handleChatbotOpen() {
        if (isFirstTimeOpen) {
            isFirstTimeOpen = false;
            setTimeout(() => {
                appendMessage('bot', 'Hai aku SIMA chatbot asisten yang akan membantumu. Apa yang bisa saya bantu?');
            }, 300);
        }
    }

    const closePanelBtn = document.getElementById('close-chatbot-panel');
    if (closePanelBtn) {
        closePanelBtn.addEventListener('click', () => {
            chatPanelWrapper.classList.add('hidden');
        });
    }

    chatToggle.addEventListener('click', () => {
        hideBubbleHint(); // Sembunyikan pop-up saat toggle diklik
        if (chatPanelWrapper.classList.contains('hidden')) {
            chatPanelWrapper.classList.remove('hidden');
            handleChatbotOpen();
        } else {
            chatPanelWrapper.classList.add('hidden');
        }
    });

    function appendMessage(role, text) {
        // Hilangkan placeholder tengah jika ada
        const placeholder = document.getElementById('chatbot-placeholder');
        if (placeholder) placeholder.style.opacity = '0';

        const wrapper = document.createElement('div');
        wrapper.className = 'flex w-full ' + (role === 'user' ? 'justify-end' : 'justify-start');

        const div = document.createElement('div');
        div.className = role === 'user'
            ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm px-3 py-2 text-xs max-w-[85%] shadow-md leading-relaxed'
            : 'bg-white text-gray-800 border border-gray-100 rounded-2xl rounded-bl-sm px-3 py-2 text-xs max-w-[85%] shadow-md leading-relaxed';
        div.textContent = text;
        
        wrapper.appendChild(div);
        chatMessages.appendChild(wrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function appendTypingIndicator() {
        const placeholder = document.getElementById('chatbot-placeholder');
        if (placeholder) placeholder.style.opacity = '0';

        const wrapper = document.createElement('div');
        wrapper.className = 'flex w-full justify-start';

        const div = document.createElement('div');
        div.className = 'bg-white border border-gray-100 rounded-2xl rounded-bl-sm px-3 py-2 max-w-[80%] shadow-md flex items-center h-8';
        
        div.innerHTML = `
            <div class="flex space-x-1 items-center">
                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: -0.3s"></div>
                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: -0.15s"></div>
                <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"></div>
            </div>
        `;
        
        wrapper.appendChild(div);
        chatMessages.appendChild(wrapper);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        return wrapper;
    }

    async function sendMessage() {
        const msg = chatInput.value.trim();
        if (!msg) return;
        appendMessage('user', msg);
        chatInput.value = '';

        const typingIndicator = appendTypingIndicator();
        
        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const headers = { 'Content-Type': 'application/json' };
            if (csrfMeta) headers['X-CSRF-TOKEN'] = csrfMeta.content;

            const res = await fetch('/chatbot', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ message: msg })
            });
            const data = await res.json();
            typingIndicator.remove();
            appendMessage('bot', data.answer ?? 'Maaf, terjadi kesalahan.');
        } catch {
            typingIndicator.remove();
            appendMessage('bot', 'Gagal menghubungi server. Coba lagi.');
        }
    }

    if (chatSend) chatSend.addEventListener('click', sendMessage);
    if (chatInput) chatInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
});
</script>
