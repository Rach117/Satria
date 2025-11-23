<div class="mt-auto"></div> 
    
    <footer class="bg-white border-t border-slate-200 py-6 px-8 mt-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            
            <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8">
                <div class="text-center md:text-left">
                    <p class="text-sm font-bold text-slate-700">
                        &copy; 2025 <span class="text-blue-700">SATRIA</span> Politeknik Negeri Jakarta.
                    </p>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mt-0.5 font-semibold">Enterprise Resource Planning System v1.0</p>
                </div>
                
                <div class="hidden md:flex items-center px-3 py-1 bg-emerald-50 border border-emerald-100 rounded-full">
                    <span class="relative flex h-2 w-2 mr-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wide">System Operational</span>
                </div>
            </div>

            <div class="flex items-center gap-6 text-xs font-semibold text-slate-500">
                <a href="/bantuan" class="hover:text-blue-600 transition-colors flex items-center">
                    <span class="material-icons text-[14px] mr-1">help_outline</span> Pusat Bantuan
                </a>
                <a href="/syarat" class="hover:text-blue-600 transition-colors">Kebijakan Privasi</a>
                <a href="/syarat" class="hover:text-blue-600 transition-colors">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>

</div> 
<script>
    // 1. Auto-Close Toast Notification (LOGIKA CERDAS)
    document.addEventListener('DOMContentLoaded', () => {
        // Ambil semua elemen yang memiliki animasi 'fade-in-down'
        const animatedElements = document.querySelectorAll('.animate-fade-in-down');
        
        animatedElements.forEach(element => {
            // CEK PENTING: Apakah elemen ini berada di dalam #modalLogout?
            const isInsideModal = element.closest('#modalLogout');
            
            // Logika: HANYA tutup otomatis jika elemen tersebut BUKAN bagian dari modal
            if (!isInsideModal) {
                setTimeout(() => {
                    element.style.transition = 'all 0.5s ease-out';
                    element.style.opacity = '0';
                    element.style.transform = 'translateY(-20px)';
                    setTimeout(() => element.remove(), 500);
                }, 4000); // Hilang setelah 4 detik
            }
            // Jika isInsideModal = true, script tidak melakukan apa-apa (Modal aman)
        });
    });

    // 2. Session Timeout Logic (30 Menit Idle)
    let idleTime = 0;
    const timeOutLimit = 30 * 60 * 1000; 

    function resetTimer() { idleTime = 0; }
    function timerIncrement() {
        idleTime += 60000; 
        if (idleTime > timeOutLimit) window.location.href = '/logout';
    }

    ['load', 'mousemove', 'keypress', 'click', 'scroll'].forEach(evt => 
        document.addEventListener(evt, resetTimer, false)
    );
    setInterval(timerIncrement, 60000);
</script>

</body>
</html>