<div id="chatbot-widget" class="fixed bottom-24 right-6 z-50">
    <!-- Tombol buka -->
    <button id="chatbot-toggle"
        class="bg-blue-600 text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 shadow-[0_10px_25px_-5px_rgba(37,99,235,0.6)] flex items-center justify-center hover:bg-blue-700 hover:scale-110 transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>

    <!-- Pop up SIMA Besar di Tengah Layar -->
    <div id="chatbot-tooltip" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm transition-all duration-500 opacity-0 pointer-events-none">
        <!-- Modal Content -->
        <div id="chatbot-tooltip-box" class="bg-[#f0f7ff] rounded-3xl shadow-2xl max-w-sm w-[90%] text-center relative overflow-hidden transform scale-95 transition-transform duration-500 border border-white">
            
            <!-- Close Button -->
            <button id="close-tooltip" class="absolute top-4 right-4 text-blue-400 hover:text-blue-600 bg-white/50 hover:bg-white rounded-full p-2 transition-colors focus:outline-none z-20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Top Graphic Area -->
            <div class="pt-12 pb-8 flex items-center justify-center relative">
                <!-- Decorative background circle -->
                <div class="absolute inset-0 flex items-center justify-center opacity-50">
                    <div class="w-48 h-48 bg-white rounded-full"></div>
                </div>
                
                <!-- Robot Icon with AI Badge -->
                <div class="w-24 h-24 bg-white/80 backdrop-blur-md rounded-2xl shadow-sm flex flex-col items-center justify-center relative z-10">
                    <div class="w-14 h-14 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-md">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a2 2 0 012 2c0 .74-.4 1.39-1 1.73V7h1a3 3 0 013 3v2h1a1 1 0 011 1v4a1 1 0 01-1 1h-1v2a3 3 0 01-3 3H9a3 3 0 01-3-3v-2H5a1 1 0 01-1-1v-4a1 1 0 011-1h1V10a3 3 0 013-3h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 012-2zM9 10a1 1 0 00-1 1v8a1 1 0 001 1h6a1 1 0 001-1v-8a1 1 0 00-1-1H9zm1 2h4v2h-4v-2z"></path></svg>
                    </div>
                    <div class="absolute -bottom-2 -right-2 bg-blue-400 text-white text-[10px] font-bold px-2 py-0.5 rounded-full border-2 border-white shadow-sm">AI</div>
                </div>
            </div>

            <!-- Bottom Content Area (White Card) -->
            <div class="bg-white rounded-t-[2rem] pt-8 pb-6 px-6 relative z-10 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.05)]">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Hai! 👋</h3>
                <p class="text-gray-600 text-[15px] mb-6 leading-relaxed">Ketika memiliki pertanyaan, ayo tanya <strong>SIMA</strong>!</p>
                
                <button id="open-chatbot-btn" class="w-full bg-blue-600 text-white font-bold py-3.5 px-6 rounded-2xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 hover:scale-[1.02]">
                    <span>Mulai Tanya SIMA</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Panel chat -->
    <div id="chatbot-panel" class="hidden absolute bottom-24 right-0 bg-[#f0f7ff] rounded-3xl shadow-2xl border border-gray-100 flex flex-col overflow-hidden w-[90vw] sm:w-[33vw] h-[60vh] sm:h-[60vh] max-h-[700px]">
        
        <!-- Header -->
        <div class="bg-white px-5 py-4 flex items-center justify-between z-10 relative">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a2 2 0 012 2c0 .74-.4 1.39-1 1.73V7h1a3 3 0 013 3v2h1a1 1 0 011 1v4a1 1 0 01-1 1h-1v2a3 3 0 01-3 3H9a3 3 0 01-3-3v-2H5a1 1 0 01-1-1v-4a1 1 0 011-1h1V10a3 3 0 013-3h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 012-2zM9 10a1 1 0 00-1 1v8a1 1 0 001 1h6a1 1 0 001-1v-8a1 1 0 00-1-1H9zm1 2h4v2h-4v-2z"></path></svg>
                </div>
                <span class="font-bold text-gray-800 text-lg">SIMA Bot</span>
            </div>
            <div class="flex items-center gap-3 text-gray-500">
                <button id="close-chatbot-panel" class="hover:text-gray-800"><svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
        </div>

        <!-- Messages Area -->
        <div class="flex-1 relative">
            <!-- Placeholder Tengah -->
            <div id="chatbot-placeholder" class="absolute inset-0 flex items-center justify-center pointer-events-none transition-opacity duration-300">
                <div class="w-64 h-64 bg-white/30 rounded-full flex items-center justify-center">
                    <div class="w-40 h-40 bg-white/70 backdrop-blur-md rounded-2xl shadow-sm flex flex-col items-center justify-center relative">
                        <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center text-white shadow-md">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a2 2 0 012 2c0 .74-.4 1.39-1 1.73V7h1a3 3 0 013 3v2h1a1 1 0 011 1v4a1 1 0 01-1 1h-1v2a3 3 0 01-3 3H9a3 3 0 01-3-3v-2H5a1 1 0 01-1-1v-4a1 1 0 011-1h1V10a3 3 0 013-3h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 012-2zM9 10a1 1 0 00-1 1v8a1 1 0 001 1h6a1 1 0 001-1v-8a1 1 0 00-1-1H9zm1 2h4v2h-4v-2z"></path></svg>
                        </div>
                        <div class="absolute bottom-4 right-6 bg-blue-400 text-white text-[10px] font-bold px-2 py-0.5 rounded-full border-2 border-white shadow-sm">AI</div>
                    </div>
                </div>
            </div>

            <div id="chatbot-messages" class="absolute inset-0 overflow-y-auto p-5 space-y-4 pb-32 z-10"></div>
        </div>

        <!-- Input Area (Curved Bottom Sheet) -->
        <div class="bg-white rounded-t-[2rem] pt-6 pb-5 px-6 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.1)] absolute bottom-0 left-0 right-0 z-20">
            <input id="chatbot-input" type="text" placeholder="How can I help you today?"
                class="w-full bg-transparent text-gray-700 text-lg placeholder-gray-400 focus:outline-none mb-4" />
            
            <div class="flex items-center justify-end text-gray-400">
                <button id="chatbot-send" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all hover:scale-105">
                    <svg class="w-6 h-6 transform -rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatToggle = document.getElementById('chatbot-toggle');
    const chatPanel = document.getElementById('chatbot-panel');
    const chatInput = document.getElementById('chatbot-input');
    const chatSend = document.getElementById('chatbot-send');
    const chatMessages = document.getElementById('chatbot-messages');
    const chatTooltip = document.getElementById('chatbot-tooltip');
    const tooltipBox = document.getElementById('chatbot-tooltip-box');
    const closeTooltipBtn = document.getElementById('close-tooltip');
    const openChatbotBtn = document.getElementById('open-chatbot-btn');

    if (!chatToggle || !chatPanel) return;

    // Tampilkan pop-up setelah 1 detik jika panel chat belum dibuka
    // Hanya tampilkan pop-up otomatis jika berada di halaman landing page (bukan dashboard)
    const isLandingPage = window.location.pathname === '/';
    
    if (isLandingPage) {
        setTimeout(() => {
            if (chatTooltip && chatPanel.classList.contains('hidden')) {
                chatTooltip.classList.remove('opacity-0', 'pointer-events-none');
                chatTooltip.classList.add('opacity-100');
                if (tooltipBox) {
                    tooltipBox.classList.remove('scale-95');
                    tooltipBox.classList.add('scale-100');
                }
            }
        }, 1000);
    }

    function hideTooltip() {
        if (chatTooltip) {
            chatTooltip.classList.remove('opacity-100');
            chatTooltip.classList.add('opacity-0', 'pointer-events-none');
            if (tooltipBox) {
                tooltipBox.classList.remove('scale-100');
                tooltipBox.classList.add('scale-95');
            }
        }
    }

    if (closeTooltipBtn) closeTooltipBtn.addEventListener('click', hideTooltip);

    if (openChatbotBtn) {
        openChatbotBtn.addEventListener('click', () => {
            hideTooltip();
            if (chatPanel.classList.contains('hidden')) {
                chatPanel.classList.remove('hidden');
            }
        });
    }

    const closePanelBtn = document.getElementById('close-chatbot-panel');
    if (closePanelBtn) {
        closePanelBtn.addEventListener('click', () => {
            chatPanel.classList.add('hidden');
        });
    }

    chatToggle.addEventListener('click', () => {
        hideTooltip(); // Sembunyikan pop-up saat toggle diklik
        chatPanel.classList.toggle('hidden');
    });

    function appendMessage(role, text) {
        // Hilangkan placeholder tengah jika ada
        const placeholder = document.getElementById('chatbot-placeholder');
        if (placeholder) placeholder.style.opacity = '0';

        const wrapper = document.createElement('div');
        wrapper.className = 'flex w-full ' + (role === 'user' ? 'justify-end' : 'justify-start');

        const div = document.createElement('div');
        div.className = role === 'user'
            ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm px-4 py-3 max-w-[80%] shadow-md leading-relaxed'
            : 'bg-white text-gray-800 border border-gray-100 rounded-2xl rounded-bl-sm px-4 py-3 max-w-[80%] shadow-md leading-relaxed';
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
        div.className = 'bg-white border border-gray-100 rounded-2xl rounded-bl-sm px-4 py-4 max-w-[80%] shadow-md flex items-center h-11';
        
        div.innerHTML = `
            <div class="flex space-x-1.5 items-center">
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: -0.3s"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: -0.15s"></div>
                <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
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
