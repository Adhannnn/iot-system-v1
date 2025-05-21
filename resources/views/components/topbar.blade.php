<div class="flex justify-between items-center p-4 bg-white shadow-md">
    <div class="flex items-center space-x-2">
        <span class="font-bold text-xl">Hello Admin</span>
    </div>

    <div x-data="{ time: '' }" x-init="setInterval(() => time = (new Date()).toLocaleTimeString(), 1000)">
        <span class="font-mono text-lg text-gray-600" x-text="time"></span>
    </div>
</div>
