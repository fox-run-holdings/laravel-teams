<div class="space-y-6">
    <flux:text class="text-lg font-semibold">{{ __('Team Settings') }}</flux:text>
    
    @if (session('error'))
        <flux:text class="text-sm font-medium text-red-600 dark:text-red-400">
            {{ session('error') }}
        </flux:text>
    @endif
    
    @if(isset($noTeamSelected) && $noTeamSelected)
        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
            <flux:text class="text-yellow-800 dark:text-yellow-200">
                {{ __('No team selected. Please select a team from the teams list to manage its settings.') }}
            </flux:text>
        </div>
    @elseif($team)
        <form wire:submit.prevent="saveTeamSettings" class="space-y-6">
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
            <flux:text class="text-sm font-medium text-green-600 dark:text-green-400">
                {{ __('Team settings updated successfully.') }}
            </flux:text>
        @endif
        
        <!-- Debug info -->
        @if(app()->environment('local'))
            <div class="mt-4 p-4 bg-gray-100 dark:bg-gray-800 rounded text-sm">
                <flux:text class="font-medium">Debug Info:</flux:text>
                <flux:text>Team ID: {{ $team?->id ?? 'None' }}</flux:text>
                <flux:text>Team Name: {{ $teamName }}</flux:text>
                <flux:text>Team Slug: {{ $teamSlug }}</flux:text>
            </div>
        @endif
    @endif
</div> 