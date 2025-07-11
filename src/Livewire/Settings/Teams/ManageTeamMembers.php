<?php
    
    namespace FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams;
    
    use FoxRunHoldings\LaravelTeams\Models\Team;
    use Illuminate\Support\Facades\Auth;
    use Livewire\Component;
    
    class ManageTeamMembers extends Component {
        public ?Team $team = null;
        public $members = [];
        public $editingMember = null;
        public $editingRole = '';
        
        public function mount($team_id = null) {
            $this->team = $team_id ? Team::find($team_id) : Auth::user()->currentTeam;
            if ($this->team) {
                $this->refreshMembers();
            }
        }
        
        public function refreshMembers() {
            if ($this->team) {
                $this->members = $this->team->users()->with('pivot')->get();
            }
        }
        
        public function removeMember($userId) {
            if (!$this->team || !$this->team->userHasPermission(Auth::id(), 'delete')) {
                abort(403);
            }
            
            // Don't allow removing the owner
            if ($this->team->owner_id === $userId) {
                session()->flash('error', 'Cannot remove the team owner.');
                return;
            }
            
            $this->team->users()->detach($userId);
            $this->refreshMembers();
            
            session()->flash('status', 'member-removed');
        }
        
        public function editMemberRole($userId) {
            $member = $this->members->firstWhere('id', $userId);
            if ($member) {
                $this->editingMember = $userId;
                $this->editingRole = $member->pivot->role;
            }
        }
        
        public function updateMemberRole() {
            if (!$this->team || !$this->team->userHasPermission(Auth::id(), 'write')) {
                abort(403);
            }
            
            $this->validate([
                'editingRole' => 'required|in:admin,member,viewer',
            ]);
            
            // Don't allow changing owner role
            if ($this->team->owner_id === $this->editingMember) {
                session()->flash('error', 'Cannot change the team owner\'s role.');
                return;
            }
            
            $this->team->users()->updateExistingPivot($this->editingMember, [
                'role' => $this->editingRole,
            ]);
            
            $this->refreshMembers();
            $this->editingMember = null;
            $this->editingRole = '';
            
            session()->flash('status', 'role-updated');
        }
        
        public function cancelEdit() {
            $this->editingMember = null;
            $this->editingRole = '';
        }
        
        public function render() {
            return view('laravel-teams::livewire.settings.manage-team-members', [
                'members' => $this->members,
            ]);
        }
    }
