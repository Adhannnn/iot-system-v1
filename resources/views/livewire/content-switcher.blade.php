<div>
    @if ($selected === 'room-show' && $roomId)
        <livewire:components.room-show :room-id="$roomId" />
    @elseif ($selected === 'settings')
        <livewire:components.settings />
    @else
        <p>Please select a room.</p>
    @endif
</div>