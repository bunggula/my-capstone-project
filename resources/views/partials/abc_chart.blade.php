<!-- 📈 LARGE CHARTS CONTAINER -->
<div class="flex justify-center mt-6">
    <div class="rounded-2xl shadow-xl p-6 w-full max-w-7xl space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Residents Chart -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center h-[450px]">
                <h4 class="font-bold text-gray-700 mb-2 text-center">Residents per Barangay</h4>
                <div class="w-full h-full">
                    <canvas id="residentsChart"></canvas>
                </div>
            </div>
<!-- Completed Services Chart -->
<div class="bg-white rounded-xl shadow p-4 flex flex-col items-center h-[450px]">
    <h4 class="font-bold text-gray-700 mb-2 text-center">Completed Services</h4>
    <div class="w-full h-full">
        <canvas id="servicesChart" class="w-full h-full"></canvas>
    </div>
</div>
            <!-- Events Chart -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center h-[450px]">
                <h4 class="font-bold text-gray-700 mb-2 text-center">Barangay Events per Month</h4>
                <div class="w-full h-full">
                    <canvas id="eventsChart"></canvas>
                </div>
            </div>

<!-- Resolved Cases Chart -->
<div class="bg-white rounded-xl shadow p-4 flex flex-col">
    <h4 class="font-bold text-gray-700 mb-2 text-center">Resolved Concern per Barangay</h4>
    <div class="w-full aspect-[5/4]">
        <canvas id="resolvedChart" class="w-full h-full"></canvas>
    </div>
</div>

<!-- Proposals Chart -->
<div class="bg-white rounded-xl shadow p-4 flex flex-col">
    <h4 class="font-bold text-gray-700 mb-2 text-center">Proposals Status per Month</h4>
    <div class="w-full aspect-[16/9]">
        <canvas id="proposalsChart" class="w-full h-full"></canvas>
    </div>
</div>




    
         <!-- Announcements Chart -->
<div class="bg-white rounded-xl shadow p-4 flex flex-col">
    <h4 class="font-bold text-gray-700 mb-2 text-center">Announcements Post</h4>
    <div class="w-full aspect-[16/9]">
        <canvas id="announcementsChart" class="w-full h-full"></canvas>
    </div>
</div>


        </div>
    </div>
</div>



<!-- End Scrollable Content -->

    </main>

</div> <!-- End Flex -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

/* 🌐 GLOBAL CHART SETTINGS — uniform look for all charts */
Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = "#374151"; // gray-700
Chart.defaults.plugins.legend.position = 'bottom';
Chart.defaults.plugins.legend.labels.boxWidth = 12;
Chart.defaults.plugins.legend.labels.font = { size: 8 };
Chart.defaults.maintainAspectRatio = false;
Chart.defaults.responsive = true;

/* 🖱️ GLOBAL LEGEND BEHAVIOR — show only clicked dataset (toggle others off) */
Chart.defaults.plugins.legend.onClick = function (e, legendItem, legend) {
    const chart = legend.chart;
    const index = legendItem.datasetIndex;

    const allVisible = chart.data.datasets.every(ds => !ds.hidden);
    const clickedVisible = !chart.isDatasetVisible(index);

    chart.data.datasets.forEach((ds, i) => {
        if (i === index) {
            ds.hidden = allVisible ? false : !clickedVisible ? false : true;
        } else {
            ds.hidden = allVisible ? i !== index : true;
        }
    });

    const allHidden = chart.data.datasets.every(ds => ds.hidden);
    if (allHidden) chart.data.datasets.forEach(ds => (ds.hidden = false));

    chart.update();
};

/* 🎨 Generate dynamic color sets */
function generateColors(num, brightness = '55%') {
    const colors = [];
    for (let i = 0; i < num; i++) {
        const hue = (i * 360 / num) % 360;
        colors.push(`hsl(${hue}, 70%, ${brightness})`);
    }
    return colors;
}

/* 🧍 Residents Pie Chart — legend strikethrough + center label */
/* 🧍 Residents Pie Chart — legend strikethrough + center label */
const residentsCtx = document.getElementById('residentsChart').getContext('2d');
let selectedLegendIndex = null;

/* Compute total residents */
const residentData = @json($residentsPerBarangay);
const totalResidents = residentData.reduce((a, b) => a + b, 0);

/* Plugin for strikethrough legend text */
const legendStrikethrough = {
    id: 'legendStrikethrough',
    afterDraw(chart) {
        const ctx = chart.ctx;
        const legend = chart.legend;
        if (!legend || !legend.legendItems) return;

        legend.legendItems.forEach((item) => {
            if (item.textDecoration === 'line-through') {
                const textWidth = ctx.measureText(item.text).width;
                const y = item.y + 8; // adjust vertical alignment
                ctx.save();
                ctx.strokeStyle = 'black';
                ctx.lineWidth = 1.2;
                ctx.beginPath();
                ctx.moveTo(item.x, y);
                ctx.lineTo(item.x + textWidth, y);
                ctx.stroke();
                ctx.restore();
            }
        });
    }
};

