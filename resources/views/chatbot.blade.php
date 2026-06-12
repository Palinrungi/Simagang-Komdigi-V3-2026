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
        <div id="chatbot-tooltip-box" class="bg-white p-8 rounded-3xl shadow-2xl max-w-md w-[90%] text-center relative transform scale-95 transition-transform duration-500">
            
            <button id="close-tooltip" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 bg-gray-50 hover:bg-gray-200 rounded-full p-2 transition-colors focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Hai! 👋</h3>
            <p class="text-gray-600 text-lg mb-8 leading-relaxed">Ketika memiliki pertanyaan ayo tanya <strong>SIMA</strong>!</p>
            
            <button id="open-chatbot-btn" class="w-full bg-blue-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-blue-700 transition-colors shadow-lg hover:shadow-blue-500/40">
                Mulai Tanya SIMA
            </button>
        </div>
    </div>

    <!-- Panel chat -->
    <div id="chatbot-panel" class="hidden absolute bottom-16 right-0 bg-white rounded-xl shadow-2xl border border-gray-200 flex flex-col w-[90vw] sm:w-[33vw] h-[60vh] sm:h-[60vh] max-h-[700px]">
        <div class="bg-blue-600 text-white px-4 py-3 rounded-t-xl font-semibold text-sm">
            💬 FAQ Simagang
        </div>
        <div id="chatbot-messages" class="flex-1 overflow-y-auto p-3 space-y-2 text-sm"></div>
        <div class="p-3 border-t flex gap-2">
            <input id="chatbot-input" type="text" placeholder="Ketik pertanyaan..."
                class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
            <button id="chatbot-send" class="bg-blue-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-700">
                Kirim
            </button>
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

    chatToggle.addEventListener('click', () => {
        hideTooltip(); // Sembunyikan pop-up saat toggle diklik
        chatPanel.classList.toggle('hidden');
    });

    function appendMessage(role, text) {
        const div = document.createElement('div');
        div.className = role === 'user'
            ? 'bg-blue-100 text-blue-900 rounded-lg px-3 py-2 ml-8'
            : 'bg-gray-100 text-gray-800 rounded-lg px-3 py-2 mr-8';
        div.textContent = text;
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    async function sendMessage() {
        const msg = chatInput.value.trim();
        if (!msg) return;
        appendMessage('user', msg);
        chatInput.value = '';

        appendMessage('bot', '...');
        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const headers = { 'Content-Type': 'application/json' };
            if (csrfMeta) headers['X-CSRF-TOKEN'] = csrfMeta.content;

            const res = await fetch('/api/chatbot', {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ message: msg })
            });
            const data = await res.json();
            chatMessages.lastChild.textContent = data.answer ?? 'Maaf, terjadi kesalahan.';
        } catch {
            chatMessages.lastChild.textContent = 'Gagal menghubungi server. Coba lagi.';
        }
    }

    if (chatSend) chatSend.addEventListener('click', sendMessage);
    if (chatInput) chatInput.addEventListener('keypress', e => { if (e.key === 'Enter') sendMessage(); });
});
</script>
