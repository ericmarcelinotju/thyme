export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Menu {
    id: number;
    name: string;
    label: string;
    icon: string;
    route: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};