/* Plugin for center label (shows total or selected barangay info) */
const pieCenterText = {
    id: 'pieCenterText',
    afterDraw(chart) {
        const ctx = chart.ctx;
        const { width, height } = chart;
        ctx.save();
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        if (selectedLegendIndex !== null) {
            // Display selected barangay
            const label = chart.data.labels[selectedLegendIndex];
            const value = chart.data.datasets[0].data[selectedLegendIndex];
            ctx.font = 'bold 18px Inter, sans-serif';
            ctx.fillStyle = '#111827';
            ctx.fillText(label, width / 2, height / 2 - 10);
            ctx.font = '14px Inter, sans-serif';
            ctx.fillStyle = '#4B5563';
            ctx.fillText(`${value.toLocaleString()} residents`, width / 2, height / 2 + 12);
        } else {
            // Display total residents
            ctx.font = 'bold 22px Inter, sans-serif';
            ctx.fillStyle = '#111827';
            ctx.fillText(totalResidents.toLocaleString(), width / 2, height / 2 - 10);
            ctx.font = '14px Inter, sans-serif';
            ctx.fillStyle = '#4B5563';
            ctx.fillText('Total Residents', width / 2, height / 2 + 12);
        }

        ctx.restore();
    }
};

/* Pie chart initialization */
const residentsChart = new Chart(residentsCtx, {
    type: 'pie',
    data: {
        labels: @json($barangayNames),
        datasets: [{
            data: residentData,
            backgroundColor: generateColors(@json($barangayNames).length),
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        cutout: '60%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {
           size: 9,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold' // <-- change this to your desired legend font size
        },
                    generateLabels: (chart) => {
                        const data = chart.data;
                        if (!data.labels.length) return [];
                        return data.labels.map((label, i) => {
                            const meta = chart.getDatasetMeta(0);
                            const hidden = meta.data[i] && meta.data[i].hidden;
                            const active = selectedLegendIndex === null || selectedLegendIndex === i;
                            return {
                                text: label,
                                fillStyle: chart.data.datasets[0].backgroundColor[i],
                                hidden,
                                datasetIndex: 0,
                                index: i,
                                textDecoration: !active ? 'line-through' : 'none'
                            };
                        });
                    }
                },
                onClick: function (e, legendItem, legend) {
                    const chart = legend.chart;
                    const index = legendItem.index;
                    const meta = chart.getDatasetMeta(0);
                    const allVisible = meta.data.every(seg => !seg.hidden);

                    if (allVisible) {
                        meta.data.forEach((seg, i) => (seg.hidden = i !== index));
                        selectedLegendIndex = index;
                    } else if (meta.data[index].hidden) {
                        meta.data.forEach(seg => (seg.hidden = false));
                        selectedLegendIndex = null;
                    } else {
                        meta.data[index].hidden = !meta.data[index].hidden;
                        selectedLegendIndex = meta.data[index].hidden ? null : index;
                    }

                    if (meta.data.every(seg => seg.hidden)) {
                        meta.data.forEach(seg => (seg.hidden = false));
                        selectedLegendIndex = null;
                    }

                    chart.update();
                }
            },
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.label}: ${ctx.raw} residents`
                }
            }
        }
    },
    plugins: [legendStrikethrough, pieCenterText]
});


new Chart(document.getElementById('announcementsChart'), {
    type: 'line',
    data: {
        labels: @json($months),
        datasets: [
            {
                label: 'All Barangays',
                data: @json($announcementBreakdownPerMonth['All Barangays']),
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54,162,235,0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6
            },
            {
                label: 'Specific Barangay',
                data: @json($announcementBreakdownPerMonth['Specific Barangay']),
                borderColor: '#4BC0C0',
                backgroundColor: 'rgba(75,192,192,0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6
            },
            {
                label: 'Specific Role',
                data: @json($announcementBreakdownPerMonth['Specific Role']),
                borderColor: '#FFCE56',
                backgroundColor: 'rgba(255,206,86,0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    font: {
                        size: 12,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold' // font weight: normal, bold, etc.
                    }
                }
            },
        
        },
        interaction: { mode: 'point', intersect: false },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    font: {
                        size: 12,
                        family: 'Arial, sans-serif'
                    }
                }
            },
            x: {
                ticks: {
                    autoSkip: false,
                    font: {
                        size: 12,
                        family: 'Arial, sans-serif'
                    }
                }
            }
        }
    }
});



/* ========================= EVENTS CHART (MATCHED WITH RESOLVED) ========================= */
const eventBarangays = @json($barangayNames);
const eventsPerBarangay = @json($eventsPerBarangay);
const eventColors = generateColors(eventBarangays.length);

const eventDatasets = eventBarangays.map((b, i) => ({
    label: b,
    data: eventsPerBarangay[b],
    backgroundColor: eventColors[i],
    borderRadius: 6,
    borderSkipped: false
}));

new Chart(document.getElementById('eventsChart'), {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: eventDatasets
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,   // SAME AS RESOLVED
        interaction: { mode: 'point', intersect: true },

        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                   font: {
                        size: 9,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold' // font weight: normal, bold, etc.
                    }   // SAME FONT SIZE AS RESOLVED
                }
            },
            tooltip: { mode: 'nearest', intersect: true }
        },

        scales: {
            x: {
                stacked: true,           // SAME
                ticks: { autoSkip: false },
                maxBarThickness: 30      // SAME THICKNESS AS RESOLVED
            },
            y: {
                stacked: true,           // SAME
                beginAtZero: true
            }
        }
    }
});


/* 📑 Proposals Bar Chart (Enhanced UI) */
new Chart(document.getElementById('proposalsChart'), {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [
            { 
                label: 'Pending', 
                data: @json($proposalsPerMonth['pending']), 
                backgroundColor: 'rgba(251, 191, 36, 0.8)', 
                borderRadius: 6,
                maxBarThickness: 40
            },
            { 
                label: 'Approved', 
                data: @json($proposalsPerMonth['approved']), 
                backgroundColor: 'rgba(52, 211, 153, 0.8)', 
                borderRadius: 6,
                maxBarThickness: 40
            },
            { 
                label: 'Rejected', 
                data: @json($proposalsPerMonth['rejected']), 
                backgroundColor: 'rgba(248, 113, 113, 0.8)', 
                borderRadius: 6,
                maxBarThickness: 40
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'point', intersect: false },
        scales: {
            y: { 
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.05)'
                },
                ticks: {
                    font: { size: 11 }
                }
            },
            x: { 
                stacked: false,
                ticks: {
                   font: {
                        size: 12,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold' // font weight: normal, bold, etc.
                    }
                }
            }
        },
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: { size: 13, weight: '600' },
                    padding: 15
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.75)',
                padding: 10,
                titleFont: { size: 13 },
                bodyFont: { size: 12 },
                cornerRadius: 6
            }
        }
    }
});


//* ========================= SERVICES STACKED BAR CHART ========================= */
const documentTypes = @json($documentTypes);
const months = @json($months);
const detailedData = @json($servicesPerDocumentTypeDetailed);
const colors = generateColors(documentTypes.length);

let selectedLegendIndexServices = null; // TRACK active legend

// Build datasets
const serviceDatasets = documentTypes.map((type, i) => ({
    label: type,
    data: months.map((_, monthIdx) => {
        const monthNum = monthIdx + 1;
        const monthData = detailedData[type][monthNum];
        return Object.values(monthData).reduce((a, b) => a + b, 0);
    }),
    backgroundColor: colors[i],
    borderRadius: 6,
    barThickness: 20
}));

const ctx = document.getElementById('servicesChart');
const chart = new Chart(ctx, {
    type: 'bar',
    data: { labels: months, datasets: serviceDatasets },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'point', intersect: false },

        scales: {
            x: {
                stacked: true,
                ticks: { autoSkip: false },
                grid: { display: false }
            },
            y: {
                stacked: true,
                beginAtZero: true
            }
        },

        plugins: {
            /* ---------- UPDATED LEGEND (same as Requests Chart) ---------- */
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    font: {
                       size: 9,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold'
                    },
                    generateLabels(chart) {
                        return chart.data.datasets.map((ds, i) => {
                            const meta = chart.getDatasetMeta(i);
                            const hidden = meta.hidden;
                            const active = selectedLegendIndexServices === null || selectedLegendIndexServices === i;

                            return {
                                text: ds.label,
                                fillStyle: ds.backgroundColor,
                                hidden,
                                datasetIndex: i,
                                textDecoration: !active ? 'line-through' : 'none',
                               
                            };
                        });
                    }
                },

                /* CLICK LEGEND LOGIC */
                onClick(e, legendItem, legend) {
                    const chart = legend.chart;
                    const index = legendItem.datasetIndex;
                    const meta = chart.getDatasetMeta(index);

                    const allVisible = chart.data.datasets.every((_, i) =>
                        !chart.getDatasetMeta(i).hidden
                    );

                    if (allVisible) {
                        // Show only selected
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = i !== index;
                        });
                        selectedLegendIndexServices = index;
                    } else if (meta.hidden) {
                        // Restore all
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = false;
                        });
                        selectedLegendIndexServices = null;
                    } else {
                        // Hide selected (show others)
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = i === index;
                        });
                        selectedLegendIndexServices = null;
                    }

                    chart.update();
                }
            },

            /* ------------------ YOUR CUSTOM TOOLTIP (unchanged) ------------------ */
          tooltip: {
    enabled: false,
    external: function(context) {
        let tooltipEl = document.getElementById('chartjs-tooltip');

        // Create tooltip if missing
        if (!tooltipEl) {
            tooltipEl = document.createElement('div');
            tooltipEl.id = 'chartjs-tooltip';
            tooltipEl.style.position = 'absolute';
            tooltipEl.style.background = 'rgba(0,0,0,0.85)';
            tooltipEl.style.borderRadius = '8px';
            tooltipEl.style.color = '#fff';
            tooltipEl.style.padding = '8px';
            tooltipEl.style.maxWidth = '260px';
            tooltipEl.style.fontSize = '12px';
            tooltipEl.style.zIndex = '9999';
            tooltipEl.style.pointerEvents = 'auto';
            tooltipEl.style.transition = 'opacity .15s ease';
            tooltipEl.style.opacity = 0;

            const scrollBox = document.createElement('div');
            scrollBox.id = 'tooltip-content';
            scrollBox.style.maxHeight = '200px';
            scrollBox.style.overflowY = 'auto';

            tooltipEl.appendChild(scrollBox);
            document.body.appendChild(tooltipEl);

            /* ---------- IMPORTANT: LOCK TOOLTIP WHEN HOVERED ---------- */
            tooltipEl.addEventListener('mouseenter', () => {
                tooltipEl.dataset.locked = "true";
            });

            tooltipEl.addEventListener('mouseleave', () => {
                tooltipEl.dataset.locked = "false";
                tooltipEl.style.opacity = 0; // hide only when mouse leaves tooltip
            });

            /* Prevent page scroll */
            scrollBox.addEventListener('wheel', e => {
                e.stopPropagation();
            }, { passive: false });
        }

        const { chart, tooltip } = context;
        const scrollBox = tooltipEl.querySelector('#tooltip-content');

        /* ---------- HIDE ONLY IF NOT LOCKED AND NOT HOVERED ---------- */
        if (tooltip.opacity === 0) {
            if (tooltipEl.dataset.locked !== "true") {
                tooltipEl.style.opacity = 0;
            }
            return;
        }

        /* ---------- BUILD CONTENT ---------- */
        const monthIdx = tooltip.dataPoints[0].dataIndex;
        let innerHtml = '';

        tooltip.dataPoints.forEach(point => {
            const type = point.dataset.label;
            const color = point.dataset.backgroundColor;
            const monthNum = monthIdx + 1;
            const barangayData = detailedData[type][monthNum];
            const total = Object.values(barangayData).reduce((a, b) => a + b, 0);

            innerHtml += `
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:10px;height:10px;border-radius:50%;background:${color};"></div>
                    <b>${type} (Total: ${total})</b>
                </div>
                <hr style="border:0;border-top:1px solid #777;margin:4px 0;">`;

            for (const [barangay, count] of Object.entries(barangayData)) {
                innerHtml += `<div>• ${barangay}: ${count}</div>`;
            }
        });

        scrollBox.innerHTML = innerHtml;

        /* ---------- POSITIONING ---------- */
        const pos = chart.canvas.getBoundingClientRect();
        tooltipEl.style.left = pos.left + window.pageXOffset + tooltip.caretX + 'px';
        tooltipEl.style.top = pos.top + window.pageYOffset + tooltip.caretY + 20 + 'px';

        tooltipEl.style.opacity = 1;
    }
},

           
        }
    }
});

/* ========================= RESOLVED CASES CHART ========================= */
const resolvedBarangays = @json($barangayNames);
const resolvedCountsPerMonth = @json($resolvedDataPerMonth['resolvedCounts']);
const resolvedColors = generateColors(resolvedBarangays.length);
const resolvedDatasets = resolvedBarangays.map((b, i) => ({
    label: b,
    data: resolvedCountsPerMonth[b],
    backgroundColor: resolvedColors[i],
    borderRadius: 4
}));

new Chart(document.getElementById('resolvedChart'), {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: resolvedDatasets
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'point', intersect: true },
        plugins: {
            legend: { position: 'bottom',
            labels: {
                    font: {
                        size: 9,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold' // font weight: normal, bold, etc.
                    } // <-- legend font size
                }
            },
            tooltip: { mode: 'nearest', intersect: true }
        },
        scales: {
            x: {
                stacked: true,
                ticks: { autoSkip: false },
                maxBarThickness: 30
            },
            y: { stacked: true, beginAtZero: true }
        }
    }
});

</script>