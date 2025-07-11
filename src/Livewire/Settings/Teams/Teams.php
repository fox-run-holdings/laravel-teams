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
        public $teams = [];
        
        public function mount($team_id = null) {
            $this->currentTeam = Auth::user()->currentTeam;
            $this->teams = Auth::user()->teams()->with('owner')->get();
            
            // If a specific team is requested, redirect to team management
            if ($team_id) {
                $team = Team::find($team_id);
                if ($team && $team->hasUser(Auth::id())) {
                    // Redirect to team management page
                    return redirect()->route('team.manage', $team_id);
                }
            }
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
            
            // Refresh teams list
            $this->teams = Auth::user()->teams()->with('owner')->get();
            
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
            
            // Refresh teams list
            $this->teams = Auth::user()->teams()->with('owner')->get();
            
            session()->flash('status', 'team-deleted');
        }
        
        public function render() {
            return view('laravel-teams::livewire.settings.teams', [
                'teams' => $this->teams,
                'currentTeam' => $this->currentTeam,
            ]);
        }
    }