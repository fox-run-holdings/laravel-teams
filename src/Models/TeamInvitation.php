<?php
    
    namespace FoxRunHoldings\LaravelTeams\Models;
    
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    
    class TeamInvitation extends Model {
        protected $fillable = [
            'team_id',
            'email',
            'role',
        ];
        
        public function team(): BelongsTo {
            return $this->belongsTo(Team::class);
        }
        
        public function hasPermission($permission): bool {
            $permissions = [
                'owner' => ['*'],
                'admin' => ['read', 'write', 'delete', 'invite'],
                'member' => ['read', 'write'],
                'viewer' => ['read'],
            ];
            
            $rolePermissions = $permissions[$this->role] ?? [];
            
            return in_array('*', $rolePermissions) || in_array($permission, $rolePermissions);
        }
    } 