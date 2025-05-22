<div>
    <h1 class="text-xl font-semibold mb-4">🔧 Settings</h1>

    <div x-data="{ enabled: localStorage.getItem('alert-sound-enabled') === 'yes' }">
        <label class="flex items-center space-x-2">
            <input type="checkbox" x-model="enabled" @change="
                if (enabled) {
                    const audio = document.getElementById('alert-sound');
                    audio.play().then(() => {
                        audio.pause();
                        audio.currentTime = 0;
                        localStorage.setItem('alert-sound-enabled', 'yes');
                        alert('🔊 Sound enabled!');
                    }).catch(err => {
                        enabled = false;
                        localStorage.setItem('alert-sound-enabled', 'no');
                        alert('❌ Autoplay blocked.');
                    });
                } else {
                    localStorage.setItem('alert-sound-enabled', 'no');
                }
            ">
            <span class="text-sm">Enable Alert Sound</span>
        </label>
        <audio id="alert-sound" preload="auto" src="{{ asset('audio/siren-alert-96052.mp3') }}"></audio>
    </div>
</div>
