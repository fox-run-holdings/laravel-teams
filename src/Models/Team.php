<?php
    
    namespace FoxRunHoldings\LaravelTeams\Models;
    
    use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
    
    class Team extends Model {
        use SoftDeletes;
        
        protected $fillable = [
            'name',
            'slug',
            'owner_id',
            'personal_team',
        ];
        
        protected $casts = [
            'personal_team' => 'boolean',
        ];
        
        protected static function boot() {
            parent::boot();
            
            static::creating(function ($team) {
                if (empty($team->slug)) {
                    $team->slug = Str::slug($team->name);
                }
            });
        }
        
        public function owner(): BelongsTo {
            return $this->belongsTo(config('auth.providers.users.model'), 'owner_id');
        }
        
        public function users(): BelongsToMany {
            return $this->belongsToMany(config('auth.providers.users.model'), 'team_user')
                ->withPivot('role')
                ->withTimestamps();
        }
        
        public function invitations(): HasMany {
            return $this->hasMany(TeamInvitation::class);
        }
        
        public function hasUser($user): bool {
            if (is_numeric($user)) {
                return $this->users()->where('user_id', $user)->exists();
            }
            
            return $this->users()->where('user_id', $user->id)->exists();
        }
        
        public function userHasPermission($user, $permission): bool {
            if (is_numeric($user)) {
                $user = config('auth.providers.users.model')::find($user);
            }
            
            if (!$user) {
                return false;
            }
            
            // Owner has all permissions
            if ($this->owner_id === $user->id) {
                return true;
            }
            
            $pivot = $this->users()->where('user_id', $user->id)->first()?->pivot;
            
            if (!$pivot) {
                return false;
            }
            
            return $this->hasPermission($pivot->role, $permission);
        }
        
        protected function hasPermission($role, $permission): bool {
            $permissions = [
                'owner' => ['*'],
                'admin' => ['read', 'write', 'delete', 'invite'],
                'member' => ['read', 'write'],
                'viewer' => ['read'],
            ];
            
            $rolePermissions = $permissions[$role] ?? [];
            
            return in_array('*', $rolePermissions) || in_array($permission, $rolePermissions);
        }
        
        public function isOwnedBy($user): bool {
            if (is_numeric($user)) {
                return $this->owner_id === $user;
            }
            
            return $this->owner_id === $user->id;
        }
    }