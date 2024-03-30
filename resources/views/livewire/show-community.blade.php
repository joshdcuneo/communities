<x-slot name="header">
    <h2
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
    >
        {{ $community->name }}
    </h2>
</x-slot>
<div>
    <p>{{ $community->description }}</p>

    @if ($community->isOwnedBy(Auth::user()))
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
            You are the owner of this community.
        </p>
    @endif
</div>
