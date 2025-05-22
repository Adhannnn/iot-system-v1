@php
    $color = 'gray';
    $label = 'Unknown';

    switch ($status) {
        case 'normal':
            $color = 'green';
            $label = 'Normal';
            break;
        case 'warning':
            $color = 'yellow';
            $label = 'Warning';
            break;
        case 'danger':
            $color = 'red';
            $label = 'Danger';
            break;
    }
@endphp

<div class="text-center">
    <span class="inline-block w-3 h-3 rounded-full bg-{{ $color }}-500 mr-2"></span>
    <span class="text-{{ $color }}-700 font-medium">{{ $label }}</span>
</div>
