import React from 'react'
import styled from 'styled-components'
import VideoRequestList from "../components/VideoRequestList";

const HomeViewInner = styled.div`
  
`


const HomeView: React.FC = () => {

    return (
        <HomeViewInner>
            <h1>Je suis la home</h1>
            <React.Suspense fallback={<div>Loading requests...</div>}>
                <VideoRequestList/>
            </React.Suspense>
        </HomeViewInner>
    )
}

export default HomeView
