<template>
    <div class="w-full h-full">
        <canvas ref="chartCanvas"></canvas>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend,
} from 'chart.js';

// Registrar componentes de Chart.js
ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    Tooltip,
    Legend
);

interface Props {
    data: any;
    options?: any;
    horizontal?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    horizontal: false,
});

const chartCanvas = ref<HTMLCanvasElement | null>(null);
let chartInstance: ChartJS | null = null;

const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
        mode: 'index' as const,
        intersect: false,
    },
    plugins: {
        legend: {
            position: 'top' as const,
            labels: {
                usePointStyle: true,
                padding: 20,
                font: {
                    size: 12,
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
        },
    },
    scales: {
        x: {
            display: true,
            grid: {
                display: true,
                color: 'rgba(0, 0, 0, 0.1)',
            },
            ticks: {
                font: {
                    size: 11,
                },
            },
        },
        y: {
            display: true,
            beginAtZero: true,
            grid: {
                display: true,
                color: 'rgba(0, 0, 0, 0.1)',
            },
            ticks: {
                font: {
                    size: 11,
                },
            },
        },
    },
    elements: {
        bar: {
            borderRadius: 4,
            borderSkipped: false,
        },
    },
};

const createChart = () => {
    if (!chartCanvas.value || !props.data) return;

    // Destruir gráfico existente si existe
    if (chartInstance) {
        chartInstance.destroy();
    }

    let mergedOptions = {
        ...defaultOptions,
        ...props.options,
    };

    // Ajustar opciones para gráfico horizontal
    if (props.horizontal) {
        mergedOptions = {
            ...mergedOptions,
            indexAxis: 'y' as const,
            scales: {
                ...mergedOptions.scales,
                x: {
                    ...mergedOptions.scales.x,
                    beginAtZero: true,
                },
                y: {
                    ...mergedOptions.scales.y,
                    beginAtZero: false,
                },
            },
        };
    }

    chartInstance = new ChartJS(chartCanvas.value, {
        type: 'bar',
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
        let mergedOptions = {
            ...defaultOptions,
            ...props.options,
        };

        if (props.horizontal) {
            mergedOptions = {
                ...mergedOptions,
                indexAxis: 'y' as const,
            };
        }

        chartInstance.options = mergedOptions;
        chartInstance.update();
    }
}, { deep: true });

watch(() => props.horizontal, () => {
    // Recrear el gráfico cuando cambia la orientación
    nextTick(() => {
        createChart();
    });
});

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
