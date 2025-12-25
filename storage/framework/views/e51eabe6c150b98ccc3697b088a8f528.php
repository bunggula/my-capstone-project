<div class="flex justify-center mt-6">
    <div class="rounded-2xl shadow-xl p-6 w-full max-w-7xl space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

     

            <!-- Residents Chart -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
                <h4 class="font-bold text-gray-700 mb-2 text-center">Residents</h4>
                <div class="w-[260px] h-[260px] flex items-center justify-center">
                    <canvas id="residentsChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Document Requests Chart -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center h-[380px]">
                <h4 class="font-bold text-gray-700 mb-2 text-center">Document Requests per Month</h4>
                <div class="w-full h-full">
                    <canvas id="requestsChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Concerns Chart -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center h-[380px]">
                <h4 class="font-bold text-gray-700 mb-2 text-center">Concerns Status</h4>
                <div class="w-full h-full">
                    <canvas id="concernsChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Proposals Chart -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center h-[380px]">
                <h4 class="font-bold text-gray-700 mb-2 text-center">Proposals (Approved vs Pending vs Rejected)</h4>
                <div class="w-full h-full">
                    <canvas id="proposalsChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Events Chart -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center h-[380px]">
                <h4 class="font-bold text-gray-700 mb-2 text-center">Events (Approved vs Rejected)</h4>
                <div class="w-full h-full">
                    <canvas id="eventsChart" class="w-full h-full"></canvas>
                </div>
            </div>

            <!-- Announcements Chart -->
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center h-[380px]">
                <h4 class="font-bold text-gray-700 mb-2 text-center">Announcements per Month</h4>
                <div class="w-full h-full">
                    <canvas id="announcementsChart" class="w-full h-full"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>




</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// 🌈 Generate dynamic colors
function generateColors(num, brightness = '55%') {
    const colors = [];
    for (let i = 0; i < num; i++) {
        const hue = (i * 360 / num) % 360;
        colors.push(`hsl(${hue}, 70%, ${brightness})`);
    }
    return colors;
}

// 📊 PHP Data
const months = <?php echo json_encode($months, 15, 512) ?>;
const residentsStatus = <?php echo json_encode($residentCategories, 15, 512) ?>;

const requestsCompleted = <?php echo json_encode($requestsChartData, 15, 512) ?>;

const eventsApproved = <?php echo json_encode($eventsApproved, 15, 512) ?>;
const eventsRejected = <?php echo json_encode($eventsRejected, 15, 512) ?>;
const eventsPending = <?php echo json_encode($eventsPending, 15, 512) ?>;
const announcementsPerMonth = <?php echo json_encode($announcementsPerMonth, 15, 512) ?>;
const proposalsApproved = <?php echo json_encode($proposalsApproved, 15, 512) ?>;
const proposalsPending = <?php echo json_encode($proposalsPending, 15, 512) ?>;
const proposalsRejected = <?php echo json_encode($proposalsRejected, 15, 512) ?>;
const approvedResidentsCount = <?php echo json_encode($approvedResidentsCount, 15, 512) ?>;


// 🌐 Global Chart Defaults
Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = "#374151"; // gray-700
Chart.defaults.plugins.legend.position = 'bottom';
Chart.defaults.plugins.legend.labels.boxWidth = 12;
Chart.defaults.plugins.legend.labels.font = { size: 10 };
Chart.defaults.maintainAspectRatio = false;
Chart.defaults.responsive = true;

// ---------- Residents Status (Doughnut) ----------
let selectedLegendIndex = null;

// Plugin for center text
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
            ctx.font = 'bold 14px Arial';
            ctx.fillStyle = '#111827';
            ctx.fillText(label, centerX, centerY - 8);
            ctx.font = '12px Arial';
            ctx.fillStyle = '#4B5563';
            ctx.fillText(`${value} residents`, centerX, centerY + 10);
        } else {
            ctx.font = 'bold 18px Arial';
            ctx.fillStyle = '#111827';
            ctx.fillText(approvedResidentsCount.toLocaleString(), centerX, centerY - 8);
            ctx.font = '12px Arial';
            ctx.fillStyle = '#4B5563';
            ctx.fillText('Total Residents', centerX, centerY + 10);
        }

        ctx.restore();
    }
};

