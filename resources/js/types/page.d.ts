import { Block } from "./block";

export interface Page {
    id: number;
    name: string;
    title: string;
    description: string;
    language: string;
    keywords: string;

    blocks: Block[];
}