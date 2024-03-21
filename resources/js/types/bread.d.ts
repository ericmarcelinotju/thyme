import { Block } from "./block";

export interface Column {
  name: string;
  field_type: string;
  relation_id: string;
  relation_column: string;

  is_allow_browse: boolean;
  is_allow_read: boolean;
  is_allow_edit: boolean;
  is_allow_add: boolean;
}

export interface Bread {
    id: number;
    name: string;
    table_name: string;
    columns: Column[];

    is_allow_browse: boolean;
    is_allow_read: boolean;
    is_allow_edit: boolean;
    is_allow_add: boolean;
    is_allow_delete: boolean;
}

export interface Resource {
  id: number;
}