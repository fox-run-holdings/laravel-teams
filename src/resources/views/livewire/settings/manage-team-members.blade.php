<div class="space-y-6">
    <flux:text class="text-lg font-semibold">{{ __('Team Members') }}</flux:text>
    
    <div class="space-y-4">
        @foreach ($members as $member)
            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                <div class="flex-1">
                    <flux:text class="font-medium">{{ $member->name }}</flux:text>
                    <flux:text class="text-sm text-gray-500">{{ $member->email }}</flux:text>
                    @if($member->id === $team->owner_id)
                        <flux:text class="text-xs text-blue-600 dark:text-blue-400">{{ __('Owner') }}</flux:text>
                    @else
                        <flux:text class="text-xs text-gray-500">{{ ucfirst($member->pivot->role) }}</flux:text>
                    @endif
                </div>
                
                <div class="flex items-center gap-2">
                    @if($member->id !== $team->owner_id)
                        @if($editingMember === $member->id)
                            <div class="flex items-center gap-2">
                                <flux:select wire:model="editingRole" class="text-sm">
                                    <option value="admin">{{ __('Admin') }}</option>
                                    <option value="member">{{ __('Member') }}</option>
                                    <option value="viewer">{{ __('Viewer') }}</option>
                                </flux:select>
                                <flux:button 
                                    variant="primary" 
                                    size="sm" 
                                    wire:click="updateMemberRole"
                                >
                                    {{ __('Save') }}
                                </flux:button>
                                <flux:button 
                                    variant="secondary" 
                                    size="sm" 
                                    wire:click="cancelEdit"
                                >
                                    {{ __('Cancel') }}
                                </flux:button>
                            </div>
                        @else
                            <flux:button 
                                variant="secondary" 
                                size="sm" 
                                wire:click="editMemberRole({{ $member->id }})"
                            >
                                {{ __('Edit Role') }}
                            </flux:button>
                            <flux:button 
                                variant="danger" 
                                size="sm" 
                                wire:click="removeMember({{ $member->id }})"
                                wire:confirm="{{ __('Are you sure you want to remove this member?') }}"
                            >
                                {{ __('Remove') }}
                            </flux:button>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if (session('status') === 'member-removed')
        <x-action-message class="me-3" on="member-removed">
            {{ __('Member removed successfully.') }}
        </x-action-message>
    @endif

    @if (session('status') === 'role-updated')
        <x-action-message class="me-3" on="role-updated">
            {{ __('Role updated successfully.') }}
        </x-action-message>
    @endif

    @if (session('error'))
        <flux:text class="text-sm font-medium text-red-600 dark:text-red-400">
            {{ session('error') }}
        </flux:text>
    @endif
</div>