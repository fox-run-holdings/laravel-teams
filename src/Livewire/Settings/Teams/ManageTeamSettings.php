<?php
    
    namespace FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams;
    
    use FoxRunHoldings\LaravelTeams\Models\Team;
    use Illuminate\Support\Facades\Auth;
    use Livewire\Component;
    
    class ManageTeamSettings extends Component {
        public ?Team $team = null;
        public $teamName = '';
        public $teamSlug = '';
        
        public function mount(?Team $team = null) {
            $this->team = $team ?? Auth::user()->currentTeam;
            if ($this->team) {
                $this->teamName = $this->team->name;
                $this->teamSlug = $this->team->slug;
            }
        }
        
        public function saveTeamSettings() {
            if (!$this->team || !$this->team->userHasPermission(Auth::id(), 'write')) {
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