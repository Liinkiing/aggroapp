// @ts-ignore
import React, {useTransition} from 'react'
import styled from 'styled-components'
import {Suspendable, useSuspendable} from "../react-fetch-hooks";
import {AggroApiVideoRequest} from "../@types/api";

const VideoRequestItemInner = styled.div`
  
`

const VideoRequestItem: React.FC<{ requestId: string, suspendable: Suspendable<AggroApiVideoRequest, { id: string }> }> = ({suspendable, requestId}) => {
    const [startTransition] = useTransition({
        timeoutMs: 300
    })
    const result = useSuspendable(suspendable);

    const fetchDetail = () => {
        startTransition(() => {
            result.refetch({id: requestId});
        });
    };

    return (
        <VideoRequestItemInner>
            {result.data.id === "NONE" ?
                <button onClick={fetchDetail}>Load nested ressource</button> :
                <span>Sub ressource :
                    <code>{JSON.stringify(result.data)}</code>
                </span>
            }
        </VideoRequestItemInner>
    )
}

export default VideoRequestItem
