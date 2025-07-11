<div class="w-full space-y-6">
    <!-- Team Creation and Listing View -->
    <div>
        <flux:text class="text-2xl font-bold">{{ __('Teams') }}</flux:text>
        <flux:text class="text-gray-600 dark:text-gray-400">{{ __('Manage your teams and switch between them.') }}</flux:text>
    </div>

    <!-- Current Team Display -->
    @if($currentTeam)
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <flux:text class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Current Team') }}</flux:text>
            <flux:text class="text-lg font-semibold">{{ $currentTeam->name }}</flux:text>
            <flux:text class="text-sm text-gray-500">{{ __('Owned by') }} {{ $currentTeam->owner->name }}</flux:text>
        </div>
    @endif

    <!-- Teams List -->
    <div class="space-y-4">
        <flux:text class="text-lg font-semibold">{{ __('Your Teams') }}</flux:text>
        
        @foreach($teams as $team)
            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="flex-1">
                    <flux:text class="font-medium">{{ $team->name }}</flux:text>
                    <flux:text class="text-sm text-gray-500">{{ __('Owned by') }} {{ $team->owner->name }}</flux:text>
                    @if($team->personal_team)
                        <flux:text class="text-xs text-blue-600 dark:text-blue-400">{{ __('Personal Team') }}</flux:text>
                    @endif
                </div>
                
                <div class="flex items-center gap-2">
                    @if($currentTeam && $currentTeam->id === $team->id)
                        <flux:text class="text-sm text-green-600 dark:text-green-400">{{ __('Current') }}</flux:text>
                    @else
                        <flux:button 
                            variant="secondary" 
                            size="sm" 
                            wire:click="switchTeam({{ $team->id }})"
                        >
                            {{ __('Switch') }}
                        </flux:button>
                    @endif
                    
                    @if($team->isOwnedBy(auth()->id()) && !$team->personal_team)
                        <flux:button 
                            variant="danger" 
                            size="sm" 
                            wire:click="deleteTeam({{ $team->id }})"
                            wire:confirm="{{ __('Are you sure you want to delete this team?') }}"
                        >
                            {{ __('Delete') }}
                        </flux:button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Create New Team -->
    @if(!$showCreateForm)
        <div class="mt-6">
            <flux:button 
                variant="secondary" 
                wire:click="$set('showCreateForm', true)"
            >
                {{ __('Create New Team') }}
            </flux:button>
        </div>
    @else
        <div class="mt-6 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <flux:text class="text-lg font-semibold mb-4">{{ __('Create New Team') }}</flux:text>
            
            <form wire:submit.prevent="createTeam" class="space-y-4">
                <flux:input 
                    wire:model="teamName" 
                    :label="__('Team Name')" 
                    type="text" 
                    required 
                    autofocus 
                />
                
                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit">
                        {{ __('Create Team') }}
                    </flux:button>
                    <flux:button 
                        variant="secondary" 
                        type="button" 
                        wire:click="$set('showCreateForm', false)"
                    >
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            </form>
        </div>
    @endif

    @if (session('status') === 'team-created')
        <flux:text class="text-sm font-medium text-green-600 dark:text-green-400">
            {{ __('Team created successfully.') }}
        </flux:text>
    @endif

    @if (session('status') === 'team-switched')
        <flux:text class="text-sm font-medium text-green-600 dark:text-green-400">
            {{ __('Team switched successfully.') }}
        </flux:text>
    @endif

    @if (session('status') === 'team-deleted')
        <flux:text class="text-sm font-medium text-green-600 dark:text-green-400">
            {{ __('Team deleted successfully.') }}
        </flux:text>
    @endif
</div>