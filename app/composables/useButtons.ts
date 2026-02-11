export type ButtonColor = 'pink' | 'yellow' | 'white' | 'transparent' | 'dark' | 'light' | 'fuchsia'
export type ButtonVariant = 'filled' | 'outline'

export interface ButtonConfig {
  label: string
  color: ButtonColor
  variant?: ButtonVariant
  to?: string
  width?: string
  inset?: string
}

type ButtonsMap = Record<string, ButtonConfig>

// Shared cache across components
let _buttonsCache: ButtonsMap | null = null
let _buttonsFetchPromise: Promise<ButtonsMap | null> | null = null

const defaultButton: ButtonConfig = {
  label: '',
  color: 'pink',
  variant: 'filled',
  to: '#'
}

export const useButtons = () => {
  const { fetchData } = useApi()

  const fetchButtons = async (): Promise<ButtonsMap | null> => {
    if (_buttonsCache) return _buttonsCache

    if (_buttonsFetchPromise) return _buttonsFetchPromise

    _buttonsFetchPromise = fetchData<ButtonsMap>('buttons', 'buttons.json').then((data) => {
      if (data) {
        _buttonsCache = data
      }
      _buttonsFetchPromise = null
      return _buttonsCache
    })

    return _buttonsFetchPromise
  }

  const buttons = ref<ButtonsMap | null>(_buttonsCache)

  const loadButtons = async () => {
    const data = await fetchButtons()
    if (data) {
      buttons.value = data
    }
  }

  const getButton = (id: string): ButtonConfig => {
    if (buttons.value && buttons.value[id]) {
      return buttons.value[id]
    }
    return defaultButton
  }

  return {
    buttons,
    loadButtons,
    getButton
  }
}
