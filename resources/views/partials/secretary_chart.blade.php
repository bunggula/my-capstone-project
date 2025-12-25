<!-- 📈 LARGE CHARTS CONTAINER -->
<div class="flex justify-center mt-6">
    <div class="rounded-2xl shadow-xl p-6 w-full max-w-7xl space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Residents Chart -->
        <!-- Residents Chart -->
<div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
    <h4 class="font-bold text-gray-700 mb-2 text-center">Residents</h4>
    <div class="w-[250px] h-[250px] flex items-center justify-center">
        <canvas id="residentsStatusChart" class="w-full h-full"></canvas>
    </div>
</div>

            <!-- Document Requests Chart -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center h-[400px]">
                <h4 class="font-bold text-gray-700 mb-2 text-center">Document Requests per Month</h4>
                <div class="w-full h-full">
                    <canvas id="requestsChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Events Chart -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center h-[400px]">
                <h4 class="font-bold text-gray-700 mb-2 text-center">Events (Approved vs Rejected)</h4>
                <div class="w-full h-full">
                    <canvas id="eventsChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Announcements Chart -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center h-[400px]">
                <h4 class="font-bold text-gray-700 mb-2 text-center">Announcements per Month</h4>
                <div class="w-full h-full">
                    <canvas id="announcementsChart" class="w-full h-full"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- 📈 Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ==================== GLOBAL SETTINGS ====================
Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
Chart.defaults.color = "#374151";
Chart.defaults.plugins.tooltip.backgroundColor = "rgba(17, 24, 39, 0.9)";
Chart.defaults.plugins.tooltip.titleFont = { weight: '600' };
Chart.defaults.plugins.tooltip.padding = 10;
Chart.defaults.plugins.legend.labels.boxWidth = 12;
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.maintainAspectRatio = false;
Chart.defaults.responsive = true;

// ==================== DYNAMIC COLORS ====================
function generateColors(num, brightness = '55%') {
    const colors = [];
    for (let i = 0; i < num; i++) {
        const hue = (i * 360 / num) % 360;
        colors.push(`hsl(${hue}, 70%, ${brightness})`);
    }
    return colors;
}

// ==================== DATA FROM BACKEND ====================
const months = @json($months);
const residentCategories = @json($residentCategories);
const eventsApproved = @json($eventsApproved);
const eventsRejected = @json($eventsRejected);
const eventsPending = @json($eventsPending);
const announcementsPerMonth = @json($announcementsPerMonth);
const requestsData = @json($requestsChartData);

// ==================== PIE CHART: RESIDENTS ====================
const residentsCtx = document.getElementById('residentsStatusChart').getContext('2d');

// Filter out 'Total Residents' key
const filteredCategories = Object.fromEntries(
    Object.entries(residentCategories).filter(([key, _]) => key !== 'Total Residents')
);

const residentLabels = Object.keys(filteredCategories);
const residentData = Object.values(filteredCategories);
const totalResidents = @json($totalResidents);
let selectedLegendIndex = null;

// Plugin: center label (fixed centering)
const pieCenterText = {
    id: 'pieCenterText',
    afterDraw(chart) {
        const { ctx, chartArea: { left, right, top, bottom } } = chart;
        const centerX = (left + right) / 2;
        const centerY = (top + bottom) / 2;

        ctx.save();
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';

        if (selectedLegendIndex !== null) {
            const label = chart.data.labels[selectedLegendIndex];
            const value = chart.data.datasets[0].data[selectedLegendIndex];
            ctx.font = 'bold 12px Inter, sans-serif';
            ctx.fillStyle = '#111827';
            ctx.fillText(label, centerX, centerY - 8);
            ctx.font = '12px Inter, sans-serif';
            ctx.fillStyle = '#4B5563';
            ctx.fillText(`${value.toLocaleString()} residents`, centerX, centerY + 10);
        } else {
            ctx.font = 'bold 18px Inter, sans-serif';
            ctx.fillStyle = '#111827';
            ctx.fillText(totalResidents.toLocaleString(), centerX, centerY - 8);
            ctx.font = '12px Inter, sans-serif';
            ctx.fillStyle = '#4B5563';
            ctx.fillText('Total Residents', centerX, centerY + 10);
        }
        ctx.restore();
    }
};

