<div class="py-12">
    <section class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <x-slot name="header">
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                {{ $community->name }}
            </h2>
        </x-slot>
        <div
            class="space-y-6 overflow-hidden bg-white p-6 shadow-xl sm:rounded-lg lg:p-8 dark:bg-gray-800"
        >
            <p>{{ $community->description }}</p>

            @if ($community->users?->isNotEmpty())
                <section>
                    <h3
                        class="my-2 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                    >
                        Members
                    </h3>
                    <ul>
                        @foreach ($community->users as $member)
                            <li>
                                {{ $member->is(Auth::user()) ? 'You' : $member->name }}
                                ({{ $member->email }})
                                @unless ($community->isOwnedBy($member))
                                    <button
                                        aria-label="Remove member {{ $member->email }}"
                                        wire:click="removeMember({{ $member->id }})"
                                    >
                                        x
                                    </button>
                                @endunless
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($community->invitations?->isNotEmpty())
                <section>
                    <h3
                        class="my-2 text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
                    >
                        Invitations
                    </h3>
                    <ul>
                        @foreach ($community->invitations as $invitation)
                            <li>
                                {{ $invitation->email }}
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            @if ($community->isOwnedBy(Auth::user()))
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                    You are the owner of this community.
                </p>
            @endif
        </div>
    </section>
    @if ($community->isOwnedBy(Auth::user()))
        <section class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-section-border />

            <x-form-section submit="addMemberByEmail">
                <x-slot name="title">
                    {{ __('Add Member') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Add a new member to this community.') }}
                </x-slot>

                <x-slot name="form">
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            wire:model="newMemberEmail"
                        />
                        <x-input-error for="newMemberEmail" class="mt-2" />
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <x-action-message class="me-3" on="added">
                        {{ __('Added.') }}
                    </x-action-message>

                    <x-button wire:loading.attr="disabled">
                        {{ __('Add') }}
                    </x-button>
                </x-slot>
            </x-form-section>
        </section>
    @endif
</div>
