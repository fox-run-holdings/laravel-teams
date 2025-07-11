<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Team Settings and Member Management -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Team Settings -->
                    <div>
                        <livewire:teams.manage-team-settings :team="$team" />
                    </div>
                    
                    <!-- Team Members -->
                    <div>
                        <livewire:teams.manage-team-members :team="$team" />
                    </div>
                </div>
                
                <!-- Team Invitations -->
                <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <livewire:teams.team-invitations :team="$team" />
                </div>
            </div>
        </div>
    </div>
</div>