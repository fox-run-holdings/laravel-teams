<div>
    <div class="flex flex-col gap-4">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ __('Team Invitations') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Accept or decline team invitations.') }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>
        <x-banner.error />
        <x-banner.success />
        <div>
            @if($this->invitations->isEmpty())
                <flux:text variant="subtle" class="italic mb-6">
                    {{ __('You have no pending team invitations.') }}
                </flux:text>
            @else
                <hr class="mb-3" />
                <flux:heading class="mb-3 text-xl">
                    Pending Invitations
                </flux:heading>
                <flux:table :paginate="$this->invitations">
                    <flux:table.columns>
                        <flux:table.column>Email</flux:table.column>
                        <flux:table.column>Role</flux:table.column>
                        <flux:table.column></flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach($this->invitations as $invitation)
                            <flux:table.row :key="'team-invitation-' . $invitation->id">
                                <flux:table.cell>
                                    {{ $invitation->email }}
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:badge :color="$invitation->role == 'owner' ? 'lime' : ($invitation->role == 'admin' ? 'blue' : 'zinc')">
                                        {{ $invitation->role }}
                                    </flux:badge>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <flux:dropdown>
                                        <flux:button class="cursor-pointer" size="sm">
                                            <flux:icon.ellipsis-horizontal />
                                        </flux:button>
                                        <flux:menu>
                                            <flux:menu.item class="cursor-pointer" wire:click="acceptInvitation({{$invitation->id}})">Accept</flux:menu.item>
                                            <flux:menu.item class="cursor-pointer" variant="danger" wire:click="cancelInvitation({{$invitation->id}})">Decline</flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            @endif
            @if(!$this->archivedInvitations->isEmpty())
                <hr class="my-3" />
                <flux:heading class="mb-3 text-xl">
                    Archived Invitations
                </flux:heading>
                    <flux:table :paginate="$this->archivedInvitations">
                        <flux:table.columns>
                            <flux:table.column>Email</flux:table.column>
                            <flux:table.column>Role</flux:table.column>
                            <flux:table.column>Status</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach($this->archivedInvitations as $archived_invitation)
                                <flux:table.row :key="'accepted-team-invitation-' . $archived_invitation->id">
                                    <flux:table.cell>
                                        {{ $archived_invitation->email }}
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge :color="$archived_invitation->role == 'owner' ? 'lime' : ($archived_invitation->role == 'admin' ? 'blue' : 'zinc')">
                                            {{ $archived_invitation->role }}
                                        </flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge :color="$archived_invitation->status == 'accepted' ? 'lime' : ($archived_invitation->status == 'pending' ? 'yellow' : 'red')">
                                            {{ $archived_invitation->status }}
                                        </flux:badge>
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
            @endif
        </div>
    </div>
</div> 