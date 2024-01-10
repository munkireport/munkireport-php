export {};
declare global {
    export namespace inertia {

        /**
         * This type models the "Shared Data" that is supplied to every Page from the Inertia Middleware, defined in
         * app/Http/Middleware/HandleInertiaRequests.php
         */
        export interface SharedData {
            appName: string;
            csrf_token?: string;
            current_theme: string;
            user: {
                id: number;
                name: string;
                email: string;
                created_at: Date;
                updated_at: Date;
            };
            jetstream: {
                [key: string]: boolean;
            };
            errorBags: unknown;
            errors: unknown;
            themes: string[];

            dashboards: object[];
            reports: object[];
            listings: object[];
            admin: object[];
        }
    }
}
