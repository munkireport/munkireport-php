<?php


namespace MR\Kiss\Contracts;

/**
 * This interface declares all the properties of the munkireport\lib\User object so that
 * they can be added in a backwards compatible way to the new App\User object.
 *
 * @package MR\Kiss\Contracts
 */
interface LegacyUser
{
    // Check if the user has the admin role
    public function isAdmin(): bool;

    // Check if the user has the manager role
    public function isManager(): bool;

    // Check if the user has the archiver role
    public function isArchiver(): bool;

    // Check if the user is able to archive, originally this meant you had to be
    // an admin, manager OR archiver
    public function canArchive(): bool;

    // Check if the current user can access a specific machine group.
    // In Eloquent this can be done via Auth Policies, but this should provide a backwards
    // compatible implementation.
    public function canAccessMachineGroup($id): bool;

    // List machine groups that the user can access.
    public function machineGroups(): array;

}
