<div class="space-y-6">
    <flux:text class="text-lg font-semibold">{{ __('Team Settings') }}</flux:text>
    
    <form wire:submit="saveTeamSettings" class="space-y-6">
        <flux:input 
            wire:model="teamName" 
            :label="__('Team Name')" 
            type="text" 
            required 
            autofocus 
        />

        <flux:input 
            wire:model="teamSlug" 
            :label="__('Team Slug')" 
            type="text" 
            required 
        />

        <div class="flex items-center gap-4">
            <flux:button variant="primary" type="submit">
                {{ __('Save Changes') }}
            </flux:button>
        </div>
    </form>

    @if (session('status') === 'team-updated')
        <x-action-message class="me-3" on="team-saved">
            {{ __('Team settings updated successfully.') }}
        </x-action-message>
    @endif
</div> 