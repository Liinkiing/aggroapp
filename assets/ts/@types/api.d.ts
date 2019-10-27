export type Uuid = string

interface ApiHrefs {
    self: string,
    show: string,
    download: string
}

export interface AggroApiVideoRequest {
    tweetUrl: string,
    requestBy: string,
    processed: boolean,
    replyUrl: string,
    video: AggroApiVideoRequestVideo
    id: Uuid,
    _href: Pick<ApiHrefs, 'self'>
}

export interface AggroApiVideoRequestVideoThumbnail {
    filename: string,
    mimeType: string,
    id: Uuid,
    _href: Pick<ApiHrefs, 'show'>
}

export interface AggroApiVideoRequestVideo {
    filename: string,
    mimeType: string,
    id: Uuid,
    thumbnail: AggroApiVideoRequestVideoThumbnail,
    _href: Pick<ApiHrefs, 'download'>
}

export interface AggroApiPostVideoRequestBody {
    tweetUrl: string,
    requestedBy: string,
    replyUrl: string
}

export interface AggroApiGetVideoRequestsQuery {
    tweet_url: string
}

export type AggroApiGetVideoRequests = AggroApiVideoRequest[]

export type AggroApiPostVideoRequest = AggroApiVideoRequest
