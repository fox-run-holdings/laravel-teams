<div class="relative" x-data="{ open: false }">
    <flux:button 
        variant="secondary" 
        size="sm"
        @click="open = !open"
        class="flex items-center gap-2"
    >
        @if($currentTeam)
            {{ $currentTeam->name }}
        @else
            {{ __('No team') }}
        @endif
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </flux:button>
    
    <div 
        x-show="open" 
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50"
    >
        <div class="py-1">
            @if($teams->count() > 0)
                @foreach($teams as $team)
                    <div class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                        <div class="flex items-center justify-between">
                            <span>{{ $team->name }}</span>
                            @if($currentTeam && $currentTeam->id === $team->id)
                                <span class="text-green-600 dark:text-green-400">✓</span>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-1 mt-1">
                            @if($currentTeam && $currentTeam->id === $team->id)
                                <a 
                                    href="{{ route('team.manage', $team->id) }}" 
                                    class="text-xs text-blue-600 dark:text-blue-400 hover:underline"
                                >
                                    {{ __('Manage Team') }}
                                </a>
                            @else
                                <a 
                                    href="#" 
                                    wire:click.prevent="switchTeam({{ $team->id }})"
                                    class="text-xs text-blue-600 dark:text-blue-400 hover:underline"
                                >
                                    {{ __('Switch to') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                <div class="border-t border-gray-200 dark:border-gray-700"></div>
            @endif
            
            <a 
                href="#" 
                wire:click.prevent="goToNoTeam"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
            >
                {{ __('Create New Team') }}
            </a>
        </div>
    </div>
</div> 