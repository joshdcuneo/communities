<x-slot name="header">
    <h2
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
    >
        {{ $community->name }}
    </h2>
</x-slot>
<div>
    <p>{{ $community->description }}</p>
</div>
