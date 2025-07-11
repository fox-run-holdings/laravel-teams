<?php
    
    namespace FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams;
    
    use FoxRunHoldings\LaravelTeams\Models\Team;
    use FoxRunHoldings\LaravelTeams\Models\TeamInvitation;
    use Illuminate\Support\Facades\Auth;
    use Livewire\Component;
    
    class TeamInvitations extends Component {
        public $team;
        public $email = '';
        public $role = 'member';
        
        public function mount($team_id) {
            $this->team = Team::findOrFail($team_id);
            
            // Check if user has access to this team
            if (!$this->team->hasUser(Auth::id())) {
                abort(403);
            }
        }
        
        public function inviteMember() {
            if (!$this->team) {
                session()->flash('error', 'No team selected.');
                return;
            }
            
            $this->validate([
                'email' => 'required|email',
                'role' => 'required|in:admin,member,viewer',
            ]);
            
            // Check if user is owner or has invite permission
            if (!$this->team->isOwnedBy(Auth::id()) && !$this->team->userHasPermission(Auth::id(), 'invite')) {
                session()->flash('error', 'You do not have permission to send invitations for this team.');
                return;
            }
            
            // Check if user is already a member
            $user = config('auth.providers.users.model')::where('email', $this->email)->first();
            if ($user && $this->team->hasUser($user->id)) {
                session()->flash('error', 'User is already a member of this team.');
                return;
            }
            
            // Check if invitation already exists
            if ($this->team->invitations()->where('email', $this->email)->exists()) {
                session()->flash('error', 'An invitation has already been sent to this email address.');
                return;
            }
            
            $this->team->invitations()->create([
                'email' => $this->email,
                'role' => $this->role,
            ]);
            
            $this->email = '';
            $this->role = 'member';
            
            session()->flash('status', 'invitation-sent');
        }
        
        public function cancelInvitation($invitationId) {
            if (!$this->team) {
                session()->flash('error', 'No team selected.');
                return;
            }
            
            $invitation = TeamInvitation::findOrFail($invitationId);
            
            // Check if user is owner or has invite permission
            if (!$this->team->isOwnedBy(Auth::id()) && !$this->team->userHasPermission(Auth::id(), 'invite')) {
                session()->flash('error', 'You do not have permission to cancel invitations for this team.');
                return;
            }
            
            $invitation->delete();
            
            session()->flash('status', 'invitation-cancelled');
        }
        
        public function render() {
            $invitations = $this->team ? $this->team->invitations()->get() : collect();
            
            return view('laravel-teams::livewire.settings.team-invitations', [
                'team' => $this->team,
                'invitations' => $invitations,
            ]);
        }
    } 