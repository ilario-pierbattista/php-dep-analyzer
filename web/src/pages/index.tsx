import React, {useEffect, useState} from "react";
import { Alert, Button } from "react-bootstrap";

export function Index() {
    const [data, setData] = useState<unknown>([])

    const getData = () => {
        const data = require('./../data.json')
        setData(data)
        console.log(data)
    }

    useEffect(() => {
        getData()
    }, [])

    return (
        <React.Fragment>
            <Button>Hello</Button>
        </React.Fragment>
    )
}

export function NotFound() {
    return (
        <React.Fragment>
            <Alert variant="warning">404: This page does not exist</Alert>
        </React.Fragment>
    )
}