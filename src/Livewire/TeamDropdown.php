<?php
    
    namespace FoxRunHoldings\LaravelTeams\Livewire;
    
    use FoxRunHoldings\LaravelTeams\Models\Team;
    use Illuminate\Support\Facades\Auth;
    use Livewire\Component;
    
    class TeamDropdown extends Component {
        public $teams = [];
        public $currentTeam = null;
        
        public function mount() {
            $this->loadTeams();
        }
        
        public function loadTeams() {
            $this->teams = Auth::user()->teams()->with('owner')->get();
            $this->currentTeam = Auth::user()->currentTeam;
        }
        
        public function switchTeam($teamId) {
            $team = Team::findOrFail($teamId);
            
            if (!$team->hasUser(Auth::id())) {
                abort(403);
            }
            
            Auth::user()->update(['current_team_id' => $team->id]);
            $this->currentTeam = $team;
            
            // Stay on current page, just update the team
            $this->dispatch('team-switched', teamId: $team->id);
        }
        
        public function goToNoTeam() {
            return redirect()->route('team');
        }
        
        public function render() {
            return view('laravel-teams::livewire.team-dropdown');
        }
    } 