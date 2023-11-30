import { Role } from "./role";

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    role_id: number;
    role: Role
}