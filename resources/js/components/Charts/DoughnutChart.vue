<template>
    <div class="w-full h-full">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue';
import {
    Chart as ChartJS,
    ArcElement,
    DoughnutController,
    Tooltip,
    Legend,
} from 'chart.js';

// Registrar componentes de Chart.js
ChartJS.register(ArcElement, DoughnutController, Tooltip, Legend);

interface Props {
    data: any;
    options?: any;
}

const props = defineProps<Props>();

const chartCanvas = ref<HTMLCanvasElement | null>(null);
let chartInstance: ChartJS | null = null;

const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom' as const,
            labels: {
                usePointStyle: true,
                padding: 20,
                font: {
                    size: 12,
                },
                generateLabels: (chart: any) => {
                    const data = chart.data;
                    if (data.labels.length && data.datasets.length) {
                        return data.labels.map((label: string, i: number) => {
                            const dataset = data.datasets[0];
                            const value = dataset.data[i];
                            const total = dataset.data.reduce((a: number, b: number) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            
                            return {
                                text: `${label} (${percentage}%)`,
                                fillStyle: dataset.backgroundColor[i],
                                strokeStyle: dataset.borderColor[i],
                                lineWidth: dataset.borderWidth,
                                hidden: false,
                                index: i,
                            };
                        });
                    }
                    return [];
                },
            },
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: 'white',
            bodyColor: 'white',
            borderColor: 'rgba(255, 255, 255, 0.1)',
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: true,
            padding: 12,
            callbacks: {
                label: (context: any) => {
                    const label = context.label || '';
                    const value = context.parsed;
                    const total = context.dataset.data.reduce((a: number, b: number) => a + b, 0);
                    const percentage = ((value / total) * 100).toFixed(1);
                    return `${label}: ${value} (${percentage}%)`;
                },
            },
        },
    },
    cutout: '60%',
    elements: {
        arc: {
            borderWidth: 2,
            hoverBorderWidth: 3,
        },
    },
};

const createChart = () => {
    if (!chartCanvas.value || !props.data) return;

    // Destruir gráfico existente si existe
    if (chartInstance) {
        chartInstance.destroy();
    }

    const mergedOptions = {
        ...defaultOptions,
        ...props.options,
    };

    chartInstance = new ChartJS(chartCanvas.value, {
        type: 'doughnut',
        data: props.data,
        options: mergedOptions,
    });
};

const updateChart = () => {
    if (!chartInstance || !props.data) return;

    chartInstance.data = props.data;
    chartInstance.update('none');
};

// Watchers
watch(() => props.data, () => {
    if (chartInstance) {
        updateChart();
    } else {
        nextTick(() => {
            createChart();
        });
    }
}, { deep: true });

watch(() => props.options, () => {
    if (chartInstance) {
        chartInstance.options = {
            ...defaultOptions,
            ...props.options,
        };
        chartInstance.update();
    }
}, { deep: true });

// Lifecycle
onMounted(() => {
    nextTick(() => {
        createChart();
    });
});

onUnmounted(() => {
    if (chartInstance) {
        chartInstance.destroy();
    }
});
</script>
