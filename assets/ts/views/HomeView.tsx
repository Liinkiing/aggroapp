import React from 'react'
import styled from 'styled-components'
import {AggroApiGetVideoRequests} from "../@types/api";
import {createAsync, useAsync} from "react-hooks-fetch";

const HomeViewInner = styled.div`
  
`

const initialResult = createAsync<AggroApiGetVideoRequests>('/api/video/requests');

const HomeView: React.FC = () => {
    const { data: requests } = useAsync<AggroApiGetVideoRequests>(initialResult);
    return (
        <HomeViewInner>
            <h1>Je suis la home</h1>
            <ul>
                {requests.map(r =>
                    <li>{r.tweetUrl}</li>
                )}
            </ul>
        </HomeViewInner>
    )
}

export default HomeView