// Plugin for solid strikethrough on inactive legend items
    const solidLegendPlugin = {
        id: 'solidLegendPlugin',
        afterDraw(chart) {
            const legend = chart.legend;
            if (!legend) return;
            const ctx = chart.ctx;
    
            legend.legendItems.forEach((item, i) => {
                const isActive = selectedLegendIndex === null || selectedLegendIndex === item.index;
                if (!isActive) {
                    const hitBox = legend.legendHitBoxes[i];
                    const textX = hitBox.left;
                    const textY = hitBox.top + hitBox.height / 2;
    
                    ctx.save();
                    ctx.strokeStyle = '#374151';
                    ctx.lineWidth = 1;
                    ctx.beginPath();
                    ctx.moveTo(textX, textY);
                    ctx.lineTo(textX + ctx.measureText(item.text).width, textY);
                    ctx.stroke();
                    ctx.restore();
                }
            });
        }
    };

// Doughnut chart with clickable legend
new Chart(document.getElementById('residentsChart'), {
    type: 'pie',
    data: {
        labels: Object.keys(residentsStatus),
        datasets: [{
            data: Object.values(residentsStatus),
            backgroundColor: generateColors(Object.keys(residentsStatus).length),
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
                usePointStyle: false,
                labels: {
                    boxWidth: 12,
                    font: {  size: 10,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold'  }
                    
                },
                onClick(e, legendItem, legend) {
                    const chart = legend.chart;
                    const index = legendItem.index;
                    const meta = chart.getDatasetMeta(0);

                    if (selectedLegendIndex === index) {
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
    plugins: [pieCenterText, solidLegendPlugin]
});

// ---------- Document Requests (Bar) ----------


// ==================== REQUESTS BAR CHART ====================
const requestsData = <?php echo json_encode($requestsChartData, 15, 512) ?>;
const typeKeys = Object.keys(requestsData);
let selectedLegendIndexRequests = null;

// Build datasets with uniform styling
const datasets = typeKeys.map((type, index) => {
    const hue = (index * 50) % 360;
    const color = `hsl(${hue}, 70%, 60%)`;

    return {
        label: type,
        data: months.map((_, i) => requestsData[type][i + 1] || 0),
        backgroundColor: color,
        borderRadius: 8,     // ✅ same rounded look
        barThickness: 20     // ✅ same thickness
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
                    usePointStyle: false,
                    boxWidth: 14,
                    font: {
                        size: 9,
                        family: 'Arial, sans-serif',
                        weight: 'bold'
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
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = i !== index;
                        });
                        selectedLegendIndexRequests = index;
                    } else if (meta.hidden) {
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = false;
                        });
                        selectedLegendIndexRequests = null;
                    } else {
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = i === index;
                        });
                        selectedLegendIndexRequests = null;
                    }

                    chart.update();
                }
            },

            title: { display: true, text: '' }
        },

        interaction: { mode: 'point', intersect: false },

        scales: {
            x: {
                stacked: true,
                grid: { display: false },
                offset: true
            },
            y: {
                stacked: true,
                beginAtZero: true,
                grid: { color: 'rgba(229,231,235,0.3)' }
            }
        },

        animation: {
            duration: 1000,
            easing: 'easeOutQuart'
        }
    }
});

/* ========================= CONCERNS PER MONTH CHART ========================= */
document.addEventListener('DOMContentLoaded', function() {
    const concernsPerMonth = <?php echo json_encode($concernsPerMonth, 15, 512) ?>;
    const months = <?php echo json_encode($months, 15, 512) ?>;

    const statuses = Object.keys(concernsPerMonth);
    const colors = generateColors(statuses.length);

    const datasets = statuses.map((status, i) => ({
        label: status.charAt(0).toUpperCase() + status.slice(1),
        data: Object.values(concernsPerMonth[status]),
        backgroundColor: colors[i],
          borderRadius: 4,
    }));

    const ctx = document.getElementById('concernsChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'point', intersect: true }, // match Resolved Cases chart
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 12,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold'
                        }
                    }
                },
                tooltip: { 
                    mode: 'point', 
                    intersect: true,
                    callbacks: {
                        label: ctx => `${ctx.dataset.label}: ${ctx.raw} concerns`
                    }
                }
            },
            scales: {
                x: {
                    stacked: true,
                    ticks: { autoSkip: false },
                    maxBarThickness: 30,
                    grid: { display: false }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { color: 'rgba(229,231,235,0.3)' }
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOutQuart'
            }
        }
    });
});


// ==================== EVENTS BAR CHART ====================

// 🎨 Define consistent colors for each status
const approvedColor = '#34D399'; // green
const rejectedColor = '#F87171'; // red
const pendingColor  = '#FBBF24'; // yellow

