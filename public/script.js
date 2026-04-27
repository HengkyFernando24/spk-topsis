document.addEventListener('DOMContentLoaded', function() {
    const btnCalculate = document.getElementById('btn-calculate');
    const tableContainer = document.getElementById('table-container');
    const loadingContainer = document.getElementById('loading-container');
    const statsContainer = document.getElementById('stats-container');
    const chartContainer = document.getElementById('custom-bar-chart');
    const resultBody = document.getElementById('result-body');
    
    // Reset style agar tidak menumpuk
    let oldStyle = document.getElementById('chart-styles');
    if(oldStyle) oldStyle.remove();
    
    const style = document.createElement('style');
    style.id = 'chart-styles';
    style.innerHTML = `
        @keyframes growUp { from { height: 0; opacity: 0; } to { opacity: 1; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .window-pattern {
            background-image: linear-gradient(to right, rgba(255,255,255,0.1) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 8px 12px;
        }
        /* Custom Scrollbar */
        .chart-scroll::-webkit-scrollbar {
            height: 12px;
        }
        .chart-scroll::-webkit-scrollbar-track {
            background: #f1f5f9; 
            border-radius: 10px;
            margin: 0 20px;
        }
        .chart-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 10px;
            border: 3px solid #f1f5f9;
        }
        .chart-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
        /* Style saat melakukan klik & seret (drag) */
        .chart-scroll.active {
            cursor: grabbing !important;
            cursor: -webkit-grabbing !important;
        }
    `;
    document.head.appendChild(style);

    if(!btnCalculate) return;

    btnCalculate.addEventListener('click', function() {
        tableContainer.style.opacity = '0.5';
        tableContainer.classList.add('pointer-events-none');
        loadingContainer.classList.remove('hidden');
        
        // Bersihkan chart sebelumnya
        chartContainer.innerHTML = ''; 
        chartContainer.classList.add('hidden');
        if(statsContainer) statsContainer.classList.add('hidden');

        const originalBtnHtml = btnCalculate.innerHTML;
        btnCalculate.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

        setTimeout(() => {
            const rows = resultBody.querySelectorAll('tr');
            let studentData = [];

            rows.forEach(row => {
                const cells = row.querySelectorAll('td, th');
                if(cells.length >= 2) {
                    let name = cells[1].innerText.trim();
                    let score = 0;
                    let foundScore = false;
                    
                    for (let i = cells.length - 1; i >= 0; i--) {
                        let textVal = cells[i].innerText.trim();
                        let cleanText = textVal.replace(/,/g, '.').replace(/[^0-9.]/g, '');
                        if (cleanText !== '') {
                            let parsedVal = parseFloat(cleanText);
                            if (!isNaN(parsedVal)) {
                                score = parsedVal;
                                foundScore = true;
                                break; 
                            }
                        }
                    }

                    if (foundScore && name !== '') {
                        studentData.push({ 
                            name: name, 
                            score: score, 
                            displayScore: score.toFixed(4)
                        });
                    }
                }
            });

            studentData.sort((a, b) => b.score - a.score);

            renderBuildingChart(studentData);
            updateStats(studentData);

            loadingContainer.classList.add('hidden');
            chartContainer.classList.remove('hidden');
            if(statsContainer) statsContainer.classList.remove('hidden');
            
            tableContainer.style.opacity = '1';
            tableContainer.classList.remove('pointer-events-none');
            
            const badge = document.getElementById('badge-status');
            if(badge) {
                badge.innerText = "Kalkulasi Selesai";
                badge.className = "text-[10px] font-black bg-emerald-100 text-emerald-700 px-4 py-1.5 rounded-full border border-emerald-200 uppercase tracking-widest";
            }

            btnCalculate.innerHTML = originalBtnHtml;

        }, 800);
    });

  function renderBuildingChart(data) {
        // Kunci stabilitas: class block, w-full, dan clear-both agar tidak mendesak tabel
        chartContainer.className = "bg-white p-6 sm:p-8 rounded-2xl shadow-xl shadow-slate-200/40 border border-slate-200 mb-8 relative mt-8 w-full block clear-both no-print";
        
        // Header Chart dipisah dan dirapikan
        const chartTitle = document.createElement('div');
        chartTitle.className = "mb-6 border-b border-slate-100 pb-4 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 relative z-20";
        chartTitle.innerHTML = `
            <div>
                <h3 class="font-black text-xl text-slate-800">Visualisasi Peringkat (1 - ${data.length})</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Gedung Tertinggi = Nilai Terbaik (Klik & Geser area ini ↔️)</p>
            </div>
            <div class="text-xs font-bold text-emerald-700 bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-200 shadow-sm">
                Total: ${data.length} Mahasiswa
            </div>
        `;
        chartContainer.appendChild(chartTitle);

        // Bungkus khusus untuk area scroll (Drag to scroll)
        const scrollArea = document.createElement('div');
        scrollArea.className = "w-full overflow-x-auto overflow-y-hidden chart-scroll select-none pb-6 pt-4";
        chartContainer.appendChild(scrollArea);

        // Area Gedung (Kunci Stabilitas: Tinggi dikunci di h-[300px] agar tidak menabrak judul)
        const chartWrapper = document.createElement('div');
        chartWrapper.className = "flex items-end justify-start gap-3 min-w-max h-[300px] px-4 pt-10";
        scrollArea.appendChild(chartWrapper);

        const maxScore = data[0].score > 0 ? data[0].score : 1; 
        
        data.forEach((item, index) => {
            // Kalkulasi tinggi bar proporsional (Minimal 2% agar rank terbawah tetap terlihat)
            const heightPercent = Math.max(2, (item.score / maxScore) * 100);
            const delay = Math.min(index * 20, 1000); // Percepat animasi untuk 90 data
            
            let colorClass = "from-emerald-500 to-emerald-400 border-emerald-600 opacity-80"; 
            let crown = "";

            if(index === 0) {
                colorClass = "from-amber-500 to-yellow-400 border-amber-600 shadow-amber-500/40"; 
                crown = `<div class="absolute -top-7 left-1/2 -translate-x-1/2 text-xl drop-shadow-md animate-bounce">👑</div>`;
            } else if(index === 1) {
                colorClass = "from-slate-400 to-slate-300 border-slate-500 shadow-slate-400/40";
                crown = `<div class="absolute -top-6 left-1/2 -translate-x-1/2 text-sm drop-shadow-md">🥈</div>`;
            } else if(index === 2) {
                colorClass = "from-orange-600 to-orange-400 border-orange-700 shadow-orange-500/40"; 
                crown = `<div class="absolute -top-6 left-1/2 -translate-x-1/2 text-sm drop-shadow-md">🥉</div>`;
            }

            // Ambil kata pertama saja dari nama agar muat di bawah gedung
            const firstName = item.name.split(' ')[0].substring(0, 8);

            const barHtml = `
                <div class="flex flex-col items-center justify-end h-full w-12 group relative pointer-events-auto" style="animation: fadeIn 0.5s ease-out ${delay}ms forwards; opacity: 0;">
                    
                    <div class="absolute -top-16 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-xs font-bold px-4 py-2 rounded-xl whitespace-nowrap pointer-events-none z-50 shadow-xl border border-slate-700">
                        Peringkat ${index + 1}<br>
                        <span class="text-emerald-400 font-black">${item.name}</span><br>
                        V: ${item.displayScore}
                    </div>

                    <span class="text-[9px] font-bold text-slate-500 mb-2 transition-all duration-300 transform -rotate-45 origin-bottom group-hover:rotate-0 group-hover:-translate-y-2 group-hover:text-emerald-600">
                        ${item.displayScore}
                    </span>

                    <div class="w-full relative rounded-t-sm transition-all duration-500 ease-out shadow-sm group-hover:shadow-md group-hover:brightness-110 bg-gradient-to-t ${colorClass}" 
                         style="height: ${heightPercent}%;">
                        ${crown}
                        <div class="absolute inset-0 window-pattern opacity-40 mix-blend-overlay pointer-events-none"></div>
                        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-4 h-6 sm:h-8 bg-black/40 rounded-t-sm border-t border-x border-white/10 pointer-events-none"></div>
                    </div>

                    <div class="mt-2 flex flex-col items-center w-full px-1">
                        <span class="text-[10px] font-black ${index < 3 ? 'text-emerald-600' : 'text-slate-400'}">
                            #${index + 1}
                        </span>
                        <span class="text-[9px] font-semibold text-slate-600 mt-0.5 text-center w-full truncate" title="${item.name}">
                            ${firstName}
                        </span>
                    </div>
                </div>
            `;
            chartWrapper.insertAdjacentHTML('beforeend', barHtml);
        });

        // ==========================================
        // Logika Drag to Scroll (Tetap Ada)
        // ==========================================
        let isDown = false;
        let startX;
        let scrollLeft;

        scrollArea.addEventListener('mousedown', (e) => {
            isDown = true;
            scrollArea.classList.add('active');
            startX = e.pageX - scrollArea.offsetLeft;
            scrollLeft = scrollArea.scrollLeft;
        });
        scrollArea.addEventListener('mouseleave', () => {
            isDown = false;
            scrollArea.classList.remove('active');
        });
        scrollArea.addEventListener('mouseup', () => {
            isDown = false;
            scrollArea.classList.remove('active');
        });
        scrollArea.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - scrollArea.offsetLeft;
            const walk = (x - startX) * 1.5; // Kecepatan drag geser
            scrollArea.scrollLeft = scrollLeft - walk;
        });
    }

    function updateStats(data) {
        if(data.length === 0) return;
        let avgScore = data.reduce((sum, item) => sum + item.score, 0) / data.length;
        
        animateValue('stat-total', 0, data.length, 1000);
        animateValueFloat('stat-highest', 0, data[0].score, 1000, 4);
        animateValueFloat('stat-avg', 0, avgScore, 1000, 4);
        
        const winnerEl = document.getElementById('stat-winner');
        if(winnerEl) winnerEl.innerText = "🏆 " + data[0].name;
    }

    function animateValue(id, start, end, duration) {
        let obj = document.getElementById(id);
        if(!obj) return;
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            obj.innerHTML = Math.floor(progress * (end - start) + start);
            if (progress < 1) window.requestAnimationFrame(step);
        };
        window.requestAnimationFrame(step);
    }

    function animateValueFloat(id, start, end, duration, decimals) {
        let obj = document.getElementById(id);
        if(!obj) return; 
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            let current = (progress * (end - start)) + start;
            obj.innerHTML = current.toFixed(decimals);
            if (progress < 1) { 
                window.requestAnimationFrame(step); 
            } else {
                obj.innerHTML = end.toFixed(decimals);
            }
        };
        window.requestAnimationFrame(step);
    }
});