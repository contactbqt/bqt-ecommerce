@props(['type' => 'success', 'message' => null])

@php
    $isSuccess = $type === 'success';

    $bgClass    = $isSuccess ? 'bg-green-100'  : 'bg-red-100';
    $borderClass= $isSuccess ? 'border-green-300' : 'border-red-300';
    $textClass  = $isSuccess ? 'text-green-800'   : 'text-red-800';
    $btnClass   = $isSuccess ? 'text-green-600 hover:text-green-800'
                             : 'text-red-600 hover:text-red-800';
@endphp

@if ($message)
    <div class="mb-4 p-4 rounded-lg {{ $bgClass }} border {{ $borderClass }} {{ $textClass }} flex items-center justify-between">
        <div class="flex items-center">
            @if($isSuccess)
                {{-- Success icon --}}
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            @else
                {{-- Error icon --}}
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            @endif
            <span>{{ $message }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="ml-4 {{ $btnClass }}">&times;</button>
    </div>
@endif
