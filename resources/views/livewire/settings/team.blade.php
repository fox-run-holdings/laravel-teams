<div>
    <div class="flex flex-col gap-4">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Settings') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Manage your team settings') }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>

        @if(is_null($team_id))
            You are not part of a team yet! Create a new one:
            <form wire:submit.prevent="createTeam">
                <flux:input
                    type="text"
                    placeholder="{{ __('Team Name') }}"
                    wire:model.live="team_name"
                    class="w-full"
                />
                @error('team_name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                <flux:button class="mt-4" type="submit" variant="primary">{{ __('Create Team') }}</flux:button>
            </form>
        @else
            {{ $team->name }}
        @endif
    </div>
</div> 