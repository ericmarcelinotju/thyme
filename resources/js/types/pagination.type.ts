import { Ref } from 'vue'
import { Option } from './option.type'

export class PaginationLink {
  url: string
  label: string
  active: boolean
}

export class PaginationResponse<T> {
  current_page: number
  data: T[]
  first_page_url: string
  from: number
  last_page: number
  last_page_url: string
  links: PaginationLink[]
  next_page_url: string
  path: string
  per_page: number
  prev_page_url: string
  to: number
  total: number
}