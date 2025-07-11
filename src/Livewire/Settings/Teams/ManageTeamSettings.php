<?php
    
    namespace FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams;
    
    use FoxRunHoldings\LaravelTeams\Models\Team;
    use Illuminate\Support\Facades\Auth;
    use Livewire\Component;
    
    class ManageTeamSettings extends Component {
        public ?Team $team = null;
        public $teamName = '';
        public $teamSlug = '';
        
        public function mount($team_id = null) {
            $this->team = $team_id ? Team::find($team_id) : Auth::user()->currentTeam;
            if ($this->team) {
                $this->teamName = $this->team->name;
                $this->teamSlug = $this->team->slug;
            }
        }
        
        public function saveTeamSettings() {
            if (!$this->team) {
                session()->flash('error', 'No team selected.');
                return;
            }
            
            // Check if user is owner or has write permission
            if (!$this->team->isOwnedBy(Auth::id()) && !$this->team->userHasPermission(Auth::id(), 'write')) {
                session()->flash('error', 'You do not have permission to update this team.');
                return;
            }
            
            $this->validate([
                'teamName' => 'required|string|max:255',
                'teamSlug' => 'required|string|max:255|unique:teams,slug,' . $this->team->id,
            ]);
            
            $this->team->update([
                'name' => $this->teamName,
                'slug' => $this->teamSlug,
            ]);
            
            session()->flash('status', 'team-updated');
            $this->dispatch('team-saved');
        }
        
        public function render() {
            return view('laravel-teams::livewire.settings.manage-team-settings');
        }
    }