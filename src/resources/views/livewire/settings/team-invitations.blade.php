<div class="space-y-6">
    <flux:text class="text-lg font-semibold">{{ __('Team Invitations') }}</flux:text>
    
    <!-- Invite New Member -->
    <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
        <flux:text class="font-medium mb-4">{{ __('Invite New Member') }}</flux:text>
        
        <form wire:submit="inviteMember" class="space-y-4">
            <flux:input 
                wire:model="email" 
                :label="__('Email Address')" 
                type="email" 
                required 
            />
            
            <flux:select wire:model="role" :label="__('Role')">
                <option value="admin">{{ __('Admin') }}</option>
                <option value="member">{{ __('Member') }}</option>
                <option value="viewer">{{ __('Viewer') }}</option>
            </flux:select>
            
            <div class="flex items-center gap-4">
                <flux:button variant="primary" type="submit">
                    {{ __('Send Invitation') }}
                </flux:button>
            </div>
        </form>
    </div>

    <!-- Pending Invitations -->
    @if($invitations->count() > 0)
        <div class="space-y-4">
            <flux:text class="font-medium">{{ __('Pending Invitations') }}</flux:text>
            
            @foreach($invitations as $invitation)
                <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div class="flex-1">
                        <flux:text class="font-medium">{{ $invitation->email }}</flux:text>
                        <flux:text class="text-sm text-gray-500">{{ ucfirst($invitation->role) }}</flux:text>
                        <flux:text class="text-xs text-gray-400">{{ __('Invited') }} {{ $invitation->created_at->diffForHumans() }}</flux:text>
                    </div>
                    
                    <flux:button 
                        variant="danger" 
                        size="sm" 
                        wire:click="cancelInvitation({{ $invitation->id }})"
                        wire:confirm="{{ __('Are you sure you want to cancel this invitation?') }}"
                    >
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            @endforeach
        </div>
    @endif

    @if (session('status') === 'invitation-sent')
        <flux:text class="text-sm font-medium text-green-600 dark:text-green-400">
            {{ __('Invitation sent successfully.') }}
        </flux:text>
    @endif

    @if (session('status') === 'invitation-cancelled')
        <flux:text class="text-sm font-medium text-green-600 dark:text-green-400">
            {{ __('Invitation cancelled successfully.') }}
        </flux:text>
    @endif

    @if (session('error'))
        <flux:text class="text-sm font-medium text-red-600 dark:text-red-400">
            {{ session('error') }}
        </flux:text>
    @endif
</div> 