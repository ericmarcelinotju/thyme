export class Option {
  label: string
  value: string
  object?: any
}

export class OptionObject {
  id: string
  name: string
  disabled?: boolean
  variant: Array<{
    id: string,
    name: string,
    disabled: boolean
  }>
}

export class UserOptionObject {
  email: string
  firstName: string
  id: string
  lastName: string
  phone: string
  updateAt: string
  username: string

  role: {
    [ x: string ]: boolean | number | string
  }
}
