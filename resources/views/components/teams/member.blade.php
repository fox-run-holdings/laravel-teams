<li class="flex items-center justify-between p-4 rounded-lg shadow-sm">
    <div class="flex items-center">
        <flux:avatar :initials="auth()->user()->initials()" class="me-4" />
        <div class="flex flex-col">
            <span class="text-lg font-semibold">{{ $member->name }}</span>
            <span class="text-sm text-muted-foreground">{{ $member->email }}</span>
        </div>
    </div>
    <div class="flex justify-end">
        <flux:badge color="zinc">
            {{ $member->role(auth()->user()->current_team_id) }}
        </flux:badge>
    </div>
    @if($member->role(auth()->user()->current_team_id) != 'owner')
        <div class="flex items ">
            <flux:button class="cursor-pointer" variant="danger" wire:click="removeMember({{ $member->id }})">
                <flux:icon.trash variant="micro" />
            </flux:button>
        </div>
    @endif
</li> 