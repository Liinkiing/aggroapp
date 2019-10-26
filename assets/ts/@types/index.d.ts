export interface TranslationsResponse {
    defaultDomain: string,
    fallback: string,
    translations: {
        [locale: string]: {
            messages: {
                [key: string]: any
            }
        }
    }
}
