<?php
    
    namespace FoxRunHoldings\LaravelTeams\Livewire\Settings\Teams;
    
    use App\Models\User;
    use FoxRunHoldings\LaravelTeams\Models\Team;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Session;
    use Illuminate\Validation\Rule;
    use Livewire\Component;
    
    class Teams extends Component {
        public $showCreateForm = false;
        public $teamName = '';
        public $currentTeam;
        
        public function mount() {
            $this->currentTeam = Auth::user()->currentTeam;
        }
        
        public function createTeam() {
            $this->validate([
                'teamName' => 'required|string|max:255',
            ]);
            
            $team = Team::create([
                'name' => $this->teamName,
                'owner_id' => Auth::id(),
                'personal_team' => false,
            ]);
            
            // Add the creator as owner
            $team->users()->attach(Auth::id(), ['role' => 'owner']);
            
            // Set as current team
            Auth::user()->update(['current_team_id' => $team->id]);
            $this->currentTeam = $team;
            
            $this->teamName = '';
            $this->showCreateForm = false;
            
            session()->flash('status', 'team-created');
        }
        
        public function switchTeam($teamId) {
            $team = Team::findOrFail($teamId);
            
            if (!$team->hasUser(Auth::id())) {
                abort(403);
            }
            
            Auth::user()->update(['current_team_id' => $team->id]);
            $this->currentTeam = $team;
            
            session()->flash('status', 'team-switched');
        }
        
        public function deleteTeam($teamId) {
            $team = Team::findOrFail($teamId);
            
            if (!$team->isOwnedBy(Auth::id())) {
                abort(403);
            }
            
            $team->delete();
            
            // If this was the current team, switch to personal team
            if ($this->currentTeam && $this->currentTeam->id === $team->id) {
                $personalTeam = Auth::user()->teams()->where('personal_team', true)->first();
                if ($personalTeam) {
                    Auth::user()->update(['current_team_id' => $personalTeam->id]);
                    $this->currentTeam = $personalTeam;
                }
            }
            
            session()->flash('status', 'team-deleted');
        }
        
        public function render() {
            $user = Auth::user();
            $teams = $user->teams()->with('owner')->get();
            
            return view('laravel-teams::livewire.settings.teams', [
                'teams' => $teams,
                'currentTeam' => $this->currentTeam,
            ]);
        }
    }