// @ts-ignore
import React from 'react'
import styled from 'styled-components'
import {createFetcher, useSuspendable} from "../react-fetch-hooks";
import {AggroApiGetVideoRequests, AggroApiVideoRequest} from "../@types/api";
import VideoRequestItem from "../components/VideoRequestItem";

const VideoRequestListInner = styled.div`
  
`
const suspendable = createFetcher<AggroApiGetVideoRequests, {}>(
    () => fetch('/api/video/requests').then(r => r.json())
).prefetch({});


const VideoRequestList: React.FC = () => {
    const {data: requests} = useSuspendable(suspendable);

    return (
        <VideoRequestListInner>
            {requests.map(r =>
                <li key={r.id}>
                    {r.tweetUrl}
                    <React.Suspense fallback={<div>Loading sub request...</div>}>
                        <VideoRequestItem requestId={r.id}
                                          suspendable={createFetcher<AggroApiVideoRequest, { id: string }>(
                                              ({id}) => fetch(`/api/video/request/${id}`).then(r => r.json())
                                          ).lazyFetch({
                                              id: "NONE",
                                          })}/>
                    </React.Suspense>
                </li>
            )}
        </VideoRequestListInner>
    )
}

export default VideoRequestList
