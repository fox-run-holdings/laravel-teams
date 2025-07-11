<?php
    
    namespace FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams;
    
    use FoxRunHoldings\LaravelTeams\Models\Team;
    use Illuminate\Support\Facades\Auth;
    use Livewire\Component;
    
    class ManageTeamSettings extends Component {
        public $team;
        public $teamName;
        public $showDeleteConfirmation = false;
        
        public function mount($team_id) {
            $this->team = Team::findOrFail($team_id);
            
            // Check if user has access to this team
            if (!$this->team->hasUser(Auth::id())) {
                abort(403);
            }
            
            $this->teamName = $this->team->name;
        }
        
        public function updateTeamName() {
            $this->validate([
                'teamName' => 'required|string|max:255',
            ]);
            
            // Check if user can update team
            if (!$this->team->userHasPermission(Auth::id(), 'write')) {
                abort(403);
            }
            
            $this->team->update(['name' => $this->teamName]);
            
            session()->flash('status', 'team-updated');
        }
        
        public function deleteTeam() {
            // Check if user can delete team
            if (!$this->team->isOwnedBy(Auth::id())) {
                abort(403);
            }
            
            $this->team->delete();
            
            // Redirect to teams page
            return redirect()->route('team');
        }
        
        public function render() {
            return view('laravel-teams::livewire.settings.manage-team-settings', [
                'team' => $this->team,
            ]);
        }
    }