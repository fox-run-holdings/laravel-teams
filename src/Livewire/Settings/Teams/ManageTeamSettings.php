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
            // Add debugging
            \Log::info('SaveTeamSettings called', [
                'team' => $this->team?->id,
                'teamName' => $this->teamName,
                'teamSlug' => $this->teamSlug,
                'user' => Auth::id()
            ]);
            
            if (!$this->team) {
                session()->flash('error', 'No team selected. Please select a team first.');
                return;
            }
            
            // Check if user is owner or has write permission
            if (!$this->team->isOwnedBy(Auth::id()) && !$this->team->userHasPermission(Auth::id(), 'write')) {
                session()->flash('error', 'You do not have permission to update this team.');
                return;
            }
            
            try {
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
                
                \Log::info('Team updated successfully', ['team_id' => $this->team->id]);
                
            } catch (\Exception $e) {
                \Log::error('Error updating team', [
                    'team_id' => $this->team->id,
                    'error' => $e->getMessage()
                ]);
                session()->flash('error', 'Error updating team: ' . $e->getMessage());
            }
        }
        
        public function render() {
            // If no team is selected, show a message
            if (!$this->team) {
                return view('laravel-teams::livewire.settings.manage-team-settings', [
                    'noTeamSelected' => true
                ]);
            }
            
            return view('laravel-teams::livewire.settings.manage-team-settings');
        }
    }