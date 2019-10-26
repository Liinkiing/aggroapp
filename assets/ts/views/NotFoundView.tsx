import React from 'react'
import styled from 'styled-components'
import {FormattedMessage} from "react-intl";

const NotFoundViewInner = styled.div`
  
`

const NotFoundView: React.FC = () => {

    return (
        <NotFoundViewInner>
            <h1><FormattedMessage id="not_found_page.content"/></h1>
        </NotFoundViewInner>
    )
}

export default NotFoundView
