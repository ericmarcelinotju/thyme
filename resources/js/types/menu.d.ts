export type MenuType = 'link' | 'title' | 'dropdown'

export interface Menu {
    id: number;
    name: string;
    label: string;
    icon: string;
    type: MenuType;
    route: string;
    sequence: number;
}
