import {Dispatch, SetStateAction, useEffect, useReducer, useState} from "react";

type ActionType<Data> =
    | { type: 'FETCH_INIT' }
    | { type: 'FETCH_SUCCESS', payload: Data }
    | { type: 'FETCH_FAILURE' };

interface State<Data> {
    isLoading: boolean,
    isError: boolean,
    data: Data
}

const createReducer = <Data>() => (state: State<Data>, action: ActionType<Data>): State<Data> => {
    switch (action.type) {
        case 'FETCH_INIT':
            return {
                ...state,
                isLoading: true,
                isError: false
            };
        case 'FETCH_SUCCESS':
            return {
                ...state,
                isLoading: false,
                isError: false,
                data: action.payload,
            };
        case 'FETCH_FAILURE':
            return {
                ...state,
                isLoading: false,
                isError: true,
            };
        default:
            throw new Error();
    }
};

const useApi = <Data>(initialUrl: string, initialData: Data): [
    State<Data>,
    Dispatch<SetStateAction<string>>
] => {
    const [url, setUrl] = useState<string>(initialUrl);
    const [state, dispatch] = useReducer(createReducer<Data>(), {
        isLoading: false,
        isError: false,
        data: initialData
    });
    useEffect(() => {
        let didCancel = false;
        const fetchData = async () => {
            dispatch({type: 'FETCH_INIT'});
            try {
                const result = await (await fetch(url)).json()
                if (!didCancel) {
                    dispatch({type: 'FETCH_SUCCESS', payload: result as Data});
                }
            } catch (error) {
                if (!didCancel) {
                    dispatch({type: 'FETCH_FAILURE'});
                }
            }
        };
        fetchData();
        return () => {
            didCancel = true;
        };
    }, [url]);
    return [state, setUrl];
};

export default useApi
