<style>
    @keyframes float-bot {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }
    .animate-float-bot {
        animation: float-bot 3.5s ease-in-out infinite;
    }
</style>

<div id="chatbot-widget" class="fixed bottom-36 sm:bottom-24 right-6 z-50">
    <!-- Bubble Tanya SIMA Permanen -->
    <!-- <div id="chatbot-bubble-hint" class="absolute bottom-[90%] sm:bottom-full mb-2 right-1/2 translate-x-1/2 bg-white text-blue-600 font-bold text-sm sm:text-base px-5 py-2.5 rounded-full shadow-[0_5px_15px_rgba(0,0,0,0.15)] border border-blue-100 flex items-center justify-center animate-bounce z-40 whitespace-nowrap cursor-pointer hover:bg-blue-50 hover:scale-105 transition-all">
        Tanya SIMA -->
        <!-- Segitiga penunjuk ke bawah -->
        <!-- <div class="absolute -bottom-1.5 w-3 h-3 bg-white transform rotate-45 border-b border-r border-blue-100 rounded-sm"></div>
    </div> -->

    <!-- Tombol buka -->
    <button id="chatbot-toggle"
        class="w-28 h-28 sm:w-36 sm:h-36 flex items-center justify-center hover:scale-125 transition-transform duration-300 focus:outline-none drop-shadow-2xl filter drop-shadow-[0_10px_15px_rgba(0,0,0,0.3)]">
        <img src="{{ asset('storage/chatbot_icon/SIMA nobg3.png') }}" class="w-full h-full object-contain animate-float-bot" alt="SIMA Bot">
    </button>

    <!-- Pop up SIMA Mengarahkan ke Toggle -->
    <div id="chatbot-tooltip" class="absolute bottom-full right-0 mb-4 z-[60] flex flex-col items-end transition-all duration-500 opacity-0 pointer-events-none origin-bottom-right">
        <!-- Modal Content -->
        <div id="chatbot-tooltip-box" class="bg-[#f0f7ff] rounded-3xl shadow-2xl w-[95vw] sm:w-[650px] max-w-[800px] text-center relative overflow-hidden transform scale-95 transition-transform duration-500 border border-white">
            
            <!-- Close Button -->
            <button id="close-tooltip" class="absolute top-4 right-4 text-blue-400 hover:text-blue-600 bg-white/50 hover:bg-white rounded-full p-2 transition-colors focus:outline-none z-20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Top Graphic Area (Background Image) -->
            <div class="h-80 w-full flex items-center justify-center relative">
                <!-- Background Image -->
                <div class="absolute inset-0 z-0 flex items-center justify-center pointer-events-none">
                    <img src="{{ asset('storage/chatbot_icon/SIMA menyapa nobg.png') }}" class="w-full h-full object-contain" alt="Background">
                </div>
            </div>

            <!-- Bottom Content Area (White Card) -->
            <div class="bg-white rounded-t-[2.5rem] pt-10 pb-8 px-8 relative z-10 shadow-[0_-10px_40px_-15px_rgba(0,0,0,0.05)]">
                <h3 class="text-3xl font-bold text-gray-800 mb-3">Hai!</h3>
                <p class="text-gray-600 text-lg mb-8 leading-relaxed">Selamat datang di web SIMAGANG BBLSDM Komdigi Makassar. Perkenalkan, aku adalah <strong>SIMA</strong>, bot asisten yang akan membantumu menjawab pertanyaan seputar SIMAGANG.</p>
                
                <button id="open-chatbot-btn" class="w-full bg-blue-600 text-white font-bold py-4 px-6 rounded-2xl text-lg hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 hover:scale-[1.02]">
                    <span>Mulai Tanya SIMA</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>
        <!-- Segitiga penunjuk ke toggle -->
        <div class="w-10 h-10 bg-white transform rotate-45 mr-8 -mt-5 shadow-[4px_4px_10px_rgba(0,0,0,0.1)] rounded-sm pointer-events-none"></div>
    </div>

    <!-- Panel chat -->
    <div id="chatbot-panel" class="hidden absolute bottom-24 right-0 bg-[#f0f7ff] rounded-3xl shadow-2xl border border-gray-100 flex flex-col overflow-hidden w-[90vw] sm:w-[33vw] h-[60vh] sm:h-[60vh] max-h-[700px]">
        
        <!-- Header -->
        <div class="bg-white px-5 py-4 flex items-center justify-between z-10 relative">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white rounded-full flex items-center justify-center shadow-sm border border-gray-100 overflow-hidden">
                    <img src="{{ asset('storage/chatbot_icon/SIMA head.png') }}" class="w-full h-full object-cover" alt="SIMA Bot">
                </div>
                <span class="font-bold text-gray-800 text-lg">SIMA Bot</span>
            </div>
            <div class="flex items-center gap-3 text-gray-500">
                <button id="close-chatbot-panel" class="hover:text-gray-800"><svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg></button>
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
                <div class="w-72 h-72 bg-white/30 rounded-full flex items-center justify-center">
                    <div class="w-56 h-56 bg-white/70 backdrop-blur-md rounded-2xl shadow-sm flex flex-col items-center justify-center relative">
                        <div class="w-36 h-36 bg-white rounded-full flex items-center justify-center shadow-md border-4 border-blue-50 overflow-hidden">
                            <img src="{{ asset('storage/chatbot_icon/SIMA nobg2.png') }}" class="w-full h-full object-cover" alt="SIMA Bot">
                        </div>
                        <div class="absolute bottom-6 right-8 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full border-2 border-white shadow-md z-10">AI</div>
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
    const bubbleHint = document.getElementById('chatbot-bubble-hint');

    if (!chatToggle || !chatPanel) return;

    if (bubbleHint) {
        bubbleHint.addEventListener('click', () => {
            chatToggle.click();
        });
    }

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

    let isFirstTimeOpen = true;

    function handleChatbotOpen() {
        if (isFirstTimeOpen) {
            isFirstTimeOpen = false;
            setTimeout(() => {
                appendMessage('bot', 'Hai aku SIMA chatbot asisten yang akan membantumu. Apa yang bisa saya bantu?');
            }, 300);
        }
    }

    if (openChatbotBtn) {
        openChatbotBtn.addEventListener('click', () => {
            hideTooltip();
            if (chatPanel.classList.contains('hidden')) {
                chatPanel.classList.remove('hidden');
                if (bubbleHint) bubbleHint.style.display = 'none';
                handleChatbotOpen();
            }
        });
    }

    const closePanelBtn = document.getElementById('close-chatbot-panel');
    if (closePanelBtn) {
        closePanelBtn.addEventListener('click', () => {
            chatPanel.classList.add('hidden');
            if (bubbleHint) bubbleHint.style.display = 'flex';
        });
    }

    chatToggle.addEventListener('click', () => {
        hideTooltip(); // Sembunyikan pop-up saat toggle diklik
        if (chatPanel.classList.contains('hidden')) {
            chatPanel.classList.remove('hidden');
            if (bubbleHint) bubbleHint.style.display = 'none';
            handleChatbotOpen();
        } else {
            chatPanel.classList.add('hidden');
            if (bubbleHint) bubbleHint.style.display = 'flex';
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
