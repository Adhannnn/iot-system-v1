<div class="container mx-auto px-4 py-6 space-y-6">
    <script>
        window.roomId = @json($roomId);
    </script>

    @if ($room)
        <h2 class="text-2xl text-center font-semibold">{{ $room->name }}</h2>

        <div x-data="{ status: '{{ strtolower($room->status) }}' }" x-init="// Pastikan status awal lowercase
        status = status.toLowerCase();
        
        // Debugging
        console.log('Initial status:', status);
        
        // Pasang event listener
        window.addEventListener('status-indicator-update', e => {
            const newStatus = String(e.detail).toLowerCase().trim();
            console.log('New status received:', newStatus);
            status = newStatus;
        
            // Force update UI
            $nextTick(() => {
                status = status;
            });
        });" class="flex justify-center my-2 pb-3">
            <template x-if="status === 'normal'">
                <div class="text-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-green-500 border border-black mr-2"></span>
                    <span class="font-medium text-black">Normal</span>
                </div>
            </template>

            <template x-if="status === 'smoke'">
                <div class="text-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-yellow-500 border border-black mr-2"></span>
                    <span class="font-medium text-black">Smoke !</span>
                </div>
            </template>

            <template x-if="status === 'fire'">
                <div class="text-center">
                    <span class="inline-block w-3 h-3 rounded-full bg-red-500 border border-black mr-2"></span>
                    <span class="font-medium text-black">FIRE !!!!</span>
                </div>
            </template>
        </div>

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

        <div class="flex justify-end gap-4 mt-6">
            {{-- Export CSV --}}
            <a href="{{ route('rooms.export', ['room' => $roomId]) }}"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                📤 Export CSV
            </a>

            {{-- Delete Data --}}
            <form id="deleteRoomForm" action="{{ route('rooms.destroy', ['room' => $roomId]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                    🗑️ Delete Room
                </button>
            </form>
        </div>
    @else
        @include('components.choose-room')
    @endif

    <audio id="alert-sound" preload="auto" src="{{ asset('audio/siren-alert-96052.mp3') }}" type="audio/mpeg"></audio>
</div>

@push('scripts')
    <script>
        console.log("Room : ")

        let previousStatus = 'normal';

        document.getElementById('deleteRoomForm').addEventListener('submit', function(event) {
            event.preventDefault();
            confirmDelete();
        });

        function playAlertSound() {
            const audio = document.getElementById('alert-sound');
            audio.currentTime = 0; // Reset to start
            audio.play();
        }

        function showStatusAlert(status) {
            let title, text, icon;

            if (status === 'fire') {
                title = '🔥 FIRE DETECTED! 🔥';
                text = 'Emergency! Fire has been detected in the room!';
                icon = 'error';
            } else if (status === 'smoke') {
                title = '⚠️ SMOKE DETECTED! ⚠️';
                text = 'Warning: Smoke has been detected in the room!';
                icon = 'warning';
            } else {
                return; // Tidak perlu alert untuk status normal
            }

            playAlertSound();

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false,
                backdrop: true
            });
        }


        function confirmDelete() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteRoomForm').submit();
                }
            });
        }

        let currentRoomId = @json($roomId);
        let dhtChart, mq7Chart, dustChart, mq135Chart;

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
        };

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
                            backgroundColor: '#ec489980'
                        },
                        {
                            label: 'Humidity',
                            data: [],
                            borderColor: '#8b5cf6',
                            backgroundColor: '#8b5cf680'
                        },
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
                        backgroundColor: '#10b98180'
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
                        backgroundColor: '#f59e0b80'
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
                        backgroundColor: '#3b82f680'
                    }]
                },
                options: chartOptions
            });
        }

        function pushChartData(chart, label, dataArr) {
            const MAX_POINTS = 30;
            if (dataArr.some(val => typeof val !== 'number' || isNaN(val))) return;

            chart.data.labels.push(label);
            dataArr.forEach((value, i) => chart.data.datasets[i].data.push(value));

            if (chart.data.labels.length > MAX_POINTS) {
                chart.data.labels.shift();
                chart.data.datasets.forEach(ds => ds.data.shift());
            }

            chart.update();
        }

        function updateSensor(sensorData) {
            if (!sensorData) return;

            if (sensorData.dht22) {
                document.getElementById('temp').innerText = sensorData.dht22.temperature ?? '-';
                document.getElementById('humidity').innerText = sensorData.dht22.humidity ?? '-';
            }

            if (sensorData.mq7) {
                document.getElementById('co').innerText = sensorData.mq7.co ?? '-';
            }

            if (sensorData.dust) {
                document.getElementById('dust').innerText = sensorData.dust.dust ?? '-';
            }

            if (sensorData.mq135) {
                document.getElementById('air').innerText = sensorData.mq135.air_quality ?? '-';
            }
        }

        function updateCharts(sensorData) {
            const now = new Date().toLocaleTimeString();

            if (sensorData.dht22) {
                pushChartData(dhtChart, now, [
                    sensorData.dht22.temperature,
                    sensorData.dht22.humidity,
                ]);
            }

            if (sensorData.mq7) {
                pushChartData(mq7Chart, now, [sensorData.mq7.co]);
            }

            if (sensorData.dust) {
                pushChartData(dustChart, now, [sensorData.dust.dust]);
            }

            if (sensorData.mq135) {
                pushChartData(mq135Chart, now, [sensorData.mq135.air_quality]);
            }
        }

        // Subscribe echo channel
        function subscribeToRoom(roomId) {

            if (!roomId) return;

            if (window.roomChannel) {
                window.roomChannel.stopListening('.SensorDataReceived');
                Echo.leave(`room.${currentRoomId}`);
            }

            currentRoomId = roomId;

            window.roomChannel = Echo.channel(`room.${roomId}`)
                .listen('.SensorDataReceived', (event) => {

                    const newStatus = String(event.status).toLowerCase().trim();

                    window.dispatchEvent(new CustomEvent('status-indicator-update', {
                        detail: newStatus,
                        bubbles: true,
                    }));

                    if ((newStatus === 'fire' || newStatus === 'smoke') && previousStatus !== newStatus) {
                showStatusAlert(newStatus);
            }
            
            previousStatus = newStatus;


                    // console.log("Realtime Status Received:", e.status);
                    Livewire.dispatch('sensor-data-updated', event.sensorData);
                    // Livewire will already handle data, no need to do anything here


                });
        }

        Livewire.on('subscribeToRoom', (roomId) => {
            subscribeToRoom(roomId);
        });

        Livewire.on('sensor-data-updated', (sensorData) => {
            updateSensor(sensorData);
            updateCharts(sensorData);
        });

        initCharts();
        subscribeToRoom(currentRoomId);
    </script>
@endpush
