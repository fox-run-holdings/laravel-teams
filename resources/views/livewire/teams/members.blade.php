<div>
    <div class="flex flex-col gap-4">
        <div class="relative mb-6 w-full">
            <flux:heading size="xl" level="1">{{ $team->name .  __(' Settings') }}</flux:heading>
            <flux:subheading size="lg" class="mb-6">{{ __('Manage your team settings') }}</flux:subheading>
            <flux:separator variant="subtle" />
        </div>
        <x-banner.error />
        <x-banner.success />
        <x-teams.layout>
            <div>
                <flux:heading class="mb-3 text-xl">
                    Invite new member
                </flux:heading>
                <form wire:submit.prevent="inviteMember" class="flex items-center gap-2">
                    <flux:input.group>
                        <flux:input placeholder="Email" wire:model.live="new_member_email" />
                        <flux:dropdown>
                            <flux:button icon:trailing="chevron-down">Role: {{ $new_member_role }}</flux:button>
                            <flux:menu>
                                <flux:menu.radio.group wire:model.live="new_member_role">
                                    <flux:menu.radio checked>owner</flux:menu.radio>
                                    <flux:menu.radio>admin</flux:menu.radio>
                                    <flux:menu.radio>member</flux:menu.radio>
                                </flux:menu.radio.group>
                            </flux:menu>
                        </flux:dropdown>
                        <flux:button type="submit" variant="primary" class="cursor-pointer">{{ __('Invite') }}</flux:button>
                    </flux:input.group>
                </form>
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            @if(!$this->invitations->isEmpty())
                <hr class="my-3" />
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
                                    @if(in_array(auth()->user()->role($team_id), ['owner', 'admin']))
                                        <flux:dropdown>
                                            <flux:button class="cursor-pointer">
                                                <flux:icon.ellipsis-horizontal />
                                            </flux:button>
                                            <flux:menu>
                                                <flux:menu.item class="cursor-pointer" variant="danger" icon="trash" wire:click="cancelInvitation({{$invitation->id}})">Delete</flux:menu.item>
                                            </flux:menu>
                                        </flux:dropdown>
                                    @endif
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            @endif
            <hr class="my-3" />
            <flux:heading class="mb-3 text-xl">
                Current Team Members
            </flux:heading>
            <flux:table :paginate="$this->members">
                <flux:table.columns>
                    <flux:table.column>Name</flux:table.column>
                    <flux:table.column>Email</flux:table.column>
                    <flux:table.column>Role</flux:table.column>
                    <flux:table.column></flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($this->members as $member)
                        <flux:table.row :key="'team-member-' . $member->id">
                            <flux:table.cell>
                                {{ $member->name }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $member->email }}
                            </flux:table.cell>
                            <flux:table.cell>
                                @if((auth()->user()->role($team_id) != 'owner' && $member->role($team_id) == 'owner') || !in_array(auth()->user()->role($team_id), ['owner', 'admin']))
                                    <flux:badge :color="$member->role($team_id) == 'owner' ? 'lime' : ($member->role($team_id) == 'admin' ? 'blue' : 'zinc')">
                                        {{ $member->role($team->id) }}
                                    </flux:badge>
                                @else
                                    <flux:dropdown>
                                        <flux:button class="cursor-pointer" size="sm" variant="primary" :color="$member->role($team_id) == 'owner' ? 'lime' : ($member->role($team_id) == 'admin' ? 'blue' : 'zinc')" icon:trailing="chevron-down">{{ $member->role($team->id) }}</flux:button>
                                        <flux:menu>
                                            <flux:menu.radio.group wire:model="member_roles.{{ $member->id }}">
                                                @if(auth()->user()->role($team_id) == 'owner')
                                                    <flux:menu.radio value="owner" wire:click="changeRole({{ $member->id }}, 'owner')">owner</flux:menu.radio>
                                                @endif
                                                <flux:menu.radio value="admin" wire:click="changeRole({{ $member->id }}, 'admin')">admin</flux:menu.radio>
                                                <flux:menu.radio value="member" wire:click="changeRole({{ $member->id }}, 'member')">member</flux:menu.radio>
                                            </flux:menu.radio.group>
                                        </flux:menu>
                                    </flux:dropdown>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                @if($member->role($team_id) != 'owner' && in_array(auth()->user()->role($team_id), ['owner', 'admin']))
                                    <flux:dropdown>
                                        <flux:button class="cursor-pointer" size="sm">
                                            <flux:icon.ellipsis-horizontal />
                                        </flux:button>
                                        <flux:menu>
                                            @if($member->role($team_id) != 'owner' && in_array(auth()->user()->role($team_id), ['owner', 'admin']))
                                                <flux:menu.separator />
                                                <flux:menu.item icon="trash" class="cursor-pointer" variant="danger" wire:click="cancelInvitation({{$member->id}})">Remove</flux:menu.item>
                                            @endif
                                        </flux:menu>
                                    </flux:dropdown>
                                @endif
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </x-teams.layout>
    </div>
</div> 