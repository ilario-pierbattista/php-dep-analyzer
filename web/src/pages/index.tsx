import React, {useEffect, useState} from "react";
import { Alert, Button } from "react-bootstrap";

export function Index() {
    const [data, setData] = useState([])

    const getData = () => {
        fetch('file:///home/ilario/Projects/utils/php-dep-analysis/output/data.json')
            .then(r => r.json())
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