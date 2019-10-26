import React, {FunctionComponent} from 'react'
import {RouteComponentProps} from '@reach/router'

type Props = { component: FunctionComponent } & RouteComponentProps

const Route: FunctionComponent<Props> = ({component: Component, ...rest}) => (
    <Component {...rest} />
);

export default Route;
