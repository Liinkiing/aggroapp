import React from 'react'
import ReactDOM from 'react-dom'
import App from './App'
import GlobalStyle from "./styles/global"
import {IntlProvider} from "react-intl";
import {TranslationsResponse} from "./@types";

(async () => {

    const response = await (await fetch('/translations/messages.json')).json() as TranslationsResponse
    const lang = document.querySelector('html')?.getAttribute('lang') || response.fallback;

    // @ts-ignore
    ReactDOM.createRoot(document.getElementById('root')).render(
        <IntlProvider locale={lang} messages={response.translations[lang].messages}>
            <GlobalStyle/>
            <App/>
        </IntlProvider>
    )

})()
