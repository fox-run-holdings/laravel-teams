<?php
    
    namespace FoxRunHoldings\LaravelTeams\Policies;
    
    use App\Models\User;
    use FoxRunHoldings\LaravelTeams\Models\Team;
    use Illuminate\Auth\Access\HandlesAuthorization;
    
    class TeamPolicy
    {
        use HandlesAuthorization;
        
        /**
         * Determine whether the user can view any teams.
         */
        public function viewAny(User $user): bool
        {
            return true;
        }
        
        /**
         * Determine whether the user can view the team.
         */
        public function view(User $user, Team $team): bool
        {
            return $team->hasUser($user->id);
        }
        
        /**
         * Determine whether the user can create teams.
         */
        public function create(User $user): bool
        {
            return true;
        }
        
        /**
         * Determine whether the user can update the team.
         */
        public function update(User $user, Team $team): bool
        {
            return $team->userHasPermission($user->id, 'write');
        }
        
        /**
         * Determine whether the user can delete the team.
         */
        public function delete(User $user, Team $team): bool
        {
            return $team->isOwnedBy($user->id);
        }
        
        /**
         * Determine whether the user can restore the team.
         */
        public function restore(User $user, Team $team): bool
        {
            return $team->isOwnedBy($user->id);
        }
        
        /**
         * Determine whether the user can permanently delete the team.
         */
        public function forceDelete(User $user, Team $team): bool
        {
            return $team->isOwnedBy($user->id);
        }
        
        /**
         * Determine whether the user can add team members.
         */
        public function addTeamMember(User $user, Team $team): bool
        {
            return $team->userHasPermission($user->id, 'invite');
        }
        
        /**
         * Determine whether the user can update team member permissions.
         */
        public function updateTeamMember(User $user, Team $team): bool
        {
            return $team->userHasPermission($user->id, 'write');
        }
        
        /**
         * Determine whether the user can remove team members.
         */
        public function removeTeamMember(User $user, Team $team): bool
        {
            return $team->userHasPermission($user->id, 'delete');
        }
    } 