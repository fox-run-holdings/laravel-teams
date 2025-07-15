<div>
    <div class="flex flex-col gap-4">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ $team->name .  __(' Settings') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Manage your team settings') }}</flux:subheading>
            <flux:separator variant="subtle"/>
        </div>
        <x-banner.error />
        <x-banner.success />
        @if(is_null($team_id))
            You are not part of a team yet! Create a new one:
        @else
            <x-teams.layout>
        @endif
        <form wire:submit.prevent="saveTeam">
            <flux:heading class="mb-3 text-xl">
                Team Name
            </flux:heading>
            <flux:input.group>
                <flux:input type="text" placeholder="{{ __('Team Name') }}" wire:model.live="team_name" class="w-full" />
                <flux:button class="cursor-pointer" type="submit" variant="primary">{{ __('Save') }}</flux:button>
            </flux:input.group>
            @error('team_name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        </form>
        @if(!is_null($team_id))
            </x-teams.layout>
        @endif
    </div>
</div> 