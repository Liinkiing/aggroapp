import {createGlobalStyle} from "styled-components"
import {lightGray} from './modules/colors'
import bootstrap from './bootstrap'

export default createGlobalStyle`
  ${bootstrap};
  
  * {
    box-sizing: border-box;
  }
  
  html {
    font-size: 100%;
  }
  
  body {
    background: ${lightGray};
    font-family: 'Nunito', sans-serif;
  }
  
  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    font-weight: 700;
    font-family: 'Hind Siliguri', sans-serif;
  }
  
  a {
    color: inherit;
    text-decoration: none;
    transition: all .3s;
    opacity: 0.8;
    &:hover {
      cursor: pointer;
      opacity: 1;
    }
  }
`