const eventsApprovedData = months.map((_, i) => eventsApproved[i + 1] || 0);
const eventsRejectedData = months.map((_, i) => eventsRejected[i + 1] || 0);
const eventsPendingData  = months.map((_, i) => eventsPending[i + 1] || 0);

let selectedLegendIndexEvents = null; // track selected dataset

const ctx = document.getElementById('eventsChart').getContext('2d');
const eventsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Approved',
                data: eventsApprovedData,
                backgroundColor: approvedColor,
                borderRadius: 8,
                barThickness: 20
            },
            {
                label: 'Rejected',
                data: eventsRejectedData,
                backgroundColor: rejectedColor,
                borderRadius: 8,
                barThickness: 20
            },
            {
                label: 'Pending',
                data: eventsPendingData,
                backgroundColor: pendingColor,
                borderRadius: 8,
                barThickness: 20
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'point',
            intersect: false
        },
        scales: {
            x: {
                grid: { display: false }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(229,231,235,0.3)' }
            }
        },
        animation: {
            duration: 1000,
            easing: 'easeOutQuart'
        },
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    pointStyle: 'rectRounded',
                    boxWidth: 15,
                    boxHeight: 15,
                    padding: 15,
                    font: {
                        size: 12,
                        family: 'Inter, Arial, sans-serif',
                        weight: 'bold'
                    }
                },
                onClick: (e, legendItem, legend) => {
                    const chart = legend.chart;
                    const index = legendItem.datasetIndex;

                    const meta = chart.getDatasetMeta(index);
                    const allVisible = chart.data.datasets.every((_, i) => !chart.getDatasetMeta(i).hidden);

                    if (allVisible) {
                        // Show only clicked dataset
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = i !== index;
                        });
                        selectedLegendIndexEvents = index;
                    } else if (meta.hidden) {
                        // Restore all datasets
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = false;
                        });
                        selectedLegendIndexEvents = null;
                    } else {
                        // Hide clicked dataset (show others)
                        chart.data.datasets.forEach((_, i) => {
                            chart.getDatasetMeta(i).hidden = i === index;
                        });
                        selectedLegendIndexEvents = null;
                    }

                    chart.update();
                }
            },

            tooltip: {
                callbacks: {
                    label: (ctx) => `${ctx.dataset.label}: ${ctx.raw} events`
                }
            }
        }
    }
});

// ---------- Announcements per Month (Line) ----------
const announcementsData = months.map((_, i) => announcementsPerMonth[i + 1] || 0);

new Chart(document.getElementById('announcementsChart'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Announcements',
            data: announcementsData,
            borderColor: '#FBBF24',
            backgroundColor: 'rgba(251,191,36,0.2)',
            fill: true,
            tension: 0.3,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true }, x: { ticks: { autoSkip: false } } }
    }
});
// ---------- Proposals (Bar) Enhanced ----------
const proposalsApprovedData = months.map((_, i) => proposalsApproved[i + 1] || 0);
const proposalsPendingData  = months.map((_, i) => proposalsPending[i + 1] || 0);
const proposalsRejectedData = months.map((_, i) => proposalsRejected[i + 1] || 0);

new Chart(document.getElementById('proposalsChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            { label: 'Approved', data: proposalsApprovedData, backgroundColor: '#10B981', borderRadius: 6, maxBarThickness: 40 },
            { label: 'Pending',  data: proposalsPendingData,  backgroundColor: '#FBBF24', borderRadius: 6, maxBarThickness: 40 },
            { label: 'Rejected', data: proposalsRejectedData, backgroundColor: '#EF4444', borderRadius: 6, maxBarThickness: 40 }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'point', intersect: false },
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: { size: 13,      // font size
                        family: 'Arial, sans-serif', // font family
                        weight: 'bold'   },
                    padding: 15
                }
            },
          
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.75)',
                padding: 10,
                titleFont: { size: 13 },
                bodyFont: { size: 12 },
                cornerRadius: 6,
                callbacks: {
                    label: ctx => `${ctx.dataset.label}: ${ctx.raw}`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.05)' },
                ticks: { font: { size: 11 } }
            },
            x: {
                ticks: { font: { size: 12, family: 'Arial, sans-serif', weight: 'bold' } }
            }
        }
    }
});



</script><?php /**PATH C:\Users\Acer\OneDrive\Desktop\Laovista\laovista\resources\views/partials/captain_chart.blade.php ENDPATH**/ ?>