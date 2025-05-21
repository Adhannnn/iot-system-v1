<div class="container mx-auto px-4 py-6 space-y-6">
    <script>
        window.roomId = @json($roomId);
    </script>

    @if ($room)
        <h2 class="text-2xl text-center font-semibold">{{ $room->name }}</h2>

        <!-- Sensor Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- DHT22 --}}
            <div class="border rounded-lg p-4 shadow">
                <h3 class="text-lg font-bold">DHT 22</h3>
                <p>Temperature: <span id="temp">-</span> °C &nbsp; Humidity: <span id="humidity">-</span> %RH</p>
                <canvas id="dhtChart" height="200px" style="max-height: 200px"></canvas>
            </div>

            {{-- MQ-7 --}}
            <div class="border rounded-lg p-4 shadow">
                <h3 class="text-lg font-bold">MQ - 7</h3>
                <p>Carbon Monoxide: <span id="co">-</span> ppm</p>
                <canvas id="mq7Chart" height="200px" style="max-height: 200px"></canvas>
            </div>

            {{-- GP2Y1010AU0F --}}
            <div class="border rounded-lg p-4 shadow">
                <h3 class="text-lg font-bold">GP2Y1010AU0F</h3>
                <p>Dust Particles: <span id="dust">-</span> µg/m³</p>
                <canvas id="dustChart" height="200px" style="max-height: 200px"></canvas>
            </div>

            {{-- MQ-135 --}}
            <div class="border rounded-lg p-4 shadow">
                <h3 class="text-lg font-bold">MQ - 135</h3>
                <p>Air Quality: <span id="air">-</span> ppm</p>
                <canvas id="mq135Chart" height="200px" style="max-height: 200px"></canvas>
            </div>
        </div>
    @else
        @include('components.choose-room')
    @endif
</div>

@push('scripts')
    <script>
        let currentRoomId = @json($roomId);
        console.log('RoomShow script loaded, currentRoomId:', currentRoomId);

        let dhtChart, mq7Chart, dustChart, mq135Chart;

        function initCharts() {
            if (dhtChart) dhtChart.destroy();
            if (mq7Chart) mq7Chart.destroy();
            if (dustChart) dustChart.destroy();
            if (mq135Chart) mq135Chart.destroy();

            dhtChart = new Chart(document.getElementById('dhtChart'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                            label: 'Temperature',
                            data: [],
                            borderColor: '#ec4899',
                            backgroundColor: '#ec489980',
                        },
                        {
                            label: 'Humidity',
                            data: [],
                            borderColor: '#8b5cf6',
                            backgroundColor: '#8b5cf680',
                        }
                    ]
                },
                options: chartOptions
            });
            mq7Chart = new Chart(document.getElementById('mq7Chart'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'CO (ppm)',
                        data: [],
                        borderColor: '#10b981',
                        backgroundColor: '#10b98180',
                    }]
                },
                options: chartOptions
            });
            dustChart = new Chart(document.getElementById('dustChart'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Dust (µg/m³)',
                        data: [],
                        borderColor: '#f59e0b',
                        backgroundColor: '#f59e0b80',
                    }]
                },
                options: chartOptions
            });
            mq135Chart = new Chart(document.getElementById('mq135Chart'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Air Quality (ppm)',
                        data: [],
                        borderColor: '#3b82f6',
                        backgroundColor: '#3b82f680',
                    }]
                },
                options: chartOptions
            });
        }

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
        };

        initCharts();

        function subscribeToRoom(roomId) {
            if (!roomId) {
                console.warn('subscribeToRoom called with empty roomId');
                return;
            }

            // Leave previous channel and stop listening to avoid duplicate events
            if (window.roomChannel) {
                window.roomChannel.stopListening('.SensorDataReceived');
                Echo.leave(`room.${currentRoomId}`);
            }

            currentRoomId = roomId;

            window.roomChannel = Echo.channel(`room.${roomId}`)
                .listen('.SensorDataReceived', (event) => {
                    console.log('SensorDataReceived event:', event);
                    if (event.roomId == currentRoomId) {
                        Livewire.dispatch('SensorDataReceived', event.sensorData);
                    }
                });
        }

        subscribeToRoom(currentRoomId);

        Livewire.on('subscribeToRoom', (roomId) => {
            subscribeToRoom(roomId);
        });

        function updateSensor(sensorData) {
            if (!sensorData) return;

            if (sensorData.dht22) {
                const tempEl = document.getElementById('temp');
                const humEl = document.getElementById('humidity');
                if (tempEl) tempEl.innerText = sensorData.dht22.temperature ?? '-';
                if (humEl) humEl.innerText = sensorData.dht22.humidity ?? '-';
            }

            if (sensorData.mq7) {
                const coEl = document.getElementById('co');
                if (coEl) coEl.innerText = sensorData.mq7.co ?? '-';
            }

            if (sensorData.dust) {
                const dustEl = document.getElementById('dust');
                if (dustEl) dustEl.innerText = sensorData.dust.dust ?? '-';
            }

            if (sensorData.mq135) {
                const airEl = document.getElementById('air');
                if (airEl) airEl.innerText = sensorData.mq135.airQuality ?? '-';
            }
        }

        function pushChartData(chart, label, dataArr) {
            const MAX_POINTS = 30;

            // Only push if all values are numbers
            if (dataArr.some(val => typeof val !== 'number' || isNaN(val))) {
                // Skip pushing invalid data
                return;
            }

            chart.data.labels.push(label);
            dataArr.forEach((value, i) => {
                chart.data.datasets[i].data.push(value);
            });

            if (chart.data.labels.length > MAX_POINTS) {
                chart.data.labels.shift();
                chart.data.datasets.forEach(ds => ds.data.shift());
            }

            chart.update();
        }

        function updateCharts(sensorData) {
            const now = new Date().toLocaleTimeString();

            if (sensorData.dht22 && typeof sensorData.dht22.temperature === 'number' && typeof sensorData.dht22.humidity ===
                'number') {
                pushChartData(dhtChart, now, [
                    sensorData.dht22.temperature,
                    sensorData.dht22.humidity,
                ]);
            }

            if (sensorData.mq7 && typeof sensorData.mq7.co === 'number') {
                pushChartData(mq7Chart, now, [sensorData.mq7.co]);
            }

            if (sensorData.dust && typeof sensorData.dust.dust === 'number') {
                pushChartData(dustChart, now, [sensorData.dust.dust]);
            }

            if (sensorData.mq135 && typeof sensorData.mq135.airQuality === 'number') {
                pushChartData(mq135Chart, now, [sensorData.mq135.airQuality]);
            }
        }

        Livewire.on('SensorDataReceived', (sensorData) => {
            console.log('SensorDataReceived:', sensorData);
            updateSensor(sensorData);
            updateCharts(sensorData);
        });
    </script>
@endpush
