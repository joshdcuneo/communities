<x-slot name="header">
    <h2
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
    >
        {{ __('Dashboard') }}
    </h2>
</x-slot>

<div class="py-12">
    <section class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
            class="overflow-hidden bg-white shadow-xl sm:rounded-lg dark:bg-gray-800"
        >
            <div class="p-6 lg:p-8">
                <h2
                    class="text-2xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                >
                    Welcome to your dashboard
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    Manage your communities and posts.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex flex-row gap-2 p-6 lg:p-8">
                <x-link-button href="{{ route('community.create') }}">
                    Create a new community
                </x-link-button>
            </div>
        </div>
        <x-section-border />
    </section>

    <section class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <ul class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-8">
            @foreach ($communities as $community)
                <a
                    href="{{ route('community.show', $community->id) }}"
                    class="contents"
                >
                    <li
                        class="overflow-hidden bg-white p-6 shadow-xl transition-colors hover:bg-gray-100 sm:rounded-lg lg:p-8 dark:bg-gray-800 dark:hover:bg-slate-700"
                    >
                        <h2
                            class="mb-2 text-2xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                        >
                            {{ $community->name }}
                        </h2>
                        <p class="">{{ $community->description }}</p>
                        @if ($community->isOwnedBy(Auth::user()))
                            <p
                                class="mt-2 text-sm text-gray-600 dark:text-gray-300"
                            >
                                You are the owner of this community.
                            </p>
                        @endif
                    </li>
                </a>
            @endforeach
        </ul>
    </section>
</div>
