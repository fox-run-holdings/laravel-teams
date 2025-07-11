<div class="w-full space-y-6">
    <!-- Team Management Header -->
    <div>
        <flux:text class="text-2xl font-bold">{{ __('Team Management') }}</flux:text>
        <flux:text class="text-gray-600 dark:text-gray-400">{{ __('Manage team settings, members, and invitations.') }}</flux:text>
    </div>

    <!-- Team Settings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
            <flux:text class="text-lg font-semibold mb-4">{{ __('Team Settings') }}</flux:text>
            
            <form wire:submit.prevent="updateTeamName" class="space-y-4">
                <flux:input 
                    wire:model="teamName" 
                    :label="__('Team Name')" 
                    type="text" 
                    required 
                />
                
                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit">
                        {{ __('Save Changes') }}
                    </flux:button>
                </div>
            </form>
            
            @if($team->isOwnedBy(auth()->id()) && !$team->personal_team)
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <flux:text class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">{{ __('Danger Zone') }}</flux:text>
                    
                    <flux:button 
                        variant="danger" 
                        wire:click="deleteTeam"
                        wire:confirm="{{ __('Are you sure you want to delete this team? This action cannot be undone.') }}"
                    >
                        {{ __('Delete Team') }}
                    </flux:button>
                </div>
            @endif
        </div>
        
        <div>
            <livewire:teams.manage-team-members :team="$team" />
        </div>
    </div>
    
    <!-- Team Invitations -->
    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
        <livewire:teams.team-invitations :team="$team" />
    </div>

    @if (session('status') === 'team-updated')
        <flux:text class="text-sm font-medium text-green-600 dark:text-green-400">
            {{ __('Team updated successfully.') }}
        </flux:text>
    @endif
</div> 