// Plugin: legend strikethrough (cleaned)
const legendStrikethrough = {
    id: 'legendStrikethrough',
    afterDraw(chart) {
        const ctx = chart.ctx;
        const legend = chart.legend;
        if (!legend || !legend.legendItems) return;

        ctx.save();
        ctx.strokeStyle = '#111827';
        ctx.lineWidth = 1.2;

        legend.legendItems.forEach(item => {
            if (item.textDecoration === 'line-through') {
                const textWidth = ctx.measureText(item.text).width;
                const lineY = item.y + 7;
                ctx.beginPath();
                ctx.moveTo(item.x, lineY);
                ctx.lineTo(item.x + textWidth, lineY);
                ctx.stroke();
            }
        });
        ctx.restore();
    }
};

// Pie Chart
new Chart(residentsCtx, {
    type: 'pie',
    data: {
        labels: residentLabels,
        datasets: [{
            data: residentData,
            backgroundColor: generateColors(residentLabels.length),
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
    
        cutout: '50%',
        plugins: {
            legend: {
                position: 'bottom',
                 align: 'center', 
                labels: {
                    boxWidth: 16,
                    usePointStyle: false,
                    font: {
                     size: 9,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold'      // optional (normal, bold, etc.)
                },
                    generateLabels: chart => {
                        return chart.data.labels.map((label, i) => {
                            const meta = chart.getDatasetMeta(0);
                            const hidden = meta.data[i].hidden;
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
                onClick(e, legendItem, legend) {
                    const chart = legend.chart;
                    const index = legendItem.index;
                    const meta = chart.getDatasetMeta(0);
                    const allVisible = meta.data.every(seg => !seg.hidden);

                    if (allVisible) {
                        meta.data.forEach((seg, i) => seg.hidden = i !== index);
                        selectedLegendIndex = index;
                    } else if (selectedLegendIndex === index) {
                        meta.data.forEach(seg => seg.hidden = false);
                        selectedLegendIndex = null;
                    } else {
                        meta.data.forEach((seg, i) => seg.hidden = i !== index);
                        selectedLegendIndex = index;
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
    plugins: [pieCenterText, legendStrikethrough]
});


// ==================== REQUESTS BAR CHART ====================
const typeKeys = Object.keys(requestsData);
let selectedLegendIndexRequests = null; // track selected dataset

const datasets = typeKeys.map((type, index) => {
    const hue = (index * 50) % 360;
    const color = `hsl(${hue}, 70%, 60%)`;
    return {
        label: type,
      data: months.map((_, i) => requestsData[type][i + 1] || 0),


        backgroundColor: color,
        borderRadius: 8,
        barThickness: 20
    };
});

const requestsCtx = document.getElementById('requestsChart').getContext('2d');
const requestsChart = new Chart(requestsCtx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: datasets
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: false, // ✅ make legend boxes square
                    boxWidth: 14,
                    font: {
                      size: 9,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold'       // optional (normal, bold, etc.)
                },
                    generateLabels: chart => {
                        return chart.data.datasets.map((ds, i) => {
                            const hidden = chart.getDatasetMeta(i).hidden;
                            const active = selectedLegendIndexRequests === null || selectedLegendIndexRequests === i;
                            return {
                                text: ds.label,
                                fillStyle: ds.backgroundColor,
                                hidden,
                                datasetIndex: i,
                                textDecoration: !active ? 'line-through' : 'none'
                            };
                        });
                    }
                },
                onClick: function(e, legendItem, legend) {
                    const chart = legend.chart;
                    const index = legendItem.datasetIndex;
                    const meta = chart.getDatasetMeta(index);
                    const allVisible = chart.data.datasets.every((_, i) => !chart.getDatasetMeta(i).hidden);

                    if (allVisible) {
                        // Show only selected dataset
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = i !== index;
                        });
                        selectedLegendIndexRequests = index;
                    } else if (meta.hidden) {
                        // Restore all datasets
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = false;
                        });
                        selectedLegendIndexRequests = null;
                    } else {
                        // Hide selected dataset (restore all others)
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = i === index;
                        });
                        selectedLegendIndexRequests = null;
                    }

                    chart.update();
                }
            },
            title: {
                display: true,
                text: ''
            }
        },
        interaction: { mode: 'point', intersect: false },
       scales: {
    x: {
        stacked: true, // ✅ enable stacking sa X-axis
        grid: { display: false },
        offset: true // optional, para may space sa dulo
    },
    y: {
        stacked: true, // ✅ enable stacking sa Y-axis
        beginAtZero: true,
        grid: { color: 'rgba(229,231,235,0.3)' }
    }
},

        animation: { duration: 1000, easing: 'easeOutQuart' }
    }
});

