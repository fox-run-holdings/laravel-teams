<?php
    
    namespace FoxRunHoldings\LaravelTeams\Middleware;
    
    use Closure;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Symfony\Component\HttpFoundation\Response;
    
    class EnsureUserHasTeam
    {
        /**
         * Handle an incoming request.
         */
        public function handle(Request $request, Closure $next): Response
        {
            if (!Auth::check()) {
                return $next($request);
            }
            
            $user = Auth::user();
            
            // If user has no current team, try to set one
            if (!$user->current_team_id) {
                // Try to get personal team first
                $personalTeam = $user->personalTeam();
                
                if ($personalTeam) {
                    $user->update(['current_team_id' => $personalTeam->id]);
                } else {
                    // Get first team user belongs to
                    $firstTeam = $user->teams()->first();
                    
                    if ($firstTeam) {
                        $user->update(['current_team_id' => $firstTeam->id]);
                    } else {
                        // Create personal team if none exists
                        $personalTeam = \FoxRunHoldings\LaravelTeams\Models\Team::create([
                            'name' => $user->name . "'s Team",
                            'owner_id' => $user->id,
                            'personal_team' => true,
                        ]);
                        
                        $personalTeam->users()->attach($user->id, ['role' => 'owner']);
                        $user->update(['current_team_id' => $personalTeam->id]);
                    }
                }
            }
            
            return $next($request);
        }
    } 