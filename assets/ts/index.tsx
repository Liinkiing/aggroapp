import React from 'react'
import ReactDOM from 'react-dom'
import App from './App'
import GlobalStyle from "./styles/global"
import {IntlProvider} from "react-intl";
import {TranslationsResponse} from "./@types";

const messages = ((window as any).__TRANSLATIONS as TranslationsResponse)
delete (window as any).__TRANSLATIONS
document.querySelector('script#translations')?.remove()
const lang = document.querySelector('html')?.getAttribute('lang') || messages.fallback;

// @ts-ignore
ReactDOM.createRoot(document.getElementById('root')).render(
    <IntlProvider locale={lang} messages={messages.translations[lang].messages}>
        <GlobalStyle/>
        <App/>
    </IntlProvider>
)
