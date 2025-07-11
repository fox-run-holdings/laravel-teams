<div>
    <!-- Desktop Team Menu -->
    <flux:dropdown position="bottom" align="start">
        <flux:button icon:trailing="chevrons-up-down" class="w-full" style="justify-content: space-between !important;">{{ !is_null(auth()->user()->current_team_id) && auth()->user()->hasTeams() ? auth()->user()->currentTeam->name : 'No Team' }}</flux:button>
        <flux:menu class="w-[220px]">
            <flux:menu.radio.group>
                @if(auth()->user()->hasTeams())
                    @foreach(auth()->user()->teams as $team)
                        <flux:menu.item :class="auth()->user()->current_team_id === $team->id ? 'mb-1 bg-zinc-600' : 'mb-1 cursor-pointer'" wire:navigate wire:click="switchTeam({{ $team->id }})">{{ $team->name }}</flux:menu.item>
                    @endforeach
                    <flux:menu.separator />
                @endif
                @if(!is_null(auth()->user()->currentTeam))
                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.team', ['team_id' => auth()->user()->currentTeam->id])" icon="cog" wire:navigate>{{ __('Team Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>
                    <flux:menu.separator />
                @endif
                <flux:menu.item :href="route('settings.team')" wire:navigate>{{ __('New Team') }}</flux:menu.item>
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
</div> 