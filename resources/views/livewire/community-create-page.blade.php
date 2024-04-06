<x-slot name="header">
    <h2
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
    >
        {{ __('Communities') }}
    </h2>
</x-slot>

<div>
    <div class="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
        <x-form-section submit="save">
            <x-slot name="title">
                {{ __('Create a Community') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Create a new community to build connection and share information.') }}
            </x-slot>

            <x-slot name="form">
                <div class="col-span-6 sm:col-span-4">
                    <x-label for="name" value="{{ __('Name') }}" />
                    <x-input
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model="name"
                    />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-label
                        for="description"
                        value="{{ __('Description') }}"
                    />
                    <x-input
                        id="description"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model="description"
                    />
                    <x-input-error for="description" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="actions">
                <x-action-message class="me-3" on="saved">
                    {{ __('Saved.') }}
                </x-action-message>

                <x-button wire:loading.attr="disabled">
                    {{ __('Save') }}
                </x-button>
            </x-slot>
        </x-form-section>
    </div>
</div>
