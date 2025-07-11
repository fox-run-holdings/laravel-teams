<?php
    
    namespace FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams;
    
    use FoxRunHoldings\LaravelTeams\Models\Team;
    use Illuminate\Support\Facades\Auth;
    use Livewire\Component;
    
    class ManageTeamSettings extends Component {
        public Team $team;
        public $teamName;
        public $teamSlug;
        
        public function mount(Team $team) {
            $this->team = $team;
            $this->teamName = $team->name;
            $this->teamSlug = $team->slug;
        }
        
        public function saveTeamSettings() {
            if (!$this->team->userHasPermission(Auth::id(), 'write')) {
                abort(403);
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