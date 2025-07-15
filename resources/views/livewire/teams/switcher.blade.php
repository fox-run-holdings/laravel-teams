<div>
    <!-- Desktop Team Menu -->
    <flux:dropdown position="bottom" align="start">
        <flux:button icon:trailing="chevrons-up-down" class="w-full" style="justify-content: space-between !important;">{{ !is_null(auth()->user()->current_team_id) && auth()->user()->HasTeams() ? auth()->user()->CurrentTeam->name : 'No Team' }}</flux:button>
        <flux:menu class="w-[220px]">
            <flux:menu.radio.group>
                @if(auth()->user()->HasTeams())
                    @foreach(auth()->user()->teams as $team)
                        @if(auth()->user()->current_team_id === $team->id)
                            <flux:menu.item class="mb-1 bg-zinc-600 flex" wire:navigate>
                                {{ $team->name }}
                                <flux:icon.cog-6-tooth wire:click="openTeamManagement" class="ml-auto z-50 hover:text-gray-300 cursor-pointer" />
                            </flux:menu.item>
                        @else
                            <flux:menu.item class="mb-1 cursor-pointer" wire:navigate wire:click="switchTeam({{ $team->id }})">
                                {{ $team->name }}
                            </flux:menu.item>
                        @endif
                    @endforeach
                    <flux:menu.separator />
                @endif
                <flux:menu.item :href="route('teams')" wire:navigate>
                    <flux:icon.plus-circle variant="micro" class="mr-2" />
                    {{ __('New Team') }}
                </flux:menu.item>
                <flux:menu.separator />
                <flux:menu.item :href="route('teams.invitations')" wire:navigate>
                    <flux:icon.user-circle variant="micro" class="mr-2" />
                    {{ __('Invitations') }}
                </flux:menu.item>
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
</div> 