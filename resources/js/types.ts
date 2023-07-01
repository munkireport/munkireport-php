
export interface User {
    name: string;
    email: string;
    source: string;
    locale: string;
}

/**
 * This type models the "Shared Data" that is supplied to every Page from the Inertia Middleware, defined in
 * app/Http/Middleware/HandleInertiaRequests.php
 */
export type SharedData = {
    appName: string;
    dashboards: object[];
    reports: object[];
    listings: object[];
    admin: object[];
    user?: User;
    csrf_token?: string;
}