// ==================== EVENTS BAR CHART ====================
// 🎨 Define consistent colors for each status
const approvedColor = '#34D399'; // green
const rejectedColor = '#F87171'; // red
const pendingColor  = '#FBBF24'; // yellow

new Chart(document.getElementById('eventsChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Approved',
                data: months.map((_, i) => eventsApproved[i + 1] || 0),
                backgroundColor: approvedColor,
                borderRadius: 8,
                barThickness: 20
            },
            {
                label: 'Rejected',
                data: months.map((_, i) => eventsRejected[i + 1] || 0),
                backgroundColor: rejectedColor,
                borderRadius: 8,
                barThickness: 20
            },
            {
                label: 'Pending',
                data: months.map((_, i) => eventsPending[i + 1] || 0),
                backgroundColor: pendingColor,
                borderRadius: 8,
                barThickness: 20
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'rectRounded', // ✅ box-style legend
                    boxWidth: 15,
                    boxHeight: 15,
                    padding: 15,
                    font: {
                      size: 10,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold'
                    }
                },
                onClick: (e, legendItem, legend) => {
                    const chart = legend.chart;
                    const index = legendItem.datasetIndex;

                    // ✅ Toggle visibility (show one dataset at a time)
                    const meta = chart.getDatasetMeta(index);
                    const allVisible = chart.data.datasets.every((_, i) => !chart.getDatasetMeta(i).hidden);

                    if (allVisible) {
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = i !== index;
                        });
                    } else if (!meta.hidden) {
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = false;
                        });
                    } else {
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = i !== index;
                        });
                    }

                    chart.update();
                }
            },
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.dataset.label}: ${ctx.raw} events`
                }
            }
        },
        interaction: { mode: 'point', intersect: false },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, grid: { color: 'rgba(229,231,235,0.3)' } }
        },
        animation: { duration: 1000, easing: 'easeOutQuart' }
    }
});


// ==================== ANNOUNCEMENTS LINE CHART ====================
const announcementsColors = generateColors(months.length);
new Chart(document.getElementById('announcementsChart'),{
    type:'line',
    data:{
        labels:months,
        datasets:[{
            label:'Announcements',
            data:months.map((_,i)=>announcementsPerMonth[i+1]||0),
            borderColor:'#FBBF24', 
           backgroundColor: 'rgba(251,191,36,0.2)',
            fill:true,
            tension:0.35,
            borderWidth:3,
            pointRadius:5,
            pointHoverRadius:8,
            pointBackgroundColor:'#FBBF24',
            pointBorderColor:'#fff'
        }]
    },
    options:{
        responsive:true,
        plugins:{legend:{display:false}},
        interaction:{mode:'point',intersect:false},
        scales:{y:{beginAtZero:true,grid:{color:'rgba(229,231,235,0.3)'}},x:{grid:{display:false}}},
        animation:{duration:1200,easing:'easeOutQuart'}
    }
});
</script>